<?php

class CourseController extends ControllerBase 
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
				$this->dispatcher->forward(
            [
                'controller' => 'subsection',
                'action'     => 'index',
            ]
        	); 
			} else 
			{ 
				echo "К сожалению, возникли следующие проблемы: ";
				$messages = $course->getMessages();
				foreach ($messages as $message) 
					{
					echo $message->getMessage(), "<br/>"; 
				}
			}
		//$this->view->disable();
	}
}