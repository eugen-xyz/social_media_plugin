<?php

class Linkedin extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->helper('url');
		$this->load->library('curl');
	}

	public function index(){

		echo "Instructions";

		/*

			0. Follow the instruction here:
				https://developer.linkedin.com/docs/oauth2
			1. Create and configure a new applicaiton
					https://www.linkedin.com/developer/apps
			2. Client ID and Client Secret will be provided by linked in.
			3. Add OAuth 2.0 Authorized Redirect URLs:

			________

			Authorization:

			1. To request an authorization
				1.1 Redirect the user's browser to LinkedIn's OAuth 2.0 authorization endpoint.

				https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=<client_id>&redirect_uri=<redirect_url>&state=<state>&scope=r_basicprofile

				response_type

								The value of this field should always be:  code	Yes
								client_id	The "API Key" value generated when you registered your application.

								Yes
				redirect_uri

								The URI your users will be sent back to after authorization.  This value must match one of the defined OAuth 2.0 Redirect URLs in your application configuration.


								e.g. https://www.example.com/auth/linkedin
								Yes
				state

								A unique string value of your choice that is hard to guess. Used to prevent CSRF.


								e.g. state=DCEeFWf45A53sdfKef424
								Yes
				scope

								A URL-encoded, space delimited list of member permissions your application is requesting on behalf of the user.  If you do not specify a scope in your call, we will fall back to using the default member permissions you defined in your application configuration.


								e.g. scope=r_fullprofile%20r_emailaddress%20w_share

								Optional


				This is our authorization url

					https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=78ep5drwkauhpc&redirect_uri=http://localhost/~egrava/study_ci2/social_plugin/index.php/linkedin/signin&state=i4asiacorp

			2. You will be redirected back to your site. with a code and state.

				code=AQQotEI7HKMf3_Ag5n8Yh8ROvyYcAkFy1b7Fi5tBmS0wK8aXKH0FPnU-o8eJx_2iETM00qpBQvdJyRhBsEFpW4CoBRL4Pou4McszvId9x8nmxHmNcQ4&state=i4asiacorp


				// curl -X POST -d "client_id=78ep5drwkauhpc&client_secret=qOU6I0UaClSjd4D6&grant_type=authorization_code&redirect_uri=http://localhost/~egrava/study_ci2/social_plugin/index.php/linkedin/signin&code=AQSrdGqzst1fSqJ_CWjPAsNvAb5FWIgLeJQXZrME9GfOU6mpDymsx1bMIValwWFJMu-gq0i1xotjCWg4m_7im-intPHSBUG-FED9PEzJice-2HDa8mU" https://www.linkedin.com/oauth/v2/accessToken


		*/
	}

	public function signin(){

		$code = $this->input->get('code');
		$linkedin = "https://www.linkedin.com/oauth/v2/authorization";
		$response_type = "code";
		$client_id = "78ep5drwkauhpc";
		$redirect_uri = "http://localhost/~egrava/study_ci2/social_plugin/index.php/linkedin/signin";
		$state = "i4asiacorp";
		$profile = "";
		$obj = "";
		if(!empty($code)){

			$token = $this->_access_token($code);
			$obj = json_decode($token);

			$profile = $this->_get_profile($obj->access_token);
		}

		$data = array(
			'auth' => $linkedin . '?response_type=' .$response_type .'&client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&state=' . $state,
			'profile' => $profile,
			'token' => $obj,

			);

		$this->load->view('login', $data);
	}

	public function _access_token($code){


		$url = "https://www.linkedin.com/oauth/v2/accessToken";
		$post_data = array (
		    "client_id" => "78ep5drwkauhpc",
		    "client_secret" => "qOU6I0UaClSjd4D6",
		    "grant_type" => "authorization_code",
		    'redirect_uri' => 'http://localhost/~egrava/study_ci2/social_plugin/index.php/linkedin/signin',
		    'code' => $code,
		);

		return $output = $this->curl->simple_post($url, $post_data);

	}

	public function _get_profile($obj){

		$get_url = "https://api.linkedin.com/v1/people/~:(id,first-name,last-name,headline,picture-url,industry,summary,specialties,email_address,positions:(id,title,summary,start-date,end-date,is-current,company:(id,name,type,size,industry,ticker)),educations:(id,school-name,field-of-study,start-date,end-date,degree,activities,notes),associations,interests,num-recommenders,date-of-birth,publications:(id,title,publisher:(name),authors:(id,name),date,url,summary),patents:(id,title,summary,number,status:(id,name),office:(name),inventors:(id,name),date,url),languages:(id,language:(name),proficiency:(level,name)),skills:(id,skill:(name)),certifications:(id,name,authority:(name),number,start-date,end-date),courses:(id,name,number),recommendations-received:(id,recommendation-type,recommendation-text,recommender),honors-awards,three-current-positions,three-past-positions,volunteer)?format=json&oauth2_access_token=$obj";

   		return $this->curl->simple_get($get_url, false, array(CURLOPT_USERAGENT => true));
	}

	public function share(){

		$url = "https://www.linkedin.com/shareArticle";
		$post_data = array (
					'mini'	=> 'true',
					'title' => 'I4asia',
					'summary' => 'Where creativity meets genius.',
					'source' => 'I4asiacorp',
				);

		

		// die(var_dump($post));

		return $output = $this->curl->simple_get($url, $post_data);
	}
}