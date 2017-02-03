<?php 
		if($media->meta->code != 400 && ! isset($media->errorCode)){
	?>

	
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


	<?php }	?>