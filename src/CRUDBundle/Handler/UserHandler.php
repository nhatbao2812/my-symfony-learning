<?php
/**
 * Created by PhpStorm.
 * User: baotrannhat
 * Date: 2/23/18
 * Time: 1:45 PM
 */

namespace CRUDBundle\Handler;


use CRUDBundle\Repository\UserRepository;

class UserHandler
{
    private $repo;
    public function __construct(UserRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * @return UserRepository
     */
    public function getRepo()
    {
        return $this->repo;
    }

    public function getUserById($userId)
    {
       return $this->repo->getById($userId);
    }
}