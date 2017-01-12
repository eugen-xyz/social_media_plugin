<?php

class Instagram_plug extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->helper('url');
		$this->load->library('instagram', array(
				'redirect_uri' => 'http://localhost:8081/i4rnd/social_media_plugin/',
				'client_id' => '9b95468d97a347049e8cbc36504f0446',
				'client_secret' => '41950be9b59f46039049e29b5eaf57d2',
			));
	}

	
	public function index(){

		// die($this->instagram->authenticate());

		
		$data['login'] = $this->instagram->authenticate();

		// if(! empty($this->instagram->access_token)){

			$data['user_id'] = $this->instagram->get_user();
		// }

		$data['logout'] = "index.php/instagram_plug/logout";

		$this->load->view('insta', $data);

	}


	public function logout(){


		$this->instagram->logout();
	}


}