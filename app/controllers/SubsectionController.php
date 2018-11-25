<?php

class SubsectionController extends ControllerBase
{

    public function createAction($course_id = null) 
    { 
    	if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'new'
            ]);

            return;
        }

        $subsection = new subsection();
        $subsection->theme = $this->request->getPost("theme");
        $subsection->discription = $this->request->getPost("discription");
        $subsection->section = $this->request->getPost("section");
        $course_id = $this->request->getPost("course_course_id");
        $subsection->course_course_id = $course_id;

        if (!$subsection->save()) {
            foreach ($group->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'edit',
                'params' => ['course_id'=>$course_id]
            ]);

            return;
        }

        $this->flash->success("Секция была успешно создана!!!");

        $this->dispatcher->forward([
            'controller' => "course",
            'action' => 'edit',
            'params' => ['course_id'=>$course_id]
        ]);
    }

    public function deleteAction($subsection_id = null)
    {
        $subsection = Subsection::findFirstBysubsection_id($subsection_id);
        $course_id = $subsection->course_course_id;
        if (!$subsection) {
            $this->flash->error("subsection was not found");

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'edit',
                'params' => ['course_id'=>$course_id]
            ]);

            return;
        }

        if (!$subsection->delete()) {

            foreach ($subsection->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'edit',
                'params' => ['course_id'=>$course_id]
            ]);

            return;
        }

        $this->flash->success("subsection was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "course",
            'action' => "edit",
            'params' => ['course_id'=>$course_id]
        ]);
    }

    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->flash->error("Редактируйте через форму");
            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'list'
            ]);

            return;
        }

        $subsection_id = $this->request->getPost("subsection_id");
        $subsection = Subsection::findFirst($subsection_id);

        if (!$subsection) {
            $this->flash->error("Подраздел не существует " . $group_id);

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'list'
            ]);

            return;
        }
        $course_id = $this->request->getPost("course_id");
        $subsection->theme = $this->request->getPost("theme");
        $subsection->discription = $this->request->getPost("discription");
        

        if (!$subsection->save()) {
            $this->flash->error('Произошла ошибка');
            foreach ($group->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'edit',
                'params' => ['course_id'=>$course_id]
            ]);

            return;
        }

        $this->flash->success("group was updated successfully");

        $this->dispatcher->forward([
            'controller' => "course",
            'action' => 'edit',
            'params' => ['course_id'=>$course_id]
        ]);
    }
}
