<?php

class CourseController extends ControllerBase
{
    public function editAction($course_id = null)
    {
               // Получаем запрошенный курс
        $course = Course::findFirst("course_id = ".intval($course_id)); 
        if($course!=null){
            $this->view->chapters = $course->getSubsection( [
                "section = 'главы'",
            ]);
            $this->view->applications =$course->getSubsection( [
                "section = 'приложения'",
            ]);
            $this->view->charts = $course->getSubsection( [
                "section = 'графические материалы'",
            ]);
        }
        $this->view->course = $course; 
    }

    public function newAction()
    {

    }
    /**
     * Creates a new course
     */
    public function createAction()
    {
        $course = new Course();
        // Сохраняем и проверяем на наличие ошибок
        $success = $course->save(
            $this->request->getPost(),
            [
                "name",
                "description",
            ]
        );
        if ($success) {
            $this->dispatcher->forward(
                [
                    'controller' => 'course',
                    'action' => 'edit',
                    'params' => ['course_id'=>$course->course_id],
                ]
            );
        } else {
            echo "К сожалению, возникли следующие проблемы: ";
            $messages = $course->getMessages();
            foreach ($messages as $message) {
                echo $message->getMessage(), "<br/>";
            }
        }
        //$this->view->disable();
    }

}
