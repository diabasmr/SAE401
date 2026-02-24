<?php


namespace App\Command;

use App\Entity\Region;
use App\Entity\Departement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'app:import:departement',
    description: 'Import des départements depuis un CSV'
)]
class ImportDepartementCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    //configure en ajoutant le fichier dans l'input
    protected function configure(): void
    {
        $this
            ->setDescription('Import des départements depuis un CSV')
            ->addArgument('file', InputArgument::REQUIRED, 'Chemin du fichier CSV');
    }

    //    Fonction principale qui fait tout
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('file'); //recupere le csv

        if (!file_exists($filePath)) {
            $output->writeln('<error>Fichier introuvable</error>');
            return Command::FAILURE;
        }

        $handle = fopen($filePath, 'r'); //ouvre le csv en mode lecture
        $header = fgetcsv($handle, 0, ','); //recupere les données du header seulement (les colonnes lecture à la ligne 0) et la séparation est la , -> produit un tableau avec

        $batchSize = 50;
        $i = 0;

        while (($row = fgetcsv($handle, 0, ',')) !== false) { //tant qu'on a une ligne
            $data = array_combine($header, $row); //association colonne[x] = ligne[valeur] permet de passser de $row[1] à $data['code']

            $codeDepartement = $this->formatCodeDepartement($data['code_departement']);

            $codeRegion = $this->formatCodeRegion($data['code_region']);

            $region = $this->em
                ->getRepository(Region::class)
                ->find($codeRegion);

            if (!$region) {
                dump($data['code_region']);exit;
                $output->writeln("Région absente : " . $data['code_region']);
                continue;
            }

            // Vérifie si déjà existant (unique constraint) - evite doublons
            $existing = $this->em
                ->getRepository(Departement::class)
                ->find($codeDepartement); //le codeDepartement est l'id de la table departement si il existe deja on passe a la ligne suivante pour eviter les doublons

            if ($existing) {
                continue;
            }

            // Création du département en tant qu'objet symfony
            $dep = new Departement();
            $dep->setCode($codeDepartement);
            $dep->setCodeRegion($region);
            $dep->setNom($data['nom_departement']);

            $this->em->persist($dep); //mise en attente memoire

            if ($i > 0 && ($i % $batchSize) === 0) { //tous les 50 enregistrements
                $this->em->flush(); //on envoie
                $this->em->clear(); //on vide la memoire
            }


            $i++; //on passe a la ligne suivante
        }


        $this->em->flush();
        fclose($handle);


        $output->writeln("<info>Import terminé : $i lignes</info>");


        return Command::SUCCESS;
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

// LANCER AVEC : php bin/console app:import:departement src/Command/stats.csv
// y'a un pb ici lors de l'execution