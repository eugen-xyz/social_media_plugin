
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