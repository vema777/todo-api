<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * Querry um alle Tasks anhand der TodoListen zu holen.
     * @param int $listId
     * @return array
     */
    public function findTaskByTodoList(int $listId): array
    {
        return $this->createQueryBuilder('tasks')
            ->andWhere('tasks.list = :val')
            ->andWhere('tasks.isDeleted = false')
            ->setParameter('val', $listId)
            ->getQuery()
            ->getResult();
    }

    /**
     *Query um Aufgaben anhand der UserId zu holen.
     * @param int $id Die Id des Benutzers
     * @return array Die Liste von Aufgaben
     */
    public function findTaskByUserId(int $id): array
    {
        return $this->createQueryBuilder('tasks')
            ->andWhere('tasks.user = :val')
            ->andWhere('tasks.isDeleted = false')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Task[] Returns an array of Task objects
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

//    public function findOneBySomeField($value): ?Task
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
