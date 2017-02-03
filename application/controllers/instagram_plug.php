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

		$data['login'] = $this->instagram->authenticate();
		$data['logout'] = "instagram_plug/unset";
		$data['home'] = base_url();

		
		
		$data['follows'] = $this->instagram->user_follows('4155759310'); //3970210560
		//1947684413 lim sungjin
		$data['tags_search'] = $this->instagram->tags_search('seventeencarat');

		$data['tags'] = $this->instagram->tags('seventeencarat');


		$this->load->view('instagram/header');
		$this->load->view('instagram/instagram', $data);
		$this->load->view('instagram/footer');

	}


	public function get_user(){

		$data['user_id'] = $this->instagram->get_self();

		echo $this->load->view('instagram/get_user', $data, true);
	}

	public function recent_post(){

		$data['media'] = $this->instagram->self_media_recent();

		echo $this->load->view('instagram/recent_post', $data, true);
	}

	public function logout(){

		$this->instagram->logout('index.php/instagram_plug');
	}

	public function unset(){

		$this->instagram->logout('index.php/instagram_plug');
	}


}