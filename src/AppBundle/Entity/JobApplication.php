<?php
/**
 * Created by PhpStorm.
 * User: baotrannhat
 * Date: 2/12/18
 * Time: 6:04 PM
 */
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tbljob_application")
 */
class JobApplication
{
    /**
     * @ORM\Column(type="integer", name="jobId")
     * @ORM\Id
     */
    private $jobId;

    /**
     * @ORM\Column(type="integer", name="userid")
     * @ORM\Id
     */
    private $userId;

    /**
     * @return mixed
     */
    public function getJobid()
    {
        return $this->jobId;
    }

    /**
     * @param mixed $jobid
     */
    public function setJobid($jobId)
    {
        $this->jobId = $jobId;
    }

}