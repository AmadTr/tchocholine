<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Product $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Product[] Returns an array of Product objects
     */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name like :val')
            ->setParameter('val', '%' . $value[0] . '%')
            ->orderBy('p.name', 'ASC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function sort($value = null, $value2 = null)
    {
        // dd($value,$value2);
        if ($value[1] == 'croissant') {
            $order1 = 'p.price';
            $order2 = 'ASC';
        }
        if ($value[1] == 'décroissant') {
            $order1 = 'p.price';
            $order2 = 'DESC';
        }
        if ($value == null && $value2 == null) {
            $order1 = 'p.name';
            $order2 = 'ASC';
        }
        if ($value[0]) {
            return $this->createQueryBuilder('p')
                ->andWhere('p.category = :val')
                ->setParameter('val', $value[0]->getId())
                ->orderBy($order1, $order2)
                // ->setMaxResults(10)
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('p')
                // ->andWhere('p.name like :val')
                // ->setParameter('val','%'.$value[0].'%')
                ->orderBy($order1, $order2)
                // ->setMaxResults(10)
                ->getQuery()
                ->getResult();
        }
    }
    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
