<?php

class FileController extends \Phalcon\Mvc\Controller
{
    private function get_absolute_path($path) {
        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) {
            if ('.' == $part) continue;
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        return implode(DIRECTORY_SEPARATOR, $absolutes);
    }
    private function saveFile($filename) {
        $file = new File();
        $user_id = $this->session->auth['id'];
        $subsection_id = $this->request->getPost('subsection_id');
        // Сохраняем и проверяем на наличие ошибок
        $success = $file->save(
            [
                'filename' => $filename,
                'datetime' => date("Y-m-d H:i:s"),
                'subsection_subsection_id' => $subsection_id,
                'user_user_id' => $user_id,
            ]
        );
        if (!$success) {
            echo "К сожалению, возникли следующие проблемы: ";
            $messages = $course->getMessages();
            foreach ($messages as $message) {
                echo $message->getMessage(), "<br/>";
            }
        }
    }
    public function uploadAction() {
        $max_size = 20971520; //20 MiB

		$user_id = $this->session->auth['id'];
        $subsection_id = $this->request->getPost('subsection_id');
		$subsection = Subsection::findFirst($subsection_id);
		$course_id = $subsection->getCourse()->course_id;
		$id = date('d_m_Y, H_i_s');
		if (true == $this->request->hasFiles() && $this->request->isPost()) {
			$upload_dir = $this->get_absolute_path(BASE_PATH.'/public/uploads/'.$user_id.'/'.$course_id.'/'.$subsection_id);

			if (!is_dir($upload_dir)) {
				mkdir($upload_dir, 0755, TRUE);
			}
			foreach ($this->request->getUploadedFiles() as $file) {
                if ($file->getSize() > $max_size) {
                    $this->flashSession->error($file->getName().' слишком большой (> 20MiB)');
                    continue;
                }
                $file->moveTo($this->get_absolute_path($upload_dir . '/' . $id.$file->getName()));
                $this->flashSession->success($file->getName().' успешно загружен.');
                $this->saveFile($id.$file->getName());
			}
        } else {
            $this->flash->error('Не было передано файлов для загрузки');
        }
        $this->dispatcher->forward(
            [
                'controller' => 'course',
                'action' => 'show',
                'params' => [
                    'course_id'=>$course_id,
                    'user_id'=>$user_id,
                ],
            ]
        );
    }
    public function downloadAction($user_id, $course_id, $subsection_id, $file_id) {
        $file = File::findFirst($file_id);
        $file_path = $this->get_absolute_path(BASE_PATH.'/public/uploads/'.$user_id.'/'.$course_id.'/'.$subsection_id.'/'.$file->filename);
        header('Content-Length: '.filesize($file_path));
        header('Content-Disposition: attachment; filename="'.$file_id.$file->filename.'"');
        readfile($file_path);
        exit();
    }
}

