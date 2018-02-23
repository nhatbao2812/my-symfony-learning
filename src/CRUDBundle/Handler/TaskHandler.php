<?php
/**
 * Created by PhpStorm.
 * User: baotrannhat
 * Date: 2/23/18
 * Time: 1:58 PM
 */

namespace CRUDBundle\Handler;


use CRUDBundle\Entity\Tasks;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TaskHandler
{
    private $repo;
    private $router;
    private $formFactory;

    public function __construct($repository, $router, $formFactory)
    {
        $this->repo = $repository;
        $this->router = $router;
        $this->formFactory = $formFactory;
    }

    public function createNewTask()
    {
        return new Tasks();
    }

    public function getListTasksByUserId ($userId)
    {
        return $this->repo->getListByUserId($userId);
    }

    public function getTaskById ($id)
    {
         return $this->repo->getTaskById($id);
    }

    public function changeTaskStatus($taskId)
    {
        try {
            $task = $this->repo->changeStatus($taskId);
            $error = false;
        } catch (Exception $ex) {
            $task = $this->getTaskById($taskId);
            $error = true;
        }
        return array("data" => $task, "error" => $error);
    }

    public function changeTaskIsDeleted($taskId)
    {
        try {
            $task = $this->repo->changeIsDeleted($taskId);
            $error = false;
        } catch (Exception $ex) {
            $task = $this->getTaskById($taskId);
            $error = true;
        }
        return array("data" => $task, "error" => $error);
    }

    public function createForm($taskId=null)
    {
        $task = !empty($taskId) ? $this->getTaskById($taskId) : $this->createNewTask();
        $label = !empty($taskId) ? "Update Task" : "Create Task";
        $form = $this->formFactory->createBuilder()
            ->setAction($this->router->generate("insert-task"))
            ->setMethod("POST")
            ->add('description', TextType::class, array(
                'data' => !empty($task) ? $task->getDescription() : ""
            ))
            ->add("taskId", HiddenType::class, array('data' =>!empty($task) ? $task->getTaskId() : ""))
            ->add('save', SubmitType::class, array('label' => $label))
            ->getForm();
        return $form;
    }

    public function upSert($taskId, $description)
    {
        if(empty($taskId)) {
            $task = $this->createNewTask();
            $task->setCreatedDate(new \DateTime());
            $task->setUserId(1);
            $task->setStatus(0);
            $task->setIsDeleted(0);
            $message = "Created task";
        } else {
            $task = $this->getTaskById($taskId);
            $message = "Updated task";
        }
        $task->setDescription($description);
        $task->setUpdatedDate(new \DateTime());
        try {
            $this->repo->upSert($task);
            return array("message" => $message, "error" => false, "data" => $task);
        } catch (Exception $ex) {
            return array("message" => $ex->getMessage(), "error" => true,  "data" => $task);
        }
    }
}