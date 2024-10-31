<?php 
global $post, $custom;

if( !$custom )
	$custom = $this->get_post_custom( $post->ID );

$amazon_categories = $this->GetAmazonCategories();
$blog_categories = $this->GetBlogCategories();
$post_statuses = $this->GetPostStatuses();
?>

<input type="hidden" name="content" value="Empty" />

<fieldset>
	<legend>Product Discovery</legend>
	
	<?php $nextItem = 'keyword_search'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Search for keywords</label>
		<div class="controls">
			<input type="text" class="span3" id="<?php echo $nextItem; ?>" placeholder="Type something" data-provide="typeahead" data-items="4" name="field_<?php echo $nextItem; ?>" value="">
			<span class="help-inline">or type them below</span>
		</div>
	</div>
	
	<?php $nextItem = 'keywords'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>"></label>
		<div class="controls">
			<textarea class="span4" id="<?php echo $nextItem; ?>" rows="5" name="field_<?php echo $nextItem; ?>" placeholder="Comma-separated keyword list"><?php echo $custom[ $nextItem ]; ?></textarea>
		</div>
	</div>
	
	<?php $nextItem = 'amazon_product_category'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Amazon Category</label>
		<div class="controls">
			<select data-placeholder="Choose an Optin Form" name="field_<?php echo $nextItem; ?>" class="chzn-select">
				<option value=""></option> 
				
				<?php
				if( $amazon_categories )
				{
					foreach( $amazon_categories as $key => $value )
					{
						if( $custom[ $nextItem ] == $key )
							$selected = 'selected="selected"';
						else
							$selected = ''; ?>
				<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
					<?php
					}
				} ?>
			</select>
			<span class="help-block">This is optional; forces products in only these categories</span>
		</div>
	</div>
	
</fieldset>

<fieldset>
	<legend>Post Settings</legend>
	
	<?php $nextItem = 'custom_post_title'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Post Title</label>
		<div class="controls">
			<input type="text" class="span5" id="<?php echo $nextItem; ?>" name="field_<?php echo $nextItem; ?>" value="<?php echo $custom[ $nextItem ]; ?>">
			<span class="help-inline">Available codes below</span>
			<span class="help-block">
				<code>[product_title]</code> 
				<code>[product_price]</code> 
				<code>[product_url]</code> 
				<code>[product_description]</code>
			</span>
		</div>
	</div>
	
	<?php $nextItem = 'new_post_category'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Blog category</label>
		<div class="controls">
			<select data-placeholder="Select a category" name="field_<?php echo $nextItem; ?>" class="chzn-select">
				<option value=""></option> 
				
				<?php
				if( $blog_categories )
				{
					foreach( $blog_categories as $key => $value )
					{
						if( $custom[ $nextItem ] == $value->cat_ID )
							$selected = 'selected="selected"';
						else
							$selected = ''; ?>
				<option <?php echo $selected; ?> value="<?php echo $value->cat_ID; ?>"><?php echo $value->cat_name; ?></option>
					<?php
					}
				} ?>
			</select>
			<span class="help-inline">if you want created posts to be categorized</span>
		</div>
	</div>
	
	<?php $nextItem = 'new_post_status'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Post status</label>
		<div class="controls">
			<select data-placeholder="Select a status" name="field_<?php echo $nextItem; ?>" class="chzn-select">
				<option value=""></option> 
				
				<?php
				if( $post_statuses )
				{
					foreach( $post_statuses as $key => $value )
					{
						if( $custom[ $nextItem ] == $key )
							$selected = 'selected="selected"';
						else
							$selected = ''; ?>
				<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
					<?php
					}
				} ?>
			</select>
			<span class="help-inline">incase you wanted to review posts first</span>
		</div>
	</div>
	
</fieldset>

<fieldset>
	<legend>Pinterest Options</legend>
	
	<?php $nextItem = 'pinterest_text'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Pin text</label>
		<div class="controls">
			<input type="text" class="span5" id="<?php echo $nextItem; ?>" name="field_<?php echo $nextItem; ?>" value="<?php echo $custom[ $nextItem ]; ?>">
			<span class="help-inline">Available codes below</span>
			<span class="help-block">
				<code>[product_title]</code> 
				<code>[product_price]</code> 
				<code>[product_url]</code> 
				<code>[product_description]</code>
			</span>
		</div>
	</div>
	
	<?php $nextItem = 'pinterest_url'; ?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $nextItem; ?>">Board url</label>
		<div class="controls">
			<input type="text" class="span5" id="<?php echo $nextItem; ?>" name="field_<?php echo $nextItem; ?>" value="<?php echo $custom[ $nextItem ]; ?>">
		</div>
	</div>
	
</fieldset>
	





