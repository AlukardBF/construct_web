<?php
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class CourseController extends ControllerBase
{
    public function indexAction($course_id = null)
    {
        // Получаем запрошенный курс
        $course = Course::findFirst($course_id);
        $this->view->course = $course;
        $numberPage = $this->request->getQuery("page", "int");
        //список курсов
        $students = $course->getUser('type!="teach"');

        $paginator = new PaginatorModel(
            [
                "data" => $students,
                "limit" => 3,
                "page" => $numberPage,
            ]
        );
        // Получение результатов работы пагинатора
        $this->view->page = $paginator->getPaginate();

    }
    public function editAction($course_id = null)
    {
        // Получаем запрошенный курс
        $course = Course::findFirst($course_id);
        if ($course != null) {
            $this->view->chapters = $course->getSubsection([
                "section = 'главы'",
            ]);
            $this->view->applications = $course->getSubsection([
                "section = 'приложения'",
            ]);
            $this->view->charts = $course->getSubsection([
                "section = 'графические материалы'",
            ]);
        }
        $this->view->course = $course;
    }

    public function listAction()
    {
        $numberPage = $this->request->getQuery("page", "int");
        //список курсов
        $user = User::findFirst($this->session->auth['id']);
        if ($user != null) {
            $course = $user->getCourse();
            $paginator = new PaginatorModel(
                [
                    "data" => $course,
                    "limit" => 3,
                    "page" => $numberPage,
                ]
            );
            // Получение результатов работы пагинатора
            $this->view->page = $paginator->getPaginate();
        }

    }

    public function newAction()
    {

    }

    public function deleteAction($course_id = null)
    {
        $course = Course::findFirst($course_id);
        if (!$course) {
            $this->flash->error("Course was not found");

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'list',
            ]);

            return;
        }

        if (!$course->delete()) {

            foreach ($course->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'edit',
                'params' => ['course_id' => $course_id],
            ]);

            return;
        }

        $this->flash->success("Course was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "course",
            'action' => "list",
        ]);
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
            $userHasCourse->User = $user;
            $userHasCourse->Course = $course;
            $success = $userHasCourse->save();
            if ($success) {
                $this->dispatcher->forward(
                    [
                        'controller' => 'course',
                        'action' => 'edit',
                        'params' => ['course_id' => $course->course_id],
                    ]
                );
            } else {
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

    public function showAction($course_id = null, $user_id = null)
    {
        // Получаем запрошенный курс
        $course = Course::findFirst($course_id);
        $user = User::findFirst($user_id);
        if ($course != null) {
            $this->view->chapters = $course->getSubsection([
                "section = 'главы'",
            ]);
            $this->view->applications = $course->getSubsection([
                "section = 'приложения'",
            ]);
            $this->view->charts = $course->getSubsection([
                "section = 'графические материалы'",
            ]);
        }

        $this->view->course = $course;
        $this->view->user = $user;
    }

    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->flash->error("Редактируйте через форму");
            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'list',
            ]);

            return;
        }

        $course_id = $this->request->getPost("course_id");
        $course = Course::findFirst($course_id);

        if (!$course) {
            $this->flash->error("Подраздел не существует " . $course_id);

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'list',
            ]);

            return;
        }

        $course->name = $this->request->getPost("name");
        $course->description = $this->request->getPost("description");

        if (!$course->save()) {
            $this->flash->error('Произошла ошибка: ');
            foreach ($group->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "course",
                'action' => 'edit',
                'params' => ['course_id' => $course_id],
            ]);

            return;
        }

        $this->flash->success("Курс успешно обновлен.");

        $this->dispatcher->forward([
            'controller' => "course",
            'action' => 'edit',
            'params' => ['course_id' => $course_id],
        ]);
    }
}
