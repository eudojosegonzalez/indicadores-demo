<?php

namespace App\Repository;

use App\Entity\Reporte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reporte>
 *
 * @method Reporte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reporte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reporte[]    findAll()
 * @method Reporte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReporteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reporte::class);
    }

    public function add(Reporte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reporte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Reporte[] Returns an array of Reporte objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reporte
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function queryIndicators(int $enteId, int $categoriaId, int $periodoId, string $procedimiento): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql =  'call `indicadores_demo`.`SP_Ejecuta_Procedimiento`(:enteId,:categoriaId,:periodoId, :procedimiento)';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['enteId' => $enteId, 'categoriaId' => $categoriaId, 'periodoId' => $periodoId, 'procedimiento' => $procedimiento]);
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
}
