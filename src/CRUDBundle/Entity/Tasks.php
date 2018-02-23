<?php
/**
 * Created by PhpStorm.
 * User: baotrannhat
 * Date: 2/22/18
 * Time: 2:02 PM
 */
namespace CRUDBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tasks")
 */
class Tasks
{
    /**
     * @ORM\Column(type="integer", name="taskid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $taskId;

    /**
     * @ORM\Column(type="string", name="description", length=100, nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", name="status")
     */
    private $status;

    /**
     * @ORM\Column(type="boolean", name="isdeleted")
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="integer", name="userid")
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userId")
     */
    private $userId;

    /**
    * @ORM\Column(type="datetime", name="created_at")
    */
    private $createdDate;

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     */
    private $updatedDate;




    /**
     * @return mixed
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * @param mixed $taskId
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getisDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param mixed $isDeleted
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param mixed $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return mixed
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * @param mixed $updatedDate
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }



}