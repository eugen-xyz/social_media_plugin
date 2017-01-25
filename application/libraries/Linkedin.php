<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Linkedin{

	protected $CI;     

	public $state = "";       
	public $response_type = "code";
	public $company_id = "";
	public $update_key = "";

	protected $code = "";
	protected $access_token = "";
	protected $redirect_uri = "";
	protected $client_id = "";
	protected $client_secret = "";

	private $oauth2_authorization = "https://www.linkedin.com/oauth/v2/authorization";
	private $oauth2_access_token = "https://www.linkedin.com/oauth/v2/accessToken";
	private $linkedIn_profile = "https://api.linkedin.com/v1/people/";
	private $linkedIn_company = "https://api.linkedin.com/v1/companies/";
	private $share_article = "https://www.linkedin.com/shareArticle?mini=true";


	private $_api_urls = array(

			'oauth2_authorization' => "https://www.linkedin.com/oauth/v2/authorization/",
			'oauth2_access_token' => "https://www.linkedin.com/oauth/v2/accessToken",
			'linkedIn_profile' => "https://api.linkedin.com/v1/people/",
		);
	

	public function __construct($params = array()){

        if ( ! empty($params)) $this->initialize($params);

        $this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->CI->load->helper('url');
        $this->base_url = $this->CI->config->base_url();
               
        $this->state = md5(uniqid('I4asia', true)); 

                
    }

    protected function initialize($params){

        foreach ($params as $key => $value) {
            if (isset($this->$key)) $this->$key = $value;
        }

        return $this;
    }

    public function call_api($url, $post_parameters = FALSE)
	{

	    $curl_session = curl_init();

		curl_setopt($curl_session, CURLOPT_URL, $url);

		if($post_parameters !== FALSE) {
			curl_setopt ($curl_session, CURLOPT_POSTFIELDS, $post_parameters);
		}

	    curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl_session, CURLOPT_HTTPHEADER, array(
	    	'Content-Type: application/json', 
	    	'Content-Length: ' . strlen($post_parameters))                                                                       
		);   

	    $contents = curl_exec ($curl_session);

		curl_close ($curl_session);

		return json_decode($contents);
    }


	private function generate_access_token(){

		$url = $this->_api_urls['oauth2_access_token'];
		$fields = array (
		    'client_id' => $this->client_id,
		    'client_secret' => $this->client_secret,
		    'code' => $this->CI->session->userdata("access_code"),
		    'redirect_uri' => $this->redirect_uri,
		    'grant_type' => 'authorization_code',
		);

		$fields_string = "";

		foreach($fields as $key=>$value){

			$fields_string .= $key . '='. $value . '&';
			//$fields_string .= $key.'='.$value.'&';
		}

		// echo $fields_string;

		rtrim($fields_string, '&');

		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL, $url);
		curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$token = curl_exec($curl);
		curl_close($curl);
		// die();

		// die(print_r($fields));

		// $result = $this->call_api($url, $fields_string);


		// die(print_r($fields_string));
		$result = json_decode($token, true);

		if(isset ($result['code']) AND $result['code'] == 400){

			$base = $this->base_url;
	    	redirect($base);
		}

		$this->CI->session->unset_userdata('access_code');
		
		return $result['access_token'];	
		
	}


	public function authenticate(){
    	
     	if($this->CI->input->get('code') AND empty ($this->CI->session->userdata("access_token"))){
    			
    		$this->CI->session->set_userdata("access_code", $this->CI->input->get('code'));
      		$this->CI->session->set_userdata("access_token", $this->generate_access_token());
    	} 
		
	   
    	$login_url = $this->_api_urls['oauth2_authorization'];
    	$login_url .= '?client_id='. $this->client_id;
		$login_url .= '&redirect_uri=' . $this->redirect_uri;
		$login_url .= '&response_type=code';
		$login_url .= '&state=' . $this->state;
	
		return $login_url;
		

    }


    /*
    	destroy session/access token

    */

    public function logout($redirect){

    	$this->CI->session->sess_destroy();
    	$base = $this->base_url . $redirect;
    	redirect($base);

    }



    public function post($params = array()){

    	$url = 'https://api.linkedin.com/v1/companies/'.$this->company_id.'/shares?format=json&oauth2_access_token=';
    	$url .= $this->CI->session->userdata("access_token");

   
    	$data = '{
			    "visibility": { "code": "'.$params["code"].'" },
			    "comment": "'.$params["comment"].'",
			    "content": {
			       "submitted-­url": "'.$params["submitted-­url"].'",
			       "title": "'.$params["title"].'",
			       "description": "'.$params["description"].'",
			       "submitted‐image-­url": "'.$params["submitted‐image-­url"].'"
			    }
			}';

		return $this->call_api($url, $data);

    }





    // okay

	public function get_user_profile(){

		$url = $this->_api_urls['linkedIn_profile'];
		$url .= '~:(id,first-name,maiden-name,last-name,email-address,headline,location,industry,num-connections,summary,specialties,positions,picture-urls::(original),api-standard-profile-request,public-profile-url,site-standard-profile-request)?format=json&oauth2_access_token=';
		$url .= $this->CI->session->userdata("access_token");

		return $this->call_api($url);
	
	}

	// okay

	public function company_sharing_enabled(){
		
		$url = $this->linkedIn_company . $this->company_id.'/is-company-share-enabled?oauth2_access_token=' . $this->CI->session->userdata("access_token").'&format=json';

		return $this->call_api($url);

	}

	// okay

	public function member_is_admin(){
		
		$url = $this->linkedIn_company . $this->company_id.'/relation-to-viewer/is-company-share-enabled?oauth2_access_token=' . $this->CI->session->userdata("access_token").'&format=json';

		return $this->call_api($url);
	}

	// okay

	public function list_all_companies(){
	
		$url = $this->linkedIn_company . '?oauth2_access_token=' . $this->CI->session->userdata("access_token").'&format=json&is-company-admin=true';

		return $this->call_api($url);
	}

	// okay

	public function get_company_info(){
		
		$url =  $this->linkedIn_company . $this->company_id . ':(id,name,ticker,description)?oauth2_access_token=' . $this->CI->session->userdata("access_token").'&format=json';

		return $this->call_api($url);
	
	}

	// okay

	public function company_follower(){

		$url =  $this->linkedIn_company . $this->company_id . '/num-followers?oauth2_access_token=' . $this->CI->session->userdata("access_token").'&format=json&seniorities=vp,d&jobFunc=it&geos=na.ca';

		return $this->call_api($url);

		// https://api.linkedin.com/v1/companies/{id}/num-followers?format=json&seniorities=vp,d&jobFunc=it&geos=na.ca
	}


	// okay

	public function follower_stats(){

		$url =  $this->linkedIn_company . $this->company_id . '/historical-follow-statistics?oauth2_access_token=' . $this->CI->session->userdata("access_token").'&format=json';

		return $this->call_api($url);

		// https://api.linkedin.com/v1/companies/{id}/historical-follow-statistics?format=json
	}

	// okay

	public function company_posts(){


		$url =  $this->linkedIn_company . $this->company_id . '/updates?oauth2_access_token=' . $this->CI->session->userdata("access_token").'&format=json';

		return $this->call_api($url);

	}

	// okay

	public function specific_company_post(){

		$url =  $this->linkedIn_company . $this->company_id . '/updates/key='. $this->update_key .'?oauth2_access_token=' . $this->CI->session->userdata("access_token").'&format=json';

		return $this->call_api($url);

	}

	// okay

	public function comment_for_specific_post(){

		$url =  $this->linkedIn_company . $this->company_id . '/updates/key='. $this->update_key .'/update-comments?oauth2_access_token=' . $this->CI->session->userdata("access_token").'&format=json';

		return $this->call_api($url);

	}


	// okay

	public function like_for_specific_post(){

		$url =  $this->linkedIn_company . $this->company_id . '/updates/key='. $this->update_key .'/likes?oauth2_access_token=' . $this->CI->session->userdata("access_token").'&format=json';

		return $this->call_api($url);

	}



	public function share_article($title, $summary, $url){

		$share = $this->share_article;
		$share .= '&title='.htmlentities($title).'';
		$share .= '&summary='.htmlentities($summary).'';
		$share .= '&url='.$url.'';
		$link = "window.open('$share',
			'Share On LinkedIn', 'width=500, height=600'); return false;";
		
		$data = array(
				'href' => $share,
				'onclick' => $link,
			);

		return $data;

		/*
		
		<a href="<?php echo $share['href'] ?>" onclick="<?php echo $share['onclick'] ?>" >share to linkedin</a>

		*/

	}


	public function anchor_share($title, $summary, $url, $image = null){

		$share = $this->share_article;
		$share .= '&title='.htmlentities($title).'';
		$share .= '&summary='.htmlentities($summary).'';
		$share .= '&url='.$url.'';

		if(!$image){
			$image = base_url('uploads/linkedin.png');
		}


		return '<a href="'.$share.'" onclick="window.open(\''.$share.'\', \'Share On LinkedIn\', \'width=500, height=600\'); return false;">
		 		<img src="'. $image .'">
		 		</a>';

	}

	
}