<?php

namespace App\Repository;

use App\Entity\AvisEntreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class AvisEntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvisEntreprise::class);
    }


}
