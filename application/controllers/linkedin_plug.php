<?php

class Linkedin_plug extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->helper('url');
		$this->load->library('linkedin', array(
				'redirect_uri' => 'http://172.17.0.3/i4rnd/social_media_plugin/index.php/linkedin_plug',
				'client_id' => '78ep5drwkauhpc',
				'client_secret' => 'qOU6I0UaClSjd4D6',
				'company_id' =>  "13223343", // "2414183", //
				'update_key' => "UPDATE-c2414183-6220077225660674048",
			));


	}


	// public function post(){

	// 	$data = array(
	// 			'code' => 'anyone',
	// 			'comment' => 'I4asia Website', 
	// 			'submitted-­url' => 'http://i4asiacorp.com/',
	// 			'title' => 'i4asiacorp',
	// 			'description' => 'Where creativity meets genius',
	// 			'submitted‐image-­url' => 'http://i4asiacorp.com/assets/images/og/home.jpg',
	// 		);


	// 	$this->linkedin->post($data);
	// }
	


	
	public function index(){


		$data['login'] = $this->linkedin->authenticate();
		$data['logout'] = "linkedin_plug/unset";
		$data['home'] = base_url();

		$data['get_user_profile'] = $this->linkedin->get_user_profile();
		$data['post'] = null;
		$data['share'] = null;

		if($this->input->post('submit')){

			$title = $this->input->post('title');
			$summary = $this->input->post('summary');
			$url = $this->input->post('url');

			$data['share'] = $this->linkedin->share_article($title, $summary, $url);
		}


		if($this->input->post('company')){

			$data = array(
				'code' => 'anyone',
				'comment' => $this->input->post('comment'),
				'submitted-­url' => $this->input->post('submitted_url'),
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'submitted‐image-­url' => $this->input->post('image_url'),
			);

			$this->linkedin->post($data);

			redirect(site_url('linkedin_plug'));
		}


		$data['company_posts'] = $this->linkedin->company_posts();



		$this->load->view('linkedin', $data);
		

	}

	

	public function logout(){


		$this->linkedin->logout('index.php/linkedin_plug');
	}

	public function unset(){


		$this->linkedin->logout('index.php/linkedin_plug');
	}


}