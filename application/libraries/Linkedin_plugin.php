<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Linkedin_plugin{


	public function signin($client_id, $redirect_uri){


		$linkedin = "https://www.linkedin.com/oauth/v2/authorization";
		$response_type = "code";
		$state = md5(uniqid('I4asia', true));

		return   $linkedin . '?response_type=' .$response_type .'&client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&state=' . $state;


	}

	public function _access_token($code, $client_id, $client_secret, $redirect_uri){


		$url = "https://www.linkedin.com/oauth/v2/accessToken";
		$fields = array (
		    "client_id" => $client_id,
		    "client_secret" => $client_secret,
		    "grant_type" => "authorization_code",
		    'redirect_uri' => $redirect_uri,
		    'code' => $code,
		);
		$fields_string = "";

		foreach($fields as $key=>$value){

			$fields_string .= $key.'='.$value.'&';

		}
		rtrim($fields_string, '&');

		$ch = curl_init();

		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$token = curl_exec($ch);

		curl_close($ch);

		$result = json_decode($token, true);

		$obj = $result['access_token'];


		return $this->_get_profile($result['access_token']);

	}

	public function _get_profile($obj){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'https://api.linkedin.com/v1/people/~:(id,first-name,last-name,headline,picture-url,industry,summary,specialties,email_address,positions:(id,title,summary,start-date,end-date,is-current,company:(id,name,type,size,industry,ticker)),educations:(id,school-name,field-of-study,start-date,end-date,degree,activities,notes),associations,interests,num-recommenders,date-of-birth,publications:(id,title,publisher:(name),authors:(id,name),date,url,summary),patents:(id,title,summary,number,status:(id,name),office:(name),inventors:(id,name),date,url),languages:(id,language:(name),proficiency:(level,name)),skills:(id,skill:(name)),certifications:(id,name,authority:(name),number,start-date,end-date),courses:(id,name,number),recommendations-received:(id,recommendation-type,recommendation-text,recommender),honors-awards,three-current-positions,three-past-positions,volunteer)?format=json&oauth2_access_token='. $obj,
		    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
		));

		return $resp = curl_exec($curl);

		curl_close($curl);

	}



	public function share($title, $summary, $url){

		$share = "https://www.linkedin.com/shareArticle";
		$share .= '?mini=true';
		$share .= '&title='.$title.'';
		$share .= '&summary='.$summary.'';
		$share .= '&url='.$url.'';

		return $share;

	}


	public function anchor_share($title, $summary, $url, $image = null){

		$share = "https://www.linkedin.com/shareArticle";
		$share .= '?mini=true';
		$share .= '&title='.$title.'';
		$share .= '&summary='.$summary.'';
		$share .= '&url='.$url.'';

		if($image == null){
			$image = base_url('uploads/linkedin.png');
		}


		return '<a href="'.$share.'" onclick="window.open(\''.$share.'\', \'Share On LinkedIn\', \'width=500, height=600\'); return false;">
		 		<img src="'. $image .'">
		 		</a>';

	}
}