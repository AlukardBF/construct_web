<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class StudentController extends ControllerBase
{
    public function indexAction($course_id = null) 
    {  
        $this->persistent->parameters = null;
        $this->view->course_id = $course_id;

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
        print_r($parameters);

        
        
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
}

