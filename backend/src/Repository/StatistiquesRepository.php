<?php
// ce fichier sert à faire les requetes sql pour la table statistiques
namespace App\Repository;

use App\Entity\Statistiques;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StatistiquesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistiques::class);
    }
}