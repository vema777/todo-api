<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
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
     * Gibt alle Aufgaben zurück, die einem Nutzer zugewiesen sind oder vom Nutzer erstellt wurden.
     * @param int $id Die Id des Benutzers
     * @return array Die Liste von Aufgaben
     * @throws Exception
     */
    public function findTasksByUserId(int $id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT *
            FROM task
            WHERE task.user_id = :user_id AND task.is_deleted = false
            OR task.id = (SELECT task_id FROM task_user WHERE user_id = :user_id) AND task.is_deleted = false
        ';

        $resultSet = $conn->executeQuery($sql, ['user_id' => $id]);

        return $resultSet->fetchAllAssociative();
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
