<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class GroupController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for group
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Group', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "group_id";

        $group = Group::find($parameters);
        if (count($group) == 0) {
            $this->flash->notice("The search did not find any group");

            $this->dispatcher->forward([
                "controller" => "group",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $group,
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
     * Edits a group
     *
     * @param string $group_id
     */
    public function editAction($group_id)
    {
        if (!$this->request->isPost()) {

            $group = Group::findFirstBygroup_id($group_id);
            if (!$group) {
                $this->flash->error("group was not found");

                $this->dispatcher->forward([
                    'controller' => "group",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->group_id = $group->group_id;

            $this->tag->setDefault("group_id", $group->group_id);
            $this->tag->setDefault("name", $group->name);
            $this->tag->setDefault("year", $group->year);
            $this->tag->setDefault("type", $group->type);
            
        }
    }

    /**
     * Creates a new group
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "group",
                'action' => 'index'
            ]);

            return;
        }

        $group = new Group();
        $group->name = $this->request->getPost("name");
        $group->year = $this->request->getPost("year");
        $group->type = $this->request->getPost("type");
        

        if (!$group->save()) {
            foreach ($group->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "group",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("group was created successfully");

        $this->dispatcher->forward([
            'controller' => "group",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a group edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "group",
                'action' => 'index'
            ]);

            return;
        }

        $group_id = $this->request->getPost("group_id");
        $group = Group::findFirstBygroup_id($group_id);

        if (!$group) {
            $this->flash->error("group does not exist " . $group_id);

            $this->dispatcher->forward([
                'controller' => "group",
                'action' => 'index'
            ]);

            return;
        }

        $group->name = $this->request->getPost("name");
        $group->year = $this->request->getPost("year");
        $group->type = $this->request->getPost("type");
        

        if (!$group->save()) {

            foreach ($group->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "group",
                'action' => 'edit',
                'params' => [$group->group_id]
            ]);

            return;
        }

        $this->flash->success("group was updated successfully");

        $this->dispatcher->forward([
            'controller' => "group",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a group
     *
     * @param string $group_id
     */
    public function deleteAction($group_id)
    {
        $group = Group::findFirstBygroup_id($group_id);
        if (!$group) {
            $this->flash->error("group was not found");

            $this->dispatcher->forward([
                'controller' => "group",
                'action' => 'index'
            ]);

            return;
        }

        if (!$group->delete()) {

            foreach ($group->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "group",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("group was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "group",
            'action' => "index"
        ]);
    }

}
