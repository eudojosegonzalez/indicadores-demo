<?php

namespace App\Repository;

use App\Entity\Indicador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Indicador>
 *
 * @method Indicador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Indicador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Indicador[]    findAll()
 * @method Indicador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndicadorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Indicador::class);
    }

    public function add(Indicador $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Indicador $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Indicador[] Returns an array of Indicador objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Indicador
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAllIndicador(): array
    {
        /*
    Ente
        id	int(11) AI PK
        nombre	varchar(255)

    Categoria
        id	int(11) AI PK
        ente_id	int(11)
        nombre	varchar(255)    

    Indicador
        id	int(11) AI PK
        ente_id	int(11)
        categoria_id	int(11)
        nombre	varchar(255)
        formula	longtext
        descripcion	longtext
    */
        $conn = $this->getEntityManager()->getConnection();
        $sql =  'SELECT 
        i.id,
        i.nombre,
        i.formula,
        i.descripcion,
        e.nombre as nombre_ente,
        c.nombre as nombre_categoria
        from indicador i
        inner join ente e on e.id = i.ente_id
        inner join categoria c on (c.id = i.categoria_id and c.ente_id = i.ente_id)
        order by e.id,c.id,i.id';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAllAssociative();
    }

    public function findAllIndicadorEnteCategoria(int $enteId, int $categoriaId): array
    {
        /*
    Ente
        id	int(11) AI PK
        nombre	varchar(255)

    Categoria
        id	int(11) AI PK
        ente_id	int(11)
        nombre	varchar(255)    

    Indicador
        id	int(11) AI PK
        ente_id	int(11)
        categoria_id	int(11)
        nombre	varchar(255)
        formula	longtext
        descripcion	longtext
    */
        $conn = $this->getEntityManager()->getConnection();
        $sql =  'SELECT 
        i.id,
        i.nombre,
        i.formula,
        i.descripcion,
        e.nombre as nombre_ente,
        c.nombre as nombre_categoria
        from indicador i
        inner join ente e on e.id = i.ente_id
        inner join categoria c on (c.id = i.categoria_id and c.ente_id = i.ente_id)
        where i.ente_id = :enteId and i.categoria_id = :categoriaId
        order by e.id,c.id,i.id';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['enteId' => $enteId, 'categoriaId' => $categoriaId]);
        return $resultSet->fetchAllAssociative();
    }

    public function findAllIndicadorEnte(int $enteId): array
    {
        /*
    Ente
        id	int(11) AI PK
        nombre	varchar(255)

    Categoria
        id	int(11) AI PK
        ente_id	int(11)
        nombre	varchar(255)    

    Indicador
        id	int(11) AI PK
        ente_id	int(11)
        categoria_id	int(11)
        nombre	varchar(255)
        formula	longtext
        descripcion	longtext
    */
        $conn = $this->getEntityManager()->getConnection();
        $sql =  'SELECT 
        i.id,
        i.nombre,
        i.formula,
        i.descripcion,
        e.nombre as nombre_ente,
        c.nombre as nombre_categoria
        from indicador i
        inner join ente e on e.id = i.ente_id
        inner join categoria c on (c.id = i.categoria_id and c.ente_id = i.ente_id)
        where i.ente_id = :enteId 
        order by e.id,c.id,i.id';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['enteId' => $enteId);
        return $resultSet->fetchAllAssociative();
    }    
}
