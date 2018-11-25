<?php
use Phalcon\Paginator\Adapter\Model as PaginatorModel;


class TestController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
    	$currentPage = (int) $_GET["page"];
		// Набор данных для разбивки на страницы
		


    }

}

