<?php
/**
 * Created by PhpStorm.
 * User: baotrannhat
 * Date: 2/23/18
 * Time: 1:17 PM
 */
namespace CRUDBundle\Repository;

use CRUDBundle\Entity\Tasks;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class TaskRepository
{
    private $em;
    private $classname;
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->classname = Tasks::class;
        $this->repo = $this->em->getRepository($this->classname);
    }

    public function getEm()
    {
        return $this->em;
    }

    public function getListByUserId($userId)
    {
        $query = $this->repo->createQueryBuilder('t')
            ->select('t.description,t.status,t.taskId,t.userId')
            ->where('t.userId = :userId')
            ->andWhere('t.isDeleted = :isNotDeleted')
            ->setParameter('userId', $userId)
            ->setParameter('isNotDeleted', 0)
            ->getQuery();
        return $query->getResult();
    }

    public function getTaskById($taskId)
    {
        return $this->repo->find($taskId);
    }

    public function changeStatus($taskId)
    {
        $task = $this->repo->find($taskId);
        $task->setStatus(intval(!$task->getStatus()));
        $this->em->flush();
        return $task;
    }

    public function changeIsDeleted($taskId)
    {
        $task = $this->repo->find($taskId);
        $task->setIsDeleted(!$task->getIsDeleted());
        $this->em->flush();
        return $task;
    }

    public function upSert($task=null)
    {
        if(!empty($task)) {
            $this->em->persist($task);
        }
        $this->em->flush();
    }
}