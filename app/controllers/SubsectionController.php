<?php

class SubsectionController extends ControllerBase
{

    public function indexAction($course_id = null) 
    { 
        // Получаем запрошенный курс
        $course = Course::findFirst("course_id = ".intval($course_id)); 
        $this->view->course = $course; 
    } 
}
