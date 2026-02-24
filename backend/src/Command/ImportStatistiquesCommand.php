<?php


namespace App\Command;


use App\Entity\Departement;
use App\Entity\Region;
use App\Entity\Statistiques;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
   name: 'app:import:stats',
   description: 'Import des statistiques depuis un CSV'
)]
class ImportStatistiquesCommand extends Command
{
   public function __construct(private EntityManagerInterface $em)
   {
       parent::__construct();
   }

   protected function configure(): void
   {
       $this
           ->setDescription('Import des statistiques depuis un CSV')
           ->addArgument('file', InputArgument::REQUIRED, 'Chemin du fichier CSV');
   }

   protected function execute(InputInterface $input, OutputInterface $output): int
   {
       $filePath = $input->getArgument('file');

       if (!file_exists($filePath)) {
           $output->writeln('<error>Fichier introuvable</error>');
           return Command::FAILURE;
       }

       $handle = fopen($filePath, 'r');
       $header = fgetcsv($handle, 0, ',');

       $batchSize = 50;
       $i = 0;

       while (($row = fgetcsv($handle, 0, ',')) !== false) {
           $data = array_combine($header, $row);

           $codeDepartement = $this->formatCodeDepartement($data['code_departement']);

           $departement = $this->em
               ->getRepository(Departement::class)
               ->find($codeDepartement);

           if (!$departement) {
               dump($data['code_departement']);exit;
               $output->writeln("Département absent : " . $data['code_departement']);
               continue;
           }

           $codeRegion = $this->formatCodeRegion($data['code_region']);

           $region = $this->em
               ->getRepository(Region::class)
               ->find($codeRegion);

           if (!$region) {
               dump($data['code_region']);exit;
               $output->writeln("Région absente : " . $data['code_region']);
               continue;
           }

           // Vérifie si déjà existant (unique constraint)
           $existing = $this->em
               ->getRepository(Statistiques::class)
               ->findOneBy([
                   'departement' => $departement,
                   'region' => $region,
                   'anneePublication' => (int)$data['année_publication']
               ]);

           if ($existing) {
               continue;
           }

           $stat = new Statistiques();
           $stat->setcodeDepartement($departement);
           $stat->setcodeRegion($region);
           $stat->setAnneePublication((int)$data['année_publication']);
           $stat->setNombreHabitants($this->int($data["Nombre  d'habitants"]));
           $stat->setVariationPopulation10Ans($this->decimal($data["Variation de la population sur 10 ans (en %)"]));
           $stat->setNombreLogements($this->int($data["Nombre de logements"]));
           $stat->setMoyenneAnnuelleConstructionNeuve10Ans($this->int($data["Moyenne annuelle de la construction neuve sur 10 ans"]));
           $stat->setConstruction($this->decimal($data["Construction"]));
           $stat->setParcSocialNombreLogements($this->int($data["Parc social - Nombre de logements"]));
           $stat->setParcSocialLogementsDemolis($this->int($data["Parc social - Logements démolis"]));
           $stat->setParcSocialTauxLogementsVacants($this->decimal($data["Parc social - Taux de logements vacants* (en %)"]));

           $this->em->persist($stat);

           if (($i % $batchSize) === 0) {
               $this->em->flush();
               $this->em->clear();
           }


           $i++;
       }


       $this->em->flush();
       fclose($handle);


       $output->writeln("<info>Import terminé : $i lignes</info>");


       return Command::SUCCESS;
   }


   private function decimal($value): ?string
   {
       if ($value === null || $value === '') {
           return null;
       }
       return number_format((float)$value, 2, '.', '');
   }


   private function int($value): ?int
   {
       if ($value === null || $value === '') {
           return null;
       }
       return (int)$value;
   }


   private function formatCodeDepartement(string $code): string
   {
       $code = trim($code);


       // Corse déjà OK (2A / 2B)
       if (in_array($code, ['2A', '2B'])) {
           return $code;
       }


       // DOM 3 chiffres (971, 972, etc.)
       if (strlen($code) === 3) {
           return $code;
       }


       // Départements métropole → padding à 2 chiffres
       return str_pad($code, 2, '0', STR_PAD_LEFT);
   }

   private function formatCodeRegion(string $code): string
   {
         $code = trim($code);
    
         // Régions métropole → padding à 2 chiffres
         return str_pad($code, 2, '0', STR_PAD_LEFT);
   }
}

// LANCER AVEC : php bin/console app:import:stats src/Command/stats.csv