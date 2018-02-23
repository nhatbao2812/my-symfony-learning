<?php
/**
 * Created by PhpStorm.
 * User: baotrannhat
 * Date: 2/23/18
 * Time: 11:40 AM
 */

namespace CRUDBundle\Repository;
use CRUDBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository
{
    private $em;
    private $className;
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->className = User::class;
        $this->repo = $this->em->getRepository($this->className);
    }


    public function getById($userId)
    {
        return $this->repo->find($userId);
    }
}