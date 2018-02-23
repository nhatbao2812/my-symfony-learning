<?php
/**
 * Created by PhpStorm.
 * User: baotrannhat
 * Date: 2/12/18
 * Time: 3:32 PM
 */
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tbljob_alert")
 */
class JobAlert
{
    /**
     * @ORM\Column(type="integer", name="jobalertid")
     * @ORM\Id
     */
    private $jobAlertId;
    /**
     * @ORM\Column(type="integer", name="userid")
     * @ORM\ManyToOne(targetEntity="TestingUser", inversedBy="userId")
     * @ORM\JoinColumn(name="userid", referencedColumnName="userid")
     */
    private $userId;

    /**
     * @ORM\Column(type="datetime", name="sendingdate")
     */
    private $sendingDate;

    /**
     * @ORM\Column(type="integer", name="noofdays")
     */
    private $noOfDays;

    /**
     * @ORM\Column(type="integer", name="joblevelid")
     */
    private $jobLevelId;

    /**
     * @ORM\Column(type="string", name="keyword")
     */
    private $keyword;

    /**
     * @ORM\Column(type="integer", name="industryid")
     */
    private $industryId;

    /**
     * @return mixed
     */
    public function getNoOfDays()
    {
        return $this->noOfDays;
    }

    /**
     * @param mixed $noOfDays
     */
    public function setNoOfDays($noOfDays)
    {
        $this->noOfDays = $noOfDays;
    }

    /**
     * @return mixed
     */
    public function getJobLevelId()
    {
        return $this->jobLevelId;
    }

    /**
     * @param mixed $jobLevelId
     */
    public function setJobLevelId($jobLevelId)
    {
        $this->jobLevelId = $jobLevelId;
    }

    /**
     * @return mixed
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @param mixed $keyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * @return mixed
     */
    public function getIndustryId()
    {
        return $this->industryId;
    }

    /**
     * @param mixed $industryId
     */
    public function setIndustryId($industryId)
    {
        $this->industryId = $industryId;
    }

    /**
     * @ORM\Column(type="integer", name="iscompleted")
     */

    /**
     * @return mixed
     */
    public function getSendingDate()
    {
        return $this->sendingDate;
    }

    /**
     * @param mixed $sendingDate
     */
    public function setSendingDate($sendingDate)
    {
        $this->sendingDate = $sendingDate;
    }
    /**
     * @return mixed
     */
    public function getJobAlertId()
    {
        return $this->jobAlertId;
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
     * @param mixed $jobAlertId
     */
    public function setJobAlertId($jobAlertId)
    {
        $this->jobAlertId = $jobAlertId;
    }

}