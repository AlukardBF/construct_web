<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class UserController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
        $this->view->groups = Group::find();
    }

    /**
     * Searches for user
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'User', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "user_id";

        $user = User::find($parameters);
        if (count($user) == 0) {
            $this->flash->notice("The search did not find any user");

            $this->dispatcher->forward([
                "controller" => "user",
                "action" => "index"
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
    
    
    /**
     * Displays the creation form
     */
    public function newAction()
    {
        $this->view->groups = Group::find();

    }

    /**
     * Edits a user
     *
     * @param string $user_id
     */
    public function editAction($user_id=0)
    {
        if (!$this->request->isPost()) {
            $auth = $this->session->auth;
            if($auth['role']=='admin'){
                $user = User::findFirstByuser_id($user_id);
            }else{
                $user = User::findFirstByuser_id($auth['id']);
            }
            if (!$user) {
                $this->flash->error("user was not found");

                $this->dispatcher->forward([
                    'controller' => "user",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->groups = Group::find();
            $this->view->groupId = $user->group_group_id;

            $this->view->user_id = $user->user_id;
            $this->tag->setDefault("user_id", $user->user_id);
            $this->tag->setDefault("type", $user->type);
            $this->tag->setDefault("group_group_id", $user->group_group_id);
            $this->tag->setDefault("title", $user->title);
            $this->tag->setDefault("email", $user->email);
            if($user->group_group_id!=null)
                $this->tag->setDefault("group_name", $user->group->name);
            $this->tag->setDefault("name", $user->name);
            $this->tag->setDefault("second_name", $user->second_name);
            $this->tag->setDefault("father_name", $user->father_name);
            
        }
    }

    /**
     * Creates a new user
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        $user = new User();
        $user->email = $this->request->getPost("email", "email");
        $pass = $this->request->getPost("pass");
        $user->pass = $this->security->hash($pass);
        $user->type = $this->request->getPost("type");
        $user->name = $this->request->getPost("name");
        $user->second_name = $this->request->getPost("second_name");
        $user->father_name = $this->request->getPost("father_name");
        $group =  $this->request->getPost("group_group_id");
        if($group!=null){
            $user->group_group_id = $group;
        }
        $user->title = $this->request->getPost("title");
        

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("user was created successfully");

        $this->dispatcher->forward([
            'controller' => "user",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a user edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        $user_id = $this->request->getPost("user_id");
        $auth = $this->session->auth;
        if($auth['role']=='admin'){
            $user = User::findFirstByuser_id($user_id);
        }else{
            $user = User::findFirstByuser_id($auth['id']);
        }

        if (!$user) {
            $this->flash->error("user does not exist " . $user_id);

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }
        if($auth['role']=='admin'){           
            $user->type = $this->request->getPost("type"); 
            $user->email = $this->request->getPost("email", "email");
            if($this->request->getPost("group_group_id")!=null){
                $user->group_group_id = $this->request->getPost("group_group_id");
            }else{
                 $user->group_group_id = null;
            }
        }
        //сохранение пароля, для обычного пользователя
        $pass = $this->request->getPost("pass");
        if($pass!=null){
            if($auth['role']!='admin'){
                $old_pass = $this->request->getPost("old_pass");
                $re_pass = $this->request->getPost("re_pass");
                if($re_pass==$pass){
                    if($this->security->checkHash($old_pass,$user->pass)){
                        $user->pass = $this->security->hash($pass);
                    }else{
                        $this->flash->error("Неверный старый пароль!!!");
                        return $this->dispatcher->forward([
                            'controller' => "user",
                            'action' => 'edit'
                        ]);  
                    }
                    
                }else{
                    $this->flash->error("Новый пароль и повторный пароль не совпадают!!!");
                    return $this->dispatcher->forward([
                            'controller' => "user",
                            'action' => 'edit'
                        ]);  
                }
            }else{//сохранение пароля для админестратора
                $user->pass = $this->security->hash($pass);
            }
        }
        $second_name = $this->request->getPost("second_name");
        if($second_name!=null){
            $user->second_name =$second_name; 
        }

        $father_name = $this->request->getPost("father_name");
        if($father_name!=null){
            $user->father_name =$father_name; 
        }

        $name = $this->request->getPost("name");
        if($name!=null){
            $user->name =$name; 
        }

        //здесь не эквивалентность, по тому что, это поле может быть пустым
        //потому должна быть возможность присвоить пустую строку
        $title = $this->request->getPost("title");
        if($title!==null){ 
            $user->title =$title; 
        }

        if (!$user->save()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'edit',
                'params' => [$user->user_id]
            ]);

            return;
        }

        $this->flash->success("user was updated successfully");

        $this->dispatcher->forward([
            'controller' => "user",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a user
     *
     * @param string $user_id
     */
    public function deleteAction($user_id)
    {
        $user = User::findFirstByuser_id($user_id);
        if (!$user) {
            $this->flash->error("user was not found");

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        if (!$user->delete()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("user was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "user",
            'action' => "index"
        ]);
    }

}
