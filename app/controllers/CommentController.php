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
        $user_target_id = $this->request->getPost('user_target_id');
        $subsection_id = $this->request->getPost('subsection_id');
        // Сохраняем и проверяем на наличие ошибок
        $success = $comment->save(
            [
                'datetime' => date("Y-m-d H:i:s"),
                'text' => $this->request->getPost('text'),
                'subsection_subsection_id' => $subsection_id,
                'user_user_id' => $user_id,
                'user_target_id' => $user_target_id,
            ]
        );
        if ($success) {
            $this->dispatcher->forward(
                [
                    'controller' => 'course',
                    'action' => 'show',
                    'params' => [
                        'course_id'=> $this->request->getPost('course_id'),
                        'user_id'=> $user_target_id,
                        'tab_id'=> $this->request->getPost('tab_id'),
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

