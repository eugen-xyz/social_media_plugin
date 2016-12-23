<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Linkedin_plugin{

	protected $CI;     

	public $state = "";       
	public $response_type = "code";

	protected $code = "";
	protected $access_token = "";
	protected $redirect_uri = _LI_REDIRECT_URI_;
	protected $client_id = _LI_CLIENT_ID_;
	protected $client_secret = _LI_CLIENT_SECRET_;

	private $oauth2_authorization = "https://www.linkedin.com/oauth/v2/authorization";
	private $oauth2_access_token = "https://www.linkedin.com/oauth/v2/accessToken";
	private $linkedIn_profile = "https://api.linkedin.com/v1/people/";
	private $share_article = "https://www.linkedin.com/shareArticle?mini=true";
	

	public function __construct(){

        $this->CI =& get_instance();
        $this->state = md5(uniqid('I4asia', true));        
    }


	public function authenticate(){

		return $this->oauth2_authorization . '?response_type=' .$this->response_type .'&client_id=' . $this->client_id . '&redirect_uri=' . $this->redirect_uri . '&state=' . $this->state;

	}

	public function get_user_profile(){

		$this->code = $this->CI->input->get('code');

		if( isset($this->code) ){

			$url = $this->oauth2_access_token;
			$fields = array (
			    'client_id' => $this->client_id,
			    'client_secret' => $this->client_secret,
			    'grant_type' => 'authorization_code',
			    'redirect_uri' => $this->redirect_uri,
			    'code' => $this->code,
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

			if( ! isset($result['error'])){

				$this->access_token = $result['access_token'];
				return $this->_get_user_profile();

			}
		}

	}

	private function _get_user_profile(){

		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $this->linkedIn_profile . '~:(id,first-name,maiden-name,last-name,email-address,headline,location,industry,num-connections,summary,specialties,positions,picture-urls::(original),api-standard-profile-request,public-profile-url,site-standard-profile-request)?format=json&oauth2_access_token='. $this->access_token,
		    CURLOPT_USERAGENT => 'LinkedIn cURL Request'
		));

		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
	}



	public function share_article($title, $summary, $url){

		$share = $this->share_article;
		$share .= '&title='.htmlentities($title).'';
		$share .= '&summary='.htmlentities($summary).'';
		$share .= '&url='.$url.'';
		$link = "window.open('$share',
			'Share On LinkedIn', 'width=500, height=600'); return false;";
		$data = array(
				'share' => $share,
				'link' => $link,
			);
		return $data;

	}


	/*


	public function anchor_share(){

		$share = $this->share_article;
		$share .= '&title='.htmlentities($title).'';
		$share .= '&summary='.htmlentities($summary).'';
		$share .= '&url='.$url.'';

		if($image == null){
			$image = base_url('uploads/linkedin.png');
		}


		return '<a href="'.$share.'" onclick="window.open(\''.$share.'\', \'Share On LinkedIn\', \'width=500, height=600\'); return false;">
		 		<img src="'. $image .'">
		 		</a>';

	}

	*/
}