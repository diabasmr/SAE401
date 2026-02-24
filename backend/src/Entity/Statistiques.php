<?php


namespace App\Entity;


use App\Repository\StatistiquesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

//j'imagine que c'est la clé primaire
#[ORM\Entity(repositoryClass: StatistiquesRepository::class)]
#[ORM\Table(
   name: "statistiques",
   uniqueConstraints: [
       new ORM\UniqueConstraint(
           name: "unique_departement_annee",
           columns: ["code_departement", "annee_publication"]
       )
   ]
)]
class Statistiques
{
   #[ORM\Id]
   #[ORM\GeneratedValue]
   #[ORM\Column]
   private ?int $id = null;

   //clé etrangere code departement
   #[ORM\ManyToOne]
   #[ORM\JoinColumn(
       name: "code_departement",
       referencedColumnName: "code",
       nullable: false,
       onDelete: "CASCADE"
   )]
   private ?Departement $codeDepartement = null;

   //clé etrangere code departement
   #[ORM\ManyToOne]
   #[ORM\JoinColumn(
       name: "code_region",
       referencedColumnName: "code",
       nullable: false,
       onDelete: "CASCADE"
   )]
   private ?Region $codeRegion = null;

   #[ORM\Column(type: "smallint")]
   private ?int $anneePublication = null;

   #[ORM\Column(nullable: true)]
   private ?int $nombreHabitants = null;

   #[ORM\Column(name: "variation_population_10_ans", type: "decimal", precision: 6, scale: 2, nullable: true)]
   private ?string $variationPopulation10Ans = null;

   #[ORM\Column(nullable: true)]
   private ?int $nombreLogements = null;

   #[ORM\Column(name: "moyenne_annuelle_construction_neuve_10_ans", nullable: true)]
   private ?int $moyenneAnnuelleConstructionNeuve10Ans = null;

   #[ORM\Column(type: "decimal", precision: 8, scale: 2, nullable: true)]
   private ?string $construction = null;

   #[ORM\Column(nullable: true)]
   private ?int $parcSocialNombreLogements = null;

   #[ORM\Column(nullable: true)]
   private ?int $parcSocialLogementsDemolis = null;

   #[ORM\Column(type: "decimal", precision: 5, scale: 2, nullable: true)]
   private ?string $parcSocialTauxLogementsVacants = null;


   // ======================
   // GETTERS / SETTERS
   // ======================

   public function getId(): ?int
   {
       return $this->id;
   }

   public function getCodeDepartement(): ?int
   {
       return $this->codeDepartement;
   }

   public function getCodeRegion(): ?int
   {
       return $this->codeRegion;
   }

   public function getAnneePublication(): ?int
   {
       return $this->anneePublication;
   }

   public function getNombreHabitants(): ?int
   {
       return $this->nombreHabitants;
   }

   public function getVariationPopulation10Ans(): ?int
   {
       return $this->variationPopulation10Ans;
   }

   public function getNombreLogements(): ?int
   {
       return $this->nombreLogements;
   }

   public function getMoyenneAnnuelleConstructionNeuve10Ans(): ?int
   {
       return $this->moyenneAnnuelleConstructionNeuve10Ans;
   }

   public function getConstructions(): ?int
   {
       return $this->construction;
   }

   public function getParcSocialNombreLogements(): ?int
   {
       return $this->parcSocialNombreLogements;
   }

   public function getParcSocialLogementsDemolis(): ?int
   {
       return $this->parcSocialLogementsDemolis;
   }

   public function getParcSocialTauxLogementsVacants(): ?int
   {
       return $this->parcSocialTauxLogementsVacants;
   }

   public function setCodeDepartement(?Departement $codeDepartement): static
   {
       $this->codeDepartement = $codeDepartement;
       return $this;
   }


   public function setCodeRegion(?Region $codeRegion): static
   {
       $this->codeRegion = $codeRegion;
       return $this;
   }

   public function setAnneePublication(int $anneePublication): static
   {
       $this->anneePublication = $anneePublication;
       return $this;
   }

   public function setNombreHabitants(?int $nombreHabitants): static
   {
       $this->nombreHabitants = $nombreHabitants;
       return $this;
   }

   public function setVariationPopulation10Ans(?string $variationPopulation10Ans): static
   {
       $this->variationPopulation10Ans = $variationPopulation10Ans;
       return $this;
   }

   public function setNombreLogements(?int $nombreLogements): static
   {
       $this->nombreLogements = $nombreLogements;
       return $this;
   }


   public function setMoyenneAnnuelleConstructionNeuve10Ans(?int $moyenneAnnuelleConstructionNeuve10Ans): static
   {
       $this->moyenneAnnuelleConstructionNeuve10Ans = $moyenneAnnuelleConstructionNeuve10Ans;
       return $this;
   }

   public function setConstruction(?string $construction): static
   {
       $this->construction = $construction;
       return $this;
   }

   public function setParcSocialNombreLogements(?int $parcSocialNombreLogements): static
   {
       $this->parcSocialNombreLogements = $parcSocialNombreLogements;
       return $this;
   }

   public function setParcSocialLogementsDemolis(?int $parcSocialLogementsDemolis): static
   {
       $this->parcSocialLogementsDemolis = $parcSocialLogementsDemolis;
       return $this;
   }

   public function setParcSocialTauxLogementsVacants(?string $parcSocialTauxLogementsVacants): static
   {
       $this->parcSocialTauxLogementsVacants = $parcSocialTauxLogementsVacants;
       return $this;
   }
}