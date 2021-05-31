<?php

namespace App\Repository;

use App\Entity\MyTicketUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MyTicketUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method MyTicketUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method MyTicketUser[]    findAll()
 * @method MyTicketUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MyTicketUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MyTicketUser::class);
    }

    // /**
    //  * @return MyTicketUser[] Returns an array of MyTicketUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MyTicketUser
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
