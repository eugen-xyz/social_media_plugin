

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
	

	<div class="col-md-6 user_profile">
		<br /><br />
		<center>
			<button id="get-user" class="btn btn-success">View User Profile</button>
		</center>
	</div>


	<div class="col-md-12 recent_post">
		<button id="recent-post" class="btn btn-success">View Recent Posts</button>
	</div>





<script type="text/javascript">
	
	$('#get-user').click(function(){

		var url = "<?php echo site_url('instagram_plug/get_user'); ?>";

		$.get(url, function(data){

			$(".user_profile").append(data);
			$("#get-user").hide();
		});

	});


	$('#recent-post').click(function(){

		var url = "<?php echo site_url('instagram_plug/recent_post'); ?>";

		$.get(url, function(data){

			$(".recent_post").append(data);
			$("#recent-post").hide();
		});

	});

</script>