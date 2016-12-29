<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Linkedin_plugin{

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
	

	public function __construct($params = array()){

        if ( ! empty($params)) $this->initialize($params);

        $this->CI =& get_instance();
        $this->state = md5(uniqid('I4asia', true)); 
               
    }

    protected function initialize($params){

        foreach ($params as $key => $value) {
            if (isset($this->$key)) $this->$key = $value;
        }

        return $this;
    }

    private function generate_access_token(){

    	if( empty($this->access_token )){

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
				}
			}
		}

	}

	public function authenticate(){

		return $this->oauth2_authorization . '?response_type=' .$this->response_type .'&client_id=' . $this->client_id . '&redirect_uri=' . $this->redirect_uri . '&state=' . $this->state;

	}

	public function get_user_profile(){

		$this->generate_access_token();

		$url = $this->linkedIn_profile . '~:(id,first-name,maiden-name,last-name,email-address,headline,location,industry,num-connections,summary,specialties,positions,picture-urls::(original),api-standard-profile-request,public-profile-url,site-standard-profile-request)?format=json&oauth2_access_token='. $this->access_token;

		return $this->get_curl($url);
	
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

	public function company_sharing_enabled(){
		
		$url = $this->linkedIn_company . $this->company_id.'/is-company-share-enabled?oauth2_access_token=' . $this->access_token.'&format=json';

		return $this->get_curl($url);

	}


	public function member_is_admin(){
		
		$url = $this->linkedIn_company . $this->company_id.'/relation-to-viewer/is-company-share-enabled?oauth2_access_token=' . $this->access_token.'&format=json';

		return $this->get_curl($url);
	}


	public function list_all_companies(){
	
		$url = $this->linkedIn_company . '?oauth2_access_token=' . $this->access_token.'&format=json&is-company-admin=true';

		return $this->get_curl($url);
	}

	public function get_company_info(){
		
		$url =  $this->linkedIn_company . $this->company_id . ':(id,name,ticker,description)?oauth2_access_token=' . $this->access_token.'&format=json';

		return $this->get_curl($url);
	
	}

	public function company_follower(){

		$url =  $this->linkedIn_company . $this->company_id . '/num-followers?oauth2_access_token=' . $this->access_token.'&format=json&seniorities=vp,d&jobFunc=it&geos=na.ca';

		return $this->get_curl($url);

		// https://api.linkedin.com/v1/companies/{id}/num-followers?format=json&seniorities=vp,d&jobFunc=it&geos=na.ca
	}

	public function follower_stats(){

		$url =  $this->linkedIn_company . $this->company_id . '/historical-follow-statistics?oauth2_access_token=' . $this->access_token.'&format=json';

		return $this->get_curl($url);

		// https://api.linkedin.com/v1/companies/{id}/historical-follow-statistics?format=json
	}

	public function company_posts(){


		$url =  $this->linkedIn_company . $this->company_id . '/updates?oauth2_access_token=' . $this->access_token.'&format=json';

		return $this->get_curl($url);

	}

	public function specific_company_post(){

		$url =  $this->linkedIn_company . $this->company_id . '/updates/key='. $this->update_key .'?oauth2_access_token=' . $this->access_token.'&format=json';

		return $this->get_curl($url);

	}


	public function comment_for_specific_post(){

		$url =  $this->linkedIn_company . $this->company_id . '/updates/key='. $this->update_key .'/update-comments?oauth2_access_token=' . $this->access_token.'&format=json';

		return $this->get_curl($url);

	}


	public function like_for_specific_post(){

		$url =  $this->linkedIn_company . $this->company_id . '/updates/key='. $this->update_key .'/likes?oauth2_access_token=' . $this->access_token.'&format=json';

		return $this->get_curl($url);

	}

	protected function get_curl($curlopt_url){

		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $curlopt_url,
		    CURLOPT_USERAGENT => 'LinkedIn cURL Request'
		));

		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
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