<div class="wrap">
	<h1><?php _e( 'Settings galleries', 'path-gallery'); ?></h1>
	<form method="post" name="path-gallery" class="pg-form">
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="ispmanager_path"><?php _e('The main folder of galleries', 'path-gallery' ); ?></label>
				</th>
				<td>
					<input type="text" name="pg_settings[galleries_path]" class="regular-text" id="galleries_path" placeholder="<?php _e('for example /images/', 'path-gallery' ); ?>" value="<?php echo $settings['galleries_path'] ?>">
				</td>
			</tr>
		</table>
		<div class="form-group row">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="button button-primary customize load-customize hide-if-no-customize"><?php _e('Save', 'path-gallery' ); ?></button>
			</div>
		</div>
	</form>
</div>