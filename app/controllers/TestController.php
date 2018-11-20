<?php
use Phalcon\Paginator\Adapter\Model as PaginatorModel;


class TestController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
    	$currentPage = 1;
		// Набор данных для разбивки на страницы
		$course = Course::find();
		// Создаём пагинатор, отображаются 4 элемента на странице, начиная с текущей - $currentPage
		$paginator = new PaginatorModel(
			[ 
				"data" => $course,
				"limit" => 4,
				"page" => $currentPage,
			]
		);
		// Получение результатов работы ппагинатора 
		$page = $paginator->getPaginate();

    }

}

