<?php

namespace App\Repository;

use App\Entity\Usertodo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Usertodo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usertodo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usertodo[]    findAll()
 * @method Usertodo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsertodoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usertodo::class);
    }

    public function fixtureIndex()
	{
		$connection = $this->getEntityManager()->getConnection();
		$connection->exec("ALTER TABLE usertodo AUTO_INCREMENT = 1;");
	}

    // /**
    //  * @return Usertodo[] Returns an array of Usertodo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Usertodo
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
