<?php
require_once 'core/controller.php';

class indexController extends Controller{
	public function indexAction(){
		$this->render('index/index');
	}
}
