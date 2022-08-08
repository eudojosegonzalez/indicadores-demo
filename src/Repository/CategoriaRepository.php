<?php

namespace App\Repository;

use App\Entity\Categoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categoria>
 *
 * @method Categoria|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categoria|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categoria[]    findAll()
 * @method Categoria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categoria::class);
    }

    public function add(Categoria $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Categoria $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Categoria[] Returns an array of Categoria objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Categoria
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAllCategoriaByEnte(int $enteId): array
    {
        /*
        Ente
            id	int(11) AI PK
            nombre	varchar(255)

        Categoria
            id	int(11) AI PK
            ente_id	int(11)
            nombre	varchar(255)    

        */
        $conn = $this->getEntityManager()->getConnection();
        $sql =  'SELECT 
        c.id,
        c.nombre,
        c.ente_id
        from  categoria c 
        where c.ente_id = :enteId';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['enteId' => $enteId]);
        return $resultSet->fetchAllAssociative();
    }

    public function countAllCategoriasEnte(int $enteId): array
    {
        /*
        Ente
            id	int(11) AI PK
            nombre	varchar(255)

        Categoria
            id	int(11) AI PK
            ente_id	int(11)
            nombre	varchar(255)    

        */
        $conn = $this->getEntityManager()->getConnection();
        $sql =  'SELECT 
        ifnull(count(c.id),0) as total
        from  categoria c 
        where c.ente_id = :enteId';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['enteId' => $enteId]);
        return $resultSet->fetchAllAssociative();
    }
}
