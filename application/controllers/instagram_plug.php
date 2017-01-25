<?php

class Instagram_plug extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->helper('url');
		$this->load->library('instagram', array(
				'redirect_uri' => 'http://172.17.0.3/i4rnd/social_media_plugin/index.php/instagram_plug',
				'client_id' => '9b95468d97a347049e8cbc36504f0446',
				'client_secret' => '41950be9b59f46039049e29b5eaf57d2',
			));

	}

	
	public function index(){

		// die($this->instagram->authenticate());

		
		$data['login'] = $this->instagram->authenticate();

		// if(! empty($this->instagram->access_token)){

			// $data['user_id'] = $this->instagram->user_follows('3970210560');
		$data['user_id'] = $this->instagram->get_self();
		// }

		// echo $this->session->userdata('access_token');


		// echo print_r($data['user_id']);

		$data['logout'] = "index.php/instagram_plug/logout";

		$this->load->view('insta', $data);

	}


	public function logout(){


		$this->instagram->logout();
	}


}