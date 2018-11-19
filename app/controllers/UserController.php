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

    }

    /**
     * Edits a user
     *
     * @param string $user_id
     */
    public function editAction($user_id)
    {
        if (!$this->request->isPost()) {

            $user = User::findFirstByuser_id($user_id);
            if (!$user) {
                $this->flash->error("user was not found");

                $this->dispatcher->forward([
                    'controller' => "user",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->user_id = $user->user_id;

            $this->tag->setDefault("user_id", $user->user_id);
            $this->tag->setDefault("email", $user->email);
            $this->tag->setDefault("pass", $user->pass);
            $this->tag->setDefault("type", $user->type);
            $this->tag->setDefault("name", $user->name);
            $this->tag->setDefault("second_name", $user->second_name);
            $this->tag->setDefault("father_name", $user->father_name);
            $this->tag->setDefault("group_group_id", $user->group_group_id);
            $this->tag->setDefault("title", $user->title);
            
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
        $user->userId = $this->request->getPost("user_id");
        $user->email = $this->request->getPost("email", "email");
        $pass = $this->request->getPost("pass");
        $user->pass = $this->security->hash($pass);
        $user->type = $this->request->getPost("type");
        $user->name = $this->request->getPost("name");
        $user->secondName = $this->request->getPost("second_name");
        $user->fatherName = $this->request->getPost("father_name");
        $user->groupGroupId = $this->request->getPost("group_group_id");
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
        $user = User::findFirstByuser_id($user_id);

        if (!$user) {
            $this->flash->error("user does not exist " . $user_id);

            $this->dispatcher->forward([
                'controller' => "user",
                'action' => 'index'
            ]);

            return;
        }

        $user->userId = $this->request->getPost("user_id");
        $user->email = $this->request->getPost("email", "email");
        $pass = $this->request->getPost("pass");
        $user->pass = $this->security->hash($pass);
        $user->type = $this->request->getPost("type");
        $user->name = $this->request->getPost("name");
        $user->secondName = $this->request->getPost("second_name");
        $user->fatherName = $this->request->getPost("father_name");
        $user->groupGroupId = $this->request->getPost("group_group_id");
        $user->title = $this->request->getPost("title");
        

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
