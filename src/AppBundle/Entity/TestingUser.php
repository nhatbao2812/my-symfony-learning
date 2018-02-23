<?php
/**
 * Created by PhpStorm.
 * User: baotrannhat
 * Date: 2/12/18
 * Time: 2:32 PM
 */
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tbltesting_user")
 */
class TestingUser
{
    /**
     * @ORM\Column(type="integer", name="userId")
     * @ORM\Id
     * @ORM\OneToMany(targetEntity="JobAlert", mappedBy="userId")
     */
    private $userId;
    

    public function getUserId()
    {
        return $this->userId;
    }


    public function setUserId($userId)
    {
        $this->userId = $userId;
    }


}
