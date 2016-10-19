<!DOCTYPE html>
<html>
<head>
	<title>LinkedIn</title>
</head>
<body>

	<?php
		$token =  $token->access_token;
	if($profile == "") {
	?>
		<a href="<?php echo $auth; ?>">
			<img src="<?php echo base_url('uploads/signin.png'); ?>">
		</a>

	<?php
	}
	else{
		echo $profile;
	}


	$share = "https://www.linkedin.com/shareArticle";
	$share .= '?mini=true';
	$share .= '&title=I4asia';
	$share .= '&summary=Where%20creativity%20meets%20genius.';
	$share .= '&url=http://www.i4asiacorp.com/';

	?>


	<a href="<?php echo $share ?>" onclick="window.open('<?php echo $share;?> ', 'Share to LinkedIn', 'width=500, height=600'); return false;">
		Share
	</a>

</body>
</html>