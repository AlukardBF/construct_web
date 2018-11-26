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
        $subsection->description = $this->request->getPost("description");
        $subsection->section = $this->request->getPost("section");
        $course_id = $this->request->getPost("course_id");
        $subsection->course_course_id = $course_id;

        if (!$subsection->save()) {
            foreach ($group->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'edit',
                'params' => ['course_id'=>$course_id,'tab_id'=>$this->request->getPost("tab_id")]
            ]);

            return;
        }

        $this->flash->success("Глава успешно создана");

        $this->dispatcher->forward([
            'controller' => "course",
            'action' => 'edit',
            'params' => ['course_id'=>$course_id,'tab_id'=>$this->request->getPost("tab_id")]
        ]);
    }

    public function deleteAction($subsection_id, $tab_id = null)
    {
        $subsection = Subsection::findFirst($subsection_id);
        $course_id = $subsection->course_course_id;
        if (!$subsection) {
            $this->flash->error("Глава не найдена " . $subsection_id);

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'edit',
                'params' => ['course_id'=>$course_id,'tab_id'=>$tab_id]
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
                'params' => ['course_id'=>$course_id,'tab_id'=>$tab_id]
            ]);

            return;
        }

        $this->flash->success("Глава успешно удалена");

        $this->dispatcher->forward([
            'controller' => "course",
            'action' => "edit",
            'params' => ['course_id'=>$course_id,'tab_id'=>$tab_id]
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
            $this->flash->error("Глава не существует " . $group_id);

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'list'
            ]);

            return;
        }
        $course_id = $this->request->getPost("course_id");
        $subsection->theme = $this->request->getPost("theme");
        $subsection->description = $this->request->getPost("description");
        

        if (!$subsection->save()) {
            $this->flash->error('Произошла ошибка');
            foreach ($group->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'edit',
                'params' => ['course_id'=>$course_id,'tab_id'=>$this->request->getPost("tab_id")]
            ]);

            return;
        }

        $this->flash->success("Глава успешно обновлена");

        $this->dispatcher->forward([
            'controller' => "course",
            'action' => 'edit',
            'params' => ['course_id'=>$course_id,'tab_id'=>$this->request->getPost("tab_id")]
        ]);
    }
    public function gradeAction($course_id, $user_id, $tab_id, $subsection_id, $grade) {
        $subsection = Subsection::findFirst($subsection_id);
        switch ($grade) {
            case '2':
                $subsection->grade = 'ужасно';
                break;
            case '3':
                $subsection->grade = 'все плохо';
                break;
            case '4':
                $subsection->grade = 'уже лучше';
                break;
            case '5':
                $subsection->grade = 'как надо';
                break;
        }
        $success = $subsection->save();
        $this->dispatcher->forward(
            [
                'controller' => 'course',
                'action' => 'show',
                'params' => [
                    'course_id'=>$course_id,
                    'user_id'=>$user_id,
                    'tab_id' => $tab_id,
                ],
            ]
        );
    }
}
