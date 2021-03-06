<?php 

namespace Phalcon\Mvc\Micro {

	class Collection implements \Phalcon\Mvc\Micro\CollectionInterface {

		protected $_prefix;

		protected $_lazy;

		protected $_handler;

		protected $_handlers;

		protected function _addMap($method, $routePattern, $handler, $name){ }


		public function setPrefix($prefix){ }


		public function getPrefix(){ }


		public function getHandlers(){ }


		public function setHandler($handler, $lazy=null){ }


		public function setLazy($lazy){ }


		public function isLazy(){ }


		public function getHandler(){ }


		public function map($routePattern, $handler, $name=null){ }


		public function mapVia($routePattern, $handler, $method, $name=null){ }


		public function get($routePattern, $handler, $name=null){ }


		public function post($routePattern, $handler, $name=null){ }


		public function put($routePattern, $handler, $name=null){ }


		public function patch($routePattern, $handler, $name=null){ }


		public function head($routePattern, $handler, $name=null){ }


		public function delete($routePattern, $handler, $name=null){ }


		public function options($routePattern, $handler, $name=null){ }

	}
}
