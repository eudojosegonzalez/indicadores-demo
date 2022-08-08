<?php

namespace App\Repository;

use App\Entity\Registro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Registro>
 *
 * @method Registro|null find($id, $lockMode = null, $lockVersion = null)
 * @method Registro|null findOneBy(array $criteria, array $orderBy = null)
 * @method Registro[]    findAll()
 * @method Registro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegistroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registro::class);
    }

    public function add(Registro $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Registro $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Registro[] Returns an array of Registro objects
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

    //    public function findOneBySomeField($value): ?Registro
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    public function findAllRegistroByEnteCategoriaPeriodo(int $enteId, int $categoriaId, int $periodoId)
    {

        /*
    Registro
        id	int(11) AI PK
        ente_id	int(11)
        categoria_id	int(11)
        indicador_id	int(11)
        periodo_id	int(11)
        valor	decimal(13,2)

    Indicador
        id	int(11) AI PK
        ente_id	int(11)
        categoria_id	int(11)
        nombre	varchar(255)
        formula	longtext
        descripcion	longtext    

    */
        $conn = $this->getEntityManager()->getConnection();
        $sql =  'select     
        i.nombre,
        i.formula,
        i.descripcion,
        i.ente_id,
        i.categoria_id,
        i.id  as indicador_id,    
        r.id as registro_id,
        r.periodo_id,
        ifnull(r.valor,0) as valor
        from indicador i
        left join registro r
        on (r.indicador_id = i.id and r.categoria_id=i.categoria_id and r.ente_id=i.ente_id)
        where i.ente_id = :enteId 
        and i.categoria_id = :categoriaId
        and r.periodo_id = :periodoId';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['enteId' => $enteId, 'categoriaId' => $categoriaId, 'periodoId' => $periodoId]);
        return $resultSet->fetchAllAssociative();
    }
}
