<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    /**
     * @return Product[]
     * @param $days
     */
    public function deleteOlderThan($days = 14)
    {
        //     $entityManager = $this->getEntityManager();

        //     $query = $entityManager
        //         ->createQuery(
        //             'DELETE c
        //         FROM App\Entity\Contact c
        //         WHERE c.date_created < NOW() - INTERVAL :days DAY'
        //         )
        //         ->setParameter('days', $days);

        //     // returns an array of Product objects
        //     return $query;

        $em = $this->getEntityManager();

        $sql =
            'DELETE FROM contact WHERE date_created < NOW() - INTERVAL :days DAY';

        $statement = $em->getConnection()->prepare($sql);

        $statement->bindValue('days', $days);
        // $statement->execute();

        return $statement;
    }

    // /**
    //  * @return Contact[] Returns an array of Contact objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Contact
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
