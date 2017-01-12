<!DOCTYPE html>
<html>
<head>
	<title>Instagram Login</title>
</head>
<body>


	<a href="<?php echo $login; ?>">Login with IG</a>


	<a href="<?php echo $logout; ?>">Logout</a>


	<?php if(isset($user_id)) echo print_r($user_id); ?>

</body>
</html>