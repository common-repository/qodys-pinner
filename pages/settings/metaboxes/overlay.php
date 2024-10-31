
<fieldset>
	<legend>Social Image Overlays</legend>
	
	<?php $nextItem = 'use_pin_overlay'; ?>
	<?php $nextValue = $this->get_option( $nextItem ); ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Enable Overlays?</label>
		<div class="controls">
			<label class="checkbox">
				<input type="checkbox" onclick="jQuery('#pin_overlay_content').toggle();" name="<?php echo $nextItem; ?>" <?php echo $nextValue == 'yes' ? 'checked="checked"' : ''; ?> value="yes">
				Yes - will show up over images in posts / pages
				<p class="help-block"><strong>Note:</strong> theme must have <code>wp_footer()</code> to work (most do).</p>
			</label>
		</div>
	</div>
	
	
	
</fieldset>

<div id="pin_overlay_content" style="display:<?php echo $this->get_option( $nextItem ) == 'yes' ? 'block' : 'none'; ?>;">
	
	<fieldset>
		<legend>Appearance</legend>
		
		<?php $nextItem = 'pin_overlay_post_types'; ?>
		<?php $nextValue = is_array( $this->get_option( $nextItem ) ) ? $this->get_option( $nextItem ) : array(); ?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $nextItem; ?>">Post Types</label>
			<div class="controls">
			<?php
			$data = get_post_types( null, 'objects' );
			
			if( $data )
			{
				foreach( $data as $key => $value )
				{
					if( in_array( $value->name, $nextValue ) )
						$checked = 'checked="checked"';
					else
						$checked = ''; ?>
				<label class="checkbox">
					<input type="checkbox" name="<?php echo $nextItem; ?>[]" <?php echo $checked; ?> value="<?php echo $value->name; ?>">
					<?php echo $value->labels->name; ?>
				</label>
				<?php
				}
			} ?>
				<p class="help-block"><strong>Note:</strong> with none checked, overlays will never show.</p>
			</div>
		</div>
		
		<?php $nextItem = 'pin_overlay_image_style'; ?>
		<?php $nextValue = $this->get_option( $nextItem ); ?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $nextItem; ?>">Overlay Color</label>
			<div class="controls">
				<label class="radio inline">
					<input type="radio" name="<?php echo $nextItem; ?>" <?php echo $nextValue == 'red' || !$nextValue ? 'checked="checked"' : ''; ?> value="red">
					red
				</label>
				<label class="radio inline">
					<input type="radio" name="<?php echo $nextItem; ?>" <?php echo $nextValue == 'grey' ? 'checked="checked"' : ''; ?> value="grey">
					grey
				</label>
			</div>
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Default Content</legend>
		
		<!--<?php $nextItem = 'pin_overlay_title'; ?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $nextItem; ?>">Default Pin Title</label>
			<div class="controls">
				<input type="text" class="span3" id="<?php echo $nextItem; ?>" name="<?php echo $nextItem; ?>" value="<?php echo $this->get_option( $nextItem, true ); ?>">
				<span class="help-inline">Available codes below</span>
				<span class="help-block">
					<code>[post_title]</code> 
					<code>[post_author]</code> 
					<code>[post_date]</code> 
					<code>[post_excerpt]</code>
				</span>
			</div>
		</div>-->
		
		<?php $nextItem = 'pin_overlay_text'; ?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $nextItem; ?>">Default Pin Text</label>
			<div class="controls">
				<textarea class="span4" id="<?php echo $nextItem; ?>" rows="5" name="<?php echo $nextItem; ?>"><?php echo $this->Clean( $this->get_option( $nextItem ) ); ?></textarea>
				<span class="help-inline">Available codes below</span>
				<span class="help-block">
					<code>[post_title]</code> 
					<code>[post_author]</code> 
					<code>[post_date]</code> 
					<code>[post_excerpt]</code>
					<code>[post_content]</code>
				</span>
			</div>
		</div>
		
	</fieldset>
		
</div>



