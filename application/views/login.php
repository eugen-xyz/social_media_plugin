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
			if(! empty($share)){
		?>

			<a href="<?php echo $share['share'] ?>" onclick=" <?php echo $share['link'] ?>">Share to LinkedIn</a>
		
		<?php 
			}

		?>

		
		<br /><br />





</body>
</html>