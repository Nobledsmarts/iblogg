<?php namespace App\Controllers;

class Home extends BaseController {
	public function index() {
		$request_uri = $_SERVER['REQUEST_URI'];
		if($request_uri != '/'){
			return redirect()->to('/#' . $request_uri);
		}
		return view('index');
	}
	public function redirect() {
		$request_uri = $_SERVER['REQUEST_URI'];
		return redirect()->to('/#' . $request_uri);
	}
	public function view($dir, $page, $data = []){
		$session = session();
		$is_loggedin = (bool) $session->get('isLoggedIn');
        echo view($dir . '/templates/header', ['is_loggedin' => $is_loggedin]);
        echo view($dir . '/' . $page , $data);
        echo view($dir . '/templates/footer');
	}
	
}
