<!DOCTYPE html>
<html>
<head>
	<title>LinkedIn</title>
</head>
<body>

	<?php
	if($token != ""){
		$token =  $token->access_token;
	}
	if($profile == "") {
	?>
		<a href="<?php echo $auth; ?>">
			<img src="<?php echo base_url('uploads/signin.png'); ?>">
		</a>
		<br /><br />
	<?php
	}
	else{
		echo $profile;
	}

		echo $this->linkedin_plugin->share('uploads/linkedin.png');

	?>




</body>
</html>