<!DOCTYPE html>
<html>
<head>
	<title>LinkedIn</title>
</head>
<body>

	<?php
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
	?>
</body>
</html>