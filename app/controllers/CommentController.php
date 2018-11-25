<?php

class CommentController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }

    public function createAction()
    {
        $comment = new Comment();
        $user_id = $this->session->auth['id'];
        $user = User::findFirst($user_id);
        $subsection_id = $this->request->getPost('subsection_id');
        $subsection = Subsection::findFirst($subsection_id);
        // Сохраняем и проверяем на наличие ошибок
        $success = $comment->save(
            [
                'datetime' => date("Y-m-d H:i:s"),
                'text' => $this->request->getPost('text'),
                'subsection_subsection_id' => $subsection_id,
                'user_user_id' => $user_id,
            ]
        );
        if ($success) {
            $this->dispatcher->forward(
                [
                    'controller' => 'course',
                    'action' => 'show',
                    'params' => [
                        'course_id'=>$subsection->getCourse()->course_id,
                        'user_id'=>$user_id,
                    ],
                ]
            );
        } else {
            echo "К сожалению, возникли следующие проблемы: ";
            $messages = $course->getMessages();
            foreach ($messages as $message) {
                echo $message->getMessage(), "<br/>";
            }
        }
    }
}

