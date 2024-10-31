
<fieldset>
	<legend>Pinterest Widget</legend>
	
	<?php $nextItem = 'use_pin_widget'; ?>
	<?php $nextValue = $this->get_option( $nextItem ); ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Enable Widget?</label>
		<div class="controls">
			<label class="checkbox">
				<input type="checkbox" onclick="jQuery('#pin_widget_content').toggle();" name="<?php echo $nextItem; ?>" <?php echo $nextValue == 'yes' ? 'checked="checked"' : ''; ?> value="yes">
				Yes - will show up in single posts / pages
				<p class="help-block"><strong>Note:</strong> theme must have <code>wp_footer()</code> to work (most do).</p>
			</label>
		</div>
	</div>
	
	
	
</fieldset>

<div id="pin_widget_content" style="display:<?php echo $this->get_option( $nextItem ) == 'yes' ? 'block' : 'none'; ?>;">
	
	<fieldset>
		<legend>Appearance</legend>
		
		<?php $nextItem = 'pin_widget_display_type'; ?>
		<?php $nextValue = $this->get_option( $nextItem ); ?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $nextItem; ?>">Widget Type</label>
			<div class="controls">
				<label class="radio inline">
					<input type="radio" name="<?php echo $nextItem; ?>" <?php echo $nextValue == 'vertical' ? 'checked="checked"' : ''; ?> value="vertical" style="margin-top:18px;">
					<img src="<?php echo $this->GetAsset( 'images', 'pin-vertical', 'url' ); ?>" />
				</label>
				<label class="radio inline">
					<input type="radio" name="<?php echo $nextItem; ?>" <?php echo $nextValue == 'horizontal' || !$nextValue ? 'checked="checked"' : ''; ?> value="horizontal" style="margin-top:18px;">
					<img src="<?php echo $this->GetAsset( 'images', 'pin-horizontal', 'url' ); ?>" />
				</label>
				<label class="radio inline">
					<input type="radio" name="<?php echo $nextItem; ?>" <?php echo $nextValue == 'none' ? 'checked="checked"' : ''; ?> value="none" style="margin-top:18px;">
					<img src="<?php echo $this->GetAsset( 'images', 'pin-no-count', 'url' ); ?>" />
				</label>
			</div>
		</div>
		
		<?php $nextItem = 'pin_widget_post_types'; ?>
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
				<p class="help-block"><strong>Note:</strong> with none checked, widgets will never show.</p>
			</div>
		</div>
		
		<?php $nextItem = 'pin_widget_horizontal_alignment'; ?>
		<?php $nextValue = $this->get_option( $nextItem ); ?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $nextItem; ?>">Horizontal Alignment</label>
			<div class="controls">
				<label class="radio inline">
					<input type="radio" name="<?php echo $nextItem; ?>" <?php echo $nextValue == 'left' || !$nextValue ? 'checked="checked"' : ''; ?> value="left">
					left
				</label>
				<label class="radio inline">
					<input type="radio" name="<?php echo $nextItem; ?>" <?php echo $nextValue == 'center' ? 'checked="checked"' : ''; ?> value="center">
					center
				</label>
				<label class="radio inline">
					<input type="radio" name="<?php echo $nextItem; ?>" <?php echo $nextValue == 'right' ? 'checked="checked"' : ''; ?> value="right">
					right
				</label>
			</div>
		</div>
		
		<?php $nextItem = 'pin_widget_vertical_alignment'; ?>
		<?php $nextValue = is_array( $this->get_option( $nextItem ) ) ? $this->get_option( $nextItem ) : array(); ?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $nextItem; ?>">Vertical Alignment</label>
			<div class="controls">
				<label class="checkbox inline">
					<input type="checkbox" name="<?php echo $nextItem; ?>[]" <?php echo in_array( 'above', $nextValue ) ? 'checked="checked"' : ''; ?> value="above">
					Above post
				</label>
				<label class="checkbox inline">
					<input type="checkbox" name="<?php echo $nextItem; ?>[]" <?php echo in_array( 'below', $nextValue ) ? 'checked="checked"' : ''; ?> value="below">
					Below post
				</label>
				<p class="help-block"><strong>Note:</strong> with neither checked, widgets will never show.</p>
			</div>
		</div>
		
		<?php $nextItem = 'pin_widget_custom_css'; ?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $nextItem; ?>">Custom CSS Styling<br>(optional)</label>
			<div class="controls">
				<textarea class="span4" id="<?php echo $nextItem; ?>" rows="5" name="<?php echo $nextItem; ?>"><?php echo $this->Clean( $this->get_option( $nextItem ) ); ?></textarea>
				<span class="help-block">The widget container class is <code>.qody_pinner_container</code> along with <code>.below</code> or <code>.above</code></span>
				<span class="help-block"><strong>Note:</strong> will automatically be wrapped in <code><?php echo htmlentities('<style></style>'); ?></code></span>
			</div>
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Default Content</legend>
		
		<!--<?php $nextItem = 'pin_widget_title'; ?>
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
		
		<?php $nextItem = 'pin_widget_text'; ?>
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
		
		<?php $nextItem = 'pin_widget_image'; ?>
		<div class="control-group">
			<label class="control-label" for="<?php echo $nextItem; ?>">Default Pin Image Url</label>
			<div class="controls">
				<input type="text" class="span5" id="<?php echo $nextItem; ?>" name="<?php echo $nextItem; ?>" value="<?php echo $this->get_option( $nextItem, true ); ?>">
				<p class="help-block"><strong>Note:</strong> an image/video is required for widgets to show up; this works as a backup.</span>
			</div>
		</div>
		
	</fieldset>
		
</div>



