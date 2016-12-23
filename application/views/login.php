<!DOCTYPE html>
<html>
<head>
	<title>LinkedIn</title>
</head>
<body>

		<a href="<?php echo $signin ?>">
			<img src="<?php echo base_url('uploads/signin.png'); ?>">
		</a>
		<br /><br />

		<?php 

			if(! empty($profile)){

				echo "<pre>" . $profile . "</pre>";
			}

		?>

		
		<br /><br />



</body>
</html>