<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instagram{

	protected $CI;     

	public $access_token = "";
	public $base_url = "";
	public $authenticate = "";

	public $scope = 'basic+public_content+likes+relationships+follower_list+comments';

	public $client_id = "";
	public $client_secret = "";

	public $state = "";
	public $user_id = "";

	public $redirect_uri = "";

	protected $_api_urls = array(
		'access_token'				=> 'https://api.instagram.com/oauth/access_token',

		'self'						=> 'https://api.instagram.com/v1/users/self/?access_token=%s',
		'self_media_recent' 		=> 'https://api.instagram.com/v1/users/self/media/recent/?access_token=%s',
		'user'					    => 'https://api.instagram.com/v1/users/%s/?access_token=%s',
		'user_media_recent'			=> 'https://api.instagram.com/v1/users/%s/media/recent/?access_token=%s',
		'user_search'               => 'https://api.instagram.com/v1/users/search?q=%s&access_token=%s',
		'self_liked'				=> 'https://api.instagram.com/v1/users/self/media/liked?access_token=%s&scope=%s',
		'user_follows'				=> 'https://api.instagram.com/v1/users/%s/follows?access_token=%s',
		



		'user_feed'                 => 'https://api.instagram.com/v1/users/self/feed?access_token=%s&count=%s&min_id=%s&man_id=%s',
		'user_recent'               => 'https://api.instagram.com/v1/users/%s/media/recent/?access_token=%s&count=%s&max_id=%s&min_id=%s&max_timestamp=%s&min_timestamp=%s',
		
		
		'user_followed_by'          => 'https://api.instagram.com/v1/users/%s/followed-by?access_token=%s&scope=%s',
		'user_requested_by'         => 'https://api.instagram.com/v1/users/self/requested-by?access_token=%s',
		'user_relationship'         => 'https://api.instagram.com/v1/users/%s/relationship?access_token=%s',
		'modify_user_relationship'  => 'https://api.instagram.com/v1/users/%s/relationship?access_token=%s',
		'media'                     => 'https://api.instagram.com/v1/media/%s?access_token=%s',
		'media_search'              => 'https://api.instagram.com/v1/media/search?lat=%s&lng=%s&max_timestamp=%s&min_timestamp=%s&distance=%s&access_token=%s',
		'media_popular'             => 'https://api.instagram.com/v1/media/popular?access_token=%s',
		'media_comments'            => 'https://api.instagram.com/v1/media/%s/comments?access_token=%s',
		'post_media_comment'        => 'https://api.instagram.com/v1/media/%s/comments?access_token=%s',
		'delete_media_comment'      => 'https://api.instagram.com/v1/media/%s/comments?comment_id=%s&access_token=%s',
		'likes'                     => 'https://api.instagram.com/v1/media/%s/likes?access_token=%s',
		'login_url'					=> 'https://api.instagram.com/oauth/authorize/?client_id=',
		'post_like'                 => 'https://api.instagram.com/v1/media/%s/likes?access_token=%s',
		'remove_like'               => 'https://api.instagram.com/v1/media/%s/likes?access_token=%s',
		
		'tags'                      => 'https://api.instagram.com/v1/tags/%s?access_token=%s',
		'tags_recent'               => 'https://api.instagram.com/v1/tags/%s/media/recent?max_id=%s&min_id=%s&access_token=%s',
		'tags_search'               => 'https://api.instagram.com/v1/tags/search?q=%s&access_token=%s',
		'locations'                 => 'https://api.instagram.com/v1/locations/%d?access_token=%s',
		'locations_recent'          => 'https://api.instagram.com/v1/locations/%d/media/recent/?max_id=%s&min_id=%s&max_timestamp=%s&min_timestamp=%s&access_token=%s',
		'locations_search'          => 'https://api.instagram.com/v1/locations/search?lat=%s&lng=%s&foursquare_id=%s&distance=%s&access_token=%s',
		'geographies'               => 'https://api.instagram.com/v1/geographies/%s/media/recent?client_id=%s'
	
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









    public function user_follows($user_id){

    	$url = sprintf($this->_api_urls['user_follows'], $user_id, $this->CI->session->userdata("access_token"), $this->scope);

		return $this->get_curl($url);

    }


    public function self_liked(){

    	$url = sprintf($this->_api_urls['self_liked'], $this->CI->session->userdata("access_token"), $this->scope);

		return $this->get_curl($url);

    }






   







    protected function get_curl($curlopt_url){

		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $curlopt_url,
		    CURLOPT_USERAGENT => 'Instagram cURL Request'
		));

		$response = curl_exec($curl);
		curl_close($curl);

		return json_decode($response);
	}

	/*
    	generate access token and save it to sesssion

    */

	private function generate_access_token(){

		$url = $this->_api_urls['access_token'];
		$fields = array (
		    'client_id' => $this->client_id,
		    'client_secret' => $this->client_secret,
		    'grant_type' => 'authorization_code',
		    'redirect_uri' => $this->redirect_uri,
		    'code' => $this->CI->session->userdata("access_code"),
		);

		$fields_string = "";

		foreach($fields as $key=>$value){

			$fields_string .= $key.'='.$value.'&';
		}

		rtrim($fields_string, '&');

		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL, $url);
		curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$token = curl_exec($curl);
		curl_close($curl);

		$result = json_decode($token, true);

		if(isset ($result['code']) AND $result['code'] == 400){

			$base = $this->base_url;
	    	redirect($base);
		}

		$this->CI->session->unset_userdata('access_code');
		
		return $result['access_token'];	
		
	}

    /*
    	This returns authentication url to
		get code from instagram

    */

    public function authenticate(){

    	
     	if($this->CI->input->get('code') AND empty ($this->CI->session->userdata("access_token"))){
    			
    		$this->CI->session->set_userdata("access_code", $this->CI->input->get('code'));
      		$this->CI->session->set_userdata("access_token", $this->generate_access_token());
    	} 
	    

    	$login_url = $this->_api_urls['login_url'] . $this->client_id;
		$login_url .= '&redirect_uri=' . $this->redirect_uri;
		$login_url .= '&response_type=code';
		$login_url .= '&scope=' . $this->scope;
		$login_url .= '&state=' . $this->state;
	
		return $login_url;
		

    }

    /*
    	destroy session/access token

    */

    public function logout(){

    	$this->CI->session->sess_destroy();
    	$base = $this->base_url;
    	redirect($base);

    }
   

	/*
    	Get id of the owner of the access_token.

    */

	public function get_self_id(){

		$url = sprintf($this->_api_urls['self'], $this->CI->session->userdata("access_token"));

		$user_id = $this->get_curl($url);

		return $user_id->data->id;

	}

	/*
    	
    	Get information about the owner of the access_token.

    */

	public function get_self(){

		$url = sprintf($this->_api_urls['self'], $this->CI->session->userdata("access_token"));

		return $this->get_curl($url);
	}



	 // search user by username

    public function user_search($user_name){

    	$url = sprintf($this->_api_urls['user_search'] ,$user_name ,$this->CI->session->userdata("access_token"));

		return $this->get_curl($url);

    }


    // get user feed by user id

    public function user_media_recent($user_id){

    	$url = sprintf($this->_api_urls['user_media_recent'] ,$user_id ,$this->CI->session->userdata("access_token"));

		return $this->get_curl($url);

    }


    // get user info 

    public function user($user_id){

    	$url = sprintf($this->_api_urls['user'], $user_id ,$this->CI->session->userdata("access_token"));

		return $this->get_curl($url);

    }


    /*
		
		get the recent media  posts.
	
    */


    public function self_media_recent(){

		$url = sprintf($this->_api_urls['self_media_recent'], $this->CI->session->userdata("access_token"));

		return $this->get_curl($url);
		
	}




	
    
}