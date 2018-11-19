<?php
use Phalcon\Mvc\Controller;
class CourseController extends Controller 
{ 
	public function indexAction()
    {
        
    }
	 /**
     * Creates a new course
     */
    public function registerAction() { 
    	$course = new Course();
		// Сохраняем и проверяем на наличие ошибок
		$success = $course->save(
			$this->request->getPost(),
			[ 
				"name",
				"description",
			] 
		);
		if ($success) 
			{ 
				echo "Спасибо за регистрацию!"; 
			} else 
			{ 
				echo "К сожалению, возникли следующие проблемы: ";
				$messages = $course->getMessages();
				foreach ($messages as $message) 
					{
					echo $message->getMessage(), "<br/>"; 
				}
			}
		$this->view->disable();
	}
}