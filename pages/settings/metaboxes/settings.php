<?php
$themes = $this->GetThemes();
?>

<fieldset>
	<legend>Amazon Credentials</legend>
	
	<?php $nextItem = 'amazon_access_key'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Access Key ID</label>
		<div class="controls">
			<input type="text" class="span3" id="<?php echo $nextItem; ?>" name="<?php echo $nextItem; ?>" value="<?php echo $this->get_option( $nextItem, true ); ?>">
			<span class="help-inline"><a target="_blank" href="https://affiliate-program.amazon.com/gp/advertising/api/detail/main.html">click here</a> to get one</span>
		</div>
	</div>
	
	<?php $nextItem = 'amazon_secret_key'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Secret Key</label>
		<div class="controls">
			<input type="text" class="span3" id="<?php echo $nextItem; ?>" name="<?php echo $nextItem; ?>" value="<?php echo $this->get_option( $nextItem, true ); ?>">
		</div>
	</div>
	
	<?php $nextItem = 'amazon_associate_id'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Amazon Associate ID</label>
		<div class="controls">
			<input type="text" class="span3" id="<?php echo $nextItem; ?>" name="<?php echo $nextItem; ?>" value="<?php echo $this->get_option( $nextItem, true ); ?>">
		</div>
	</div>
	
</fieldset>

<fieldset>
	<legend>Pinterest Credentials</legend>
	
	<?php $nextItem = 'pinterest_login_email'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Login Email</label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on">
					<i class="icon-envelope"></i>
				</span>
				<input type="text" class="span3" id="<?php echo $nextItem; ?>" name="<?php echo $nextItem; ?>" value="<?php echo $this->get_option( $nextItem, true ); ?>">
				<span class="help-inline"><a target="_blank" href="http://pinterest.com/landing/">click here</a> to signup for pinterest</span>
			</div>
		</div>
	</div>
	
	<?php $nextItem = 'pinterest_login_password'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Login Password</label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on">
					<i class="icon-lock"></i>
				</span>
				<input type="password" class="span3" id="<?php echo $nextItem; ?>" name="<?php echo $nextItem; ?>" value="<?php echo $this->get_option( $nextItem, true ); ?>">
			</div>
		</div>
	</div>
	
</fieldset>

<!--<fieldset>
	<legend>"The Best Spinner" Credentials</legend>
	
	<?php $nextItem = 'best_spinner_username'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Login Username</label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on">
					<i class="icon-user"></i>
				</span>
				<input type="text" class="span3" id="<?php echo $nextItem; ?>" name="<?php echo $nextItem; ?>" value="<?php echo $this->get_option( $nextItem, true ); ?>">
				<span class="help-inline"><a href="#">click here</a> to signup for "the best spinner"</span>
			</div>
		</div>
	</div>
	
	<?php $nextItem = 'best_spinner_password'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Login Password</label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on">
					<i class="icon-lock"></i>
				</span>
				<input type="text" class="span3" id="<?php echo $nextItem; ?>" name="<?php echo $nextItem; ?>" value="<?php echo $this->get_option( $nextItem, true ); ?>">
			</div>
		</div>
	</div>
	
</fieldset>-->

<!--<fieldset>
	<legend>Advertising Settings</legend>
	
	<?php $nextItem = 'top_ad_code'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Top Ad Code<br>(optional)</label>
		<div class="controls">
			<textarea class="span4" id="<?php echo $nextItem; ?>" rows="5" name="<?php echo $nextItem; ?>"><?php echo $this->Clean( $this->get_option( $nextItem ) ); ?></textarea>
		</div>
	</div>
	
	<?php $nextItem = 'bottom_ad_code'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Bottom Ad Code<br>(optional)</label>
		<div class="controls">
			<textarea class="span4" id="<?php echo $nextItem; ?>" rows="5" name="<?php echo $nextItem; ?>"><?php echo $this->Clean( $this->get_option( $nextItem ) ); ?></textarea>
		</div>
	</div>
		
</fieldset>-->

<fieldset>
	<legend>Theme Options</legend>
	
	<?php $nextItem = 'active_theme'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Active Theme</label>
		<div class="controls">
			<select name="<?php echo $nextItem; ?>">
				<?php
				$real_value = $this->get_option( $nextItem );
				
				foreach( $themes as $key => $value )
				{
					if( $value == $real_value || (!$real_value && $key == 'default') )
						$selected = 'selected="selected"';
					else
						$selected = ''; ?>
				<option <?php echo $selected; ?> value="<?php echo $value; ?>"><?php echo $this->GetFromSlug($value); ?></option>
				<?php
				} ?>
				
			</select>
			<span class="help-block">Choose how to display the content controlled by this plugin.</span>
		</div>
	</div>
	
</fieldset>



