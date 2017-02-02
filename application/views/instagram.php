<!DOCTYPE html>
<html>
<head>
	<title>Instagram Login</title>

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

</head>
<body>

	<div class="jumbotron">
		<h1 style="text-align: center;">Instagram</h1>
	</div>

	<div class="col-md-1 col-md-offset-10">
		<a href="<?php echo $home; ?>">
			<button class="btn btn-primary">
				HOME
			</button>
		</a>
	</div>

	<div class="col-md-1">
		<a href="<?php echo $logout; ?>">
			<button class="btn btn-primary">
				Log Out
			</button>
		</a>
	</div>


	<div class="col-md-6">
		<br /><br />
		<div class="alert alert-info">
			
			<div class="col-md-6">
				<a href="<?php echo $login; ?>">
					<img src="<?php echo base_url('uploads/instagram.png'); ?>" height="50px" style='padding-right:7px;' />Signin with Instagram
				</a>
			</div>

			<div class="clearfix"></div>

		</div>
	</div>


	<div class="col-md-6">
		<br /><br />


		<?php if($user_id->meta->code != 400 && ! isset($user_id->errorCode)){?>
				<div class="alert alert-info">
				<h1>User Info</h1>


				<div class="col-md-3">
					
					<img src="<?php echo $user_id->data->profile_picture; ?>" />

				</div>
			
				<div class="col-md-9">

					<p> BIO: <?php echo $user_id->data->bio; ?> </p>
					<p> Userame: <?php echo $user_id->data->username; ?> </p>
					<p> Name: <?php echo $user_id->data->full_name; ?> </p>


				</div>


				<div class="clearfix"></div>

				</div>
		<?php }	?>

	</div>


	<?php 
		if($media->meta->code != 400 && ! isset($media->errorCode)){
	?>

	<div class="col-md-12">
		<div class="alert alert-success">
			
			<?php 

				foreach ($media->data as $data) {
			?>

					<?php 

						echo date('M d, Y ', $data->created_time);

						

						if(isset($data->videos)){
						?>

						 	<video width="320" height="320" controls>
								  <source src="<?php echo $data->videos->low_resolution->url; ?>" type="video/mp4">

							Your browser does not support the video tag.
							</video> 

						<?php 
						}


						else if($data->images){
						?>

							<img src="<?php echo $data->images->low_resolution->url; ?>"
								 >

						<?php 
						}


						if($data->location){
							echo $data->location->name;
						}

						if(isset($data->caption)){

							echo $data->caption->text;
						}


						if($data->users_in_photo){

							//echo print_r($data->users_in_photo[0]->user->username);

							foreach ($data->users_in_photo as $tag) {
								echo $tag->user->full_name;
							}
						}


						echo "<br />";
						
					?>

			<?php 
				}

			?>

		</div>
	</div>


	<?php }	?>




</body>
</html>