<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class StudentController extends ControllerBase
{
    public function indexAction($course_id = null) 
    {  
        $this->persistent->parameters = null;
        $this->view->course_id = $course_id;
        $this->view->groups = Group::find();

    }

    public function searchAction($course_id = null)
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'User', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $this->view->course_id = $course_id;
        $course=Course::findFirst($course_id);
        //id подписанных ребят
        $subIds=[];
        foreach ($course->user as $user) {
           $subIds[] = $user->user_id;
        }
        $this->view->course_id = $course_id;
        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
            $parameters['bind']=[];
        }
        if(isset($parameters['conditions']))
            $parameters['conditions'] .= " AND type = 'student'";
        else
            $parameters['conditions'] = "type = 'student'";
        if(count($subIds)>0){
            $parameters['conditions'].=' AND user_id NOT IN ({ids:array})';
            $parameters["bind"]=array_merge($parameters["bind"],["ids"=>$subIds]);
        }
        //print_r($parameters);        
        //$parameters["order"] = "user_id";
        $user = User::find($parameters);
        if (count($user) == 0) {
            $this->flash->notice("The search did not find any user");

            $this->dispatcher->forward([
                "controller" => "course",
                "action" => "list"
            ]);

            return;
        }
        $paginator = new Paginator([
            'data' => $user,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }
    //ууу, какой большой контроллер, с повторяющимся кодом.
    //он подписывае всех, кто был найден по поиску.
    public function subscribeallAction($course_id = null){
        $parameters = $this->persistent->parameters;
        if($parameters==null){
            $this->dispatcher->forward([
                "controller" => "course",
                "action" => "list",
                "error" => "Пожалуйста, не хакайте нас ;)"
            ]);
        }
        //ищем по тем же параметрам, что и при поиске
        $this->view->course_id = $course_id;
        $course=Course::findFirst($course_id);
        //id подписанных ребят
        $subIds=[];
        foreach ($course->user as $user) {
           $subIds[] = $user->user_id;
        }
        $this->view->course_id = $course_id;
        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
            $parameters['bind']=[];
        }
        if(isset($parameters['conditions']))
            $parameters['conditions'] .= " AND type = 'student'";
        else
            $parameters['conditions'] = "type = 'student'";
        if(count($subIds)>0){
            $parameters['conditions'].=' AND user_id NOT IN ({ids:array})';
            $parameters["bind"]=array_merge($parameters["bind"],["ids"=>$subIds]);
        }
        $user = User::find($parameters);
        if (count($user) > 40) {
            $this->flash->error("Нельзя подписывать более 40 человек за раз.");

            $this->dispatcher->forward([
                "controller" => "course",
                "action" => "list"
            ]);
            return;
        }
        //подписываем найденых
        foreach ($user as $currentUser) {
            $userHasCourse = new UserHasCourse();
            $userHasCourse->user = $currentUser;
            $userHasCourse->course = $course;
            $success = $userHasCourse->create();
            if(!$success){
                echo "К сожалению, возникли следующие проблемы: ";
                $messages = $userHasCourse->getMessages();
                if($messages!=null){
                    foreach ($messages as $message) {
                        echo $message->getMessage(), "<br/>";
                    }
                }
                $this->dispatcher->forward([
                    "controller" => "course",
                    "action" => "list"
                ]);
                return;
            }
            $this->dispatcher->forward([
                "controller" => "course",
                "action" => "list"
            ]);
            return;
        }
    }
    public function subscribeAction($course_id, $user_id)
    {
        $user = User::findFirst($user_id);
        $course = Course::findFirst($course_id);
        if($user ==null || $course==null){
            $this->dispatcher->forward([
                'controller' => "student",
                'action' => 'search',
                'params' => ['course_id'=>$course_id]
            ]);
        }
        $userHasCourse = new UserHasCourse();
        $userHasCourse->User = $user;
        $userHasCourse->Course = $course;
        $success = $userHasCourse->create();
        if($success){
            echo $this->flash->success("Успех!!!");
            $this->dispatcher->forward([
                'controller' => "student",
                'action' => 'search',
                'params' => ['course_id'=>$course_id]
            ]);
            return;
        }else{
            echo "К сожалению, возникли следующие проблемы: ";
            $messages = $course->getMessages();
            if($messages!=null){
                foreach ($messages as $message) {
                    echo $message->getMessage(), "<br/>";
                }
            }
        }
    }
    public function unsubscribeAction($course_id, $user_id)
    {
        $user = User::findFirst($user_id);
        $course = Course::findFirst($course_id);
        if($user ==null || $course==null){
            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'index',
                'params' => ['course_id'=>$course_id]
            ]);
        }
        $userHasCourse = UserHasCourse::findFirst("user_user_id = ".$user_id." AND course_course_id = ".$course_id);
        if (!$userHasCourse) {
            $this->flash->error("Связь курса и студента не найдена!");

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'index',
                'params' => ['course_id'=>$course_id]
            ]);
            return;
        }
        if (!$userHasCourse->delete()) {
            foreach ($userHasCourse->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'index',
                'params' => ['course_id'=>$course_id]
            ]);

            return;
        }

        $this->flash->success("Студент успешно отписан");

        $this->dispatcher->forward([
            'controller' => "course",
            'action' => 'index',
            'params' => ['course_id'=>$course_id]
        ]);
    }
}

