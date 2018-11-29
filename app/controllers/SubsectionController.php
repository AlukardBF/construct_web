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
        $grade_record = Grade::findFirst("user_user_id = ".$user_id." AND subsection_subsection_id = ".$subsection_id);
        //Если запись не найдена
        if (!$grade_record) {
            $grade_record = new Grade();
            $grade_record->user_user_id = $user_id;
            $grade_record->subsection_subsection_id = $subsection_id;
        }
        switch ($grade) {
            case '2':
                $grade_record->grade = 'ужасно';
                break;
            case '3':
                $grade_record->grade = 'все плохо';
                break;
            case '4':
                $grade_record->grade = 'уже лучше';
                break;
            case '5':
                $grade_record->grade = 'как надо';
                break;
        }
        $success = $grade_record->save();
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
