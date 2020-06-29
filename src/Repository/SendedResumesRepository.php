<?php

namespace App\Repository;

use App\Entity\SendedResumes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SendedResumes|null find($id, $lockMode = null, $lockVersion = null)
 * @method SendedResumes|null findOneBy(array $criteria, array $orderBy = null)
 * @method SendedResumes[]    findAll()
 * @method SendedResumes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SendedResumesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SendedResumes::class);
    }



     /**
     * @return SendedResumes[]
     */
    public function groupResumeByNameAndReaction(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s.path, SUM(s.reaction) AS react_sum
            FROM App\Entity\SendedResumes s
            GROUP BY s.path
            ORDER BY react_sum DESC'
        );

        // returns an array of Product objects
        return $query->getResult();
    }

    // /**
    //  * @return SendedResumes[] Returns an array of SendedResumes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SendedResumes
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
