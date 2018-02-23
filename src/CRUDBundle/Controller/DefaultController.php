<?php

namespace CRUDBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DefaultController extends Controller
{
    private $userHandler;
    private $taskHandler;
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->userHandler = $container->get("crud.user.handler");
        $this->taskHandler = $container->get("crud.task.handler");
    }

    /**
     * @Route("/CRUD", name="CRUDIndex")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            echo "NO login";die;
        }

//        if (!is_object($user = $token->getUser())) {
//            // chua dang nhap
//            // TODO
//            // da ve trang dang nhap
//            echo "Anonymus user";die;
//        }

        // Da dang nhap
        // TODO
        // da ve trang get list tasks
         return $this->redirectToRoute("list-tasks",["userId"=>1]);
//        return $user;
//        return $this->render('@CRUD/Default/index.html.twig');
    }

    /**
     * @Route("/tasks/{userId}", name="list-tasks")
     * @Method({"GET"})
     */
    public function listTasks($userId)
    {
        $listTasks = $this->taskHandler->getListTasksByUserId($userId);
        $messages = [];
        foreach ($this->session->getFlashBag()->get('notice', array()) as $message) {
           array_push($messages,'<div class="flash-warning">'.$message.'</div>') ;
        }
        return $this->render('@CRUD/Default/listtasks.html.twig',[
            "tasks"=>$listTasks,
            "messages" => $messages
        ]);
    }

    /**
     * @Route("/create-task/{userId}", name="create-task")
     * @Method({"GET"})
     */
    public function createTask ($userId,Request $request)
    {
        $error = $request->get("error");
        //get userName
        $user = $this->userHandler->getUserById($userId);
        $username = $user->getUserName();

        $taskId = $request -> get("taskId");
        //create form
        $form = $this->taskHandler->createForm($taskId);

        return $this->render('@CRUD/Default/createtask.html.twig',[
            "userId" => $userId,
            "userName" => $username,
            'form' => $form->createView(),
            "error" => $error
            ]);
    }

    /**
     * @Route("/insert-task", name="insert-task")
     * @Method({"POST"})
     */
    public function insertTask( Request $request)
    {
        $form = $this->taskHandler->createForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $formData = $form->getData();
            $description = $formData['description'];
            $taskId = $formData["taskId"];
            if(empty($description)){
                $form->addError(new FormError("Description is not null"));
                $errors = $form->getErrors();
                return $this->redirectToRoute("create-task",[
                    "userId" => 1,
                    "error" => $errors[0]->getMessage()
                ]);
            }
             $resp = $this->taskHandler->upSert($taskId,$description);
             $type = $resp['error'] ? "error" : "notice";
             $this->setFlashMessage($type, $resp['message']);
             return $this->redirectToRoute('list-tasks',[
                 "userId" => $resp['data']->getUserId(),
             ]);

        }
    }

    /**
     * @Route("/change-status/{taskId}", name="change-status")
     * @Method({"GET"})
     */
    public function changeStatus($taskId)
    {
        $resp = $this->taskHandler->changeTaskStatus($taskId);
        if($resp['error'])
        {
            $message = "Can not change status";
            $this->setFlashMessage('error',$message);
        }
        return $this->redirectToRoute('list-tasks',[
            "userId" => $resp['data']->getUserId()
        ]);
    }

    /**
     * @Route("/delete/{taskId}", name="delete")
     * @Method({"GET"})
     */
    public function delete($taskId)
    {
        $resp = $this->taskHandler->changeTaskIsDeleted($taskId);
        if($resp['error'])
        {
            $message = "Can not delete task";
            $this->setFlashMessage('error',$message);
        }
        return $this->redirectToRoute('list-tasks',[
            "userId" => $resp['data']->getUserId()
        ]);
    }

    private function setFlashMessage($type, $message)
    {
        $this->session->getFlashBag()->add($type, $message);
    }
}
