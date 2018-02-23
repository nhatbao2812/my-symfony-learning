<?php
/**
 * Created by PhpStorm.
 * User: baotrannhat
 * Date: 2/13/18
 * Time: 10:12 AM
 */
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity
 * @ORM\Table(name="tbljob")
 */
class Job {
    /**
     * @ORM\Column(type="integer", name="jobid")
     * @ORM\Id
     */
    private $jobId;
    /**
     * @ORM\Column(type="integer", name="iscompleted")
     */
    private $isCompleted;

    /**
     * @ORM\Column(type="integer", name="isapproved")
     */
    private $isApproved;

    /**
     * @ORM\Column(type="integer", name="expireddate")
     */
    private $expiredDate;

    /**
     * @ORM\Column(type="integer", name="isactive")
     */
    private $isActive;

    /**
     * @ORM\Column(type="integer", name="isdeleted")
     */
    private $isDeleted;

    /**
     * @Groups({"searchable"})
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * @param mixed $jobId
     */
    public function setJobId($jobId)
    {
        $this->jobId = $jobId;
    }

    /**
     * @return mixed
     */
    public function getisCompleted()
    {
        return $this->isCompleted;
    }

    /**
     * @param mixed $isCompleted
     */
    public function setIsCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;
    }

    /**
     * @return mixed
     */
    public function getisApproved()
    {
        return $this->isApproved;
    }

    /**
     * @param mixed $isApproved
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;
    }

    /**
     * @return mixed
     */
    public function getExpiredDate()
    {
        return $this->expiredDate;
    }

    /**
     * @param mixed $expiredDate
     */
    public function setExpiredDate($expiredDate)
    {
        $this->expiredDate = $expiredDate;
    }

    /**
     * @return mixed
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
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

}