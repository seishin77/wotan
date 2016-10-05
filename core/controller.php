<?php

class Controller{
	public function render($tpl='', $data=array(), $mtpl='main'){
		if($tpl == '')
			core::render($tpl, $data, 'templates/'. $mtpl . '.php');
		else
			core::render('templates/' . $tpl . '.php', $data, 'templates/'. $mtpl . '.php');
	}

	public function prender($tpl, $data=array()){
		return core::partial_render('templates/' . $tpl . '.php', $data);
	}

	public function error($content){
		$this->render('error', array('content' => $content));
	}

	public function parameters(){
		return core::getRURI();
	}

	public function preAction(){
		return true;
	}

	public function postAction(){
		return true;
	}
}

class adminController extends Controller{
	public function preAction(){
		return users::isAdmin();
	}
}

class moderatorController extends Controller{
	public function preAction(){
		return users::isModerator();
	}
}

class userController extends Controller{
	public function preAction(){
		return users::isUser();
	}
}

class teacherController extends Controller{
	public function preAction(){
		return users::isTeacher();
	}
}

class studentController extends Controller{
	public function preAction(){
		return users::isStudent();
	}
}
