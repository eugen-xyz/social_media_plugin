<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Linkedin_plugin{

	public function share($img){

		$share = "https://www.linkedin.com/shareArticle"; // fixed
		$share .= '?mini=true'; // fixed
		$share .= '&title=I4asia'; // dynamic
		$share .= '&summary=Where+creativity+meets+genius'; // dynamic
		$share .= '&url=http://dev.dennys.ph/'; // current url
		$img = base_url($img);


		return '<a href="'.$share.'" onclick="window.open(\''.$share.'\', \'Share On LinkedIn\', \'width=500, height=600\'); return false;">
				<img src="'. $img .'">
				</a>';

	}
}