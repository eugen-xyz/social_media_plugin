<!DOCTYPE html>
<html>
<head>
	<title>LinkedIn Login</title>

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</head>
<body>

	<div class="jumbotron">
		<h1 style="text-align: center;">LinkedIn</h1>
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
			
			<div class="col-md-3">
				<a href="<?php echo $login; ?>">
					<img src="<?php echo base_url('uploads/signin.png'); ?>">
				</a>
			</div>

			<div class="clearfix"></div>

		</div>



		<div class="col-md-12 alert alert-success">
			<h1>Share to LinkedIn</h1>

			<br /><br />

			<form method="POST" action="<?php echo current_url(); ?>">

				<input type="text" name="title" class="form-control" placeholder="Title" />
				<br />
				<div class="clearfix"></div>

				<input type="text" name="summary" class="form-control" placeholder="Summary" />
				<br />
				<div class="clearfix"></div>

				<input type="url" name="url" class="form-control" placeholder="URL" />
				<br />
				<div class="clearfix"></div>

				<br />
				
				<button type="submit" name="submit" class="btn btn-success" value="submit">Submit</button>
			</form>
		</div>


		<div class="clearfix"></div>
		
		<?php 
			if($share['href']){
		?>	

				<div class="col-md-12 alert alert-success">
					<a href="<?php echo $share['href'] ?>" onclick="<?php echo $share['onclick'] ?>" >
						<button class="btn btn-primary">
							Share
						</button>
					</a>
				</div>
		<?php 
			}
		?>
	


	</div>

	<div class="col-md-6">
		<br /><br />
						
		<?php if(! isset($get_user_profile->errorCode)){ ?>
				<div class="alert alert-info">
				<h1>User Info</h1>
		<?php 
					echo print_r($get_user_profile);
		?>
				</div>
		<?php }	?>

	</div>

	<?php if(! isset($get_user_profile->errorCode)){ ?>

	<div class="col-md-6">
		<div class="col-md-12 alert alert-danger">
		
		<h1>Update Company Post</h1>

			<br /><br />

			<form method="POST" action="<?php echo current_url(); ?>">

				<input type="text" name="comment" class="form-control" placeholder="Comment" />
				<br />
				<div class="clearfix"></div>

				<input type="url" name="submitted_url" class="form-control" placeholder="Submitted-­url" />
				<br />
				<div class="clearfix"></div>

				<input type="text" name="title" class="form-control" placeholder="Title" />
				<br />
				<div class="clearfix"></div>

				<input type="text" name="description" class="form-control" placeholder="Description" />
				<br />
				<div class="clearfix"></div>

				<input type="url" name="image_url" class="form-control" placeholder="Submitted‐image-­url" />
				<br />
				<div class="clearfix"></div>
				
				<button type="submit" name="company" class="btn btn-success" value="company">Post</button>
			</form>
		</div>


	</div>
	<?php }	?>


	<?php if(! isset($company_posts->errorCode)){ ?>

	<div class="col-md-12">
		<div class="col-md-12 alert alert-success">
		
		<h1>Company Posts</h1>

			<?php echo print_r($company_posts); ?>

			
		</div>


	</div>
	<?php }	?>






</body>
</html>