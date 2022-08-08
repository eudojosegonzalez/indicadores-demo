<?php

namespace App\Repository;

use App\Entity\Periodo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Periodo>
 *
 * @method Periodo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Periodo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Periodo[]    findAll()
 * @method Periodo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Periodo::class);
    }

    public function add(Periodo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Periodo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Periodo[] Returns an array of Periodo objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Periodo
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findPeriodosRango(int $inicio, int $fin): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql =  "SELECT 
        *
        from  periodo p
        where  p.id between :inicio and  :fin";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['inicio' => $inicio, 'fin' => $fin]);
        return $resultSet->fetchAllAssociative();
    }

    public function countAllDocumentsPeriodo(int $id): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql =  "SELECT 
        count(id) as ndocumentos
        from  registro r
        where  r.periodo_id : periodoId";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['periodoId' => $id]);
        return $resultSet->fetchAllAssociative();
    }
}
