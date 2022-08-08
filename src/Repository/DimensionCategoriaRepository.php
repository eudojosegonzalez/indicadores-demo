<?php

namespace App\Repository;

use App\Entity\DimensionCategoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DimensionCategoria>
 *
 * @method DimensionCategoria|null find($id, $lockMode = null, $lockVersion = null)
 * @method DimensionCategoria|null findOneBy(array $criteria, array $orderBy = null)
 * @method DimensionCategoria[]    findAll()
 * @method DimensionCategoria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DimensionCategoriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DimensionCategoria::class);
    }

    public function add(DimensionCategoria $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DimensionCategoria $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DimensionCategoria[] Returns an array of DimensionCategoria objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DimensionCategoria
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
