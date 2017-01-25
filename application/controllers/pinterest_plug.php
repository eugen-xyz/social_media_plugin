<?php 


class Pinterest_plug extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->helper('url');
		$this->load->library('pinterest', array(
				'redirect_uri' => 'https://172.17.0.3/i4rnd/social_media_plugin/index.php/pinterest_plug',
				'client_id' => '4880409814934499493',
				'client_secret' => '15cc5e40721b7c7489266078c422e7eed4c412eb72c3fb3a84bd4e9253c124de',
			));


		
	}


	public function index(){


		$data['login'] = $this->pinterest->authenticate();
		$data['logout'] = "pinterest/logout";
		$data['home'] = base_url();


		$data['get_user_profile'] = $this->pinterest->get_user_profile();
		// $data['post'] = $this->post();

	

		// die(print_r($data['share']));

		$this->load->view('pinterest', $data);
		

	}

	public function logout(){


		$this->linkedin->logout('index.php/pinterest_plug');
	}
}