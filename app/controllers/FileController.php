<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
	public function uploadAction()
	{
	    // Проверяем что файл загрузился
	    if ($this->request->hasFiles()) 
	    {
	    	$files = $this->request->getUploadedFiles();
		// Выводим имя и размер файла
		foreach ($files as $file) 
		{ 
		// Выводим детали
			echo $file->getName(), " ", $file->getSize(), "\n";
		// Перемещаем в приложение
			$file->moveTo( "files/" . $file->getName() );
		}
		}
	}

}
