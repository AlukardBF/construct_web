<?php
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

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

    public function listAction(){
        $numberPage = $this->request->getQuery("page", "int");
        //список курсов
        $user = User::findFirst($this->session->auth[id]);
        if($user!=null){
            $course = $user->getCourse();
            $paginator = new PaginatorModel(
            [ 
                "data" => $course,
                "limit" => 3,
                "page" => $numberPage,
            ]
        );
        // Получение результатов работы ппагинатора 
        $this->view->page = $paginator->getPaginate();
        }

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
        $user_id = $this->session->auth['id'];
        $user = User::findFirst($user_id);
        // Сохраняем и проверяем на наличие ошибок
        $success = $course->save(
            $this->request->getPost(),
            [
                "name",
                "description",
            ]
        );
        if ($success) {
            $userHasCourse = new UserHasCourse();
            $userHasCourse->user = $user;
            $userHasCourse->course = $course;
            $success = $userHasCourse->save();
            if($success){
                $this->dispatcher->forward(
                    [
                        'controller' => 'course',
                        'action' => 'edit',
                        'params' => ['course_id'=>$course->course_id],
                    ]
                );
            }else{
                echo "К сожалению, возникли следующие проблемы: ";
                $messages = $course->getMessages();
                foreach ($messages as $message) {
                    echo $message->getMessage(), "<br/>";
                }
            }
        } else {
            echo "К сожалению, возникли следующие проблемы: ";
            $messages = $course->getMessages();
            foreach ($messages as $message) {
                echo $message->getMessage(), "<br/>";
            }
        }
    }

}
