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
}
