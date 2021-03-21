<?php

namespace App\Repository;

use App\Entity\Tasktodo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tasktodo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tasktodo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tasktodo[]    findAll()
 * @method Tasktodo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TasktodoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tasktodo::class);
    }

    public function fixtureIndex()
	{
		$connection = $this->getEntityManager()->getConnection();
		$connection->exec("ALTER TABLE tasktodo AUTO_INCREMENT = 1;");
	}

    // /**
    //  * @return Tasktodo[] Returns an array of Tasktodo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tasktodo
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
