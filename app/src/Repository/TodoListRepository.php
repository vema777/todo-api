<?php

namespace App\Repository;

use App\Entity\TodoList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TodoList>
 *
 * @method TodoList|null find($id, $lockMode = null, $lockVersion = null)
 * @method TodoList|null findOneBy(array $criteria, array $orderBy = null)
 * @method TodoList[]    findAll()
 * @method TodoList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodoList::class);
    }

    public function queryTodoLists(): array
    {
        return $this->createQueryBuilder('todoLists')
            ->andWhere('todoLists.isDeleted = false')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return TodoList[] Returns an array of TodoList objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TodoList
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
