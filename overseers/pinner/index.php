<?php
class qodyOverseer_Pinner extends QodyOverseer
{
	function __construct()
	{
		$this->SetOwner( func_get_args() );
		
		$this->m_raw_file = __FILE__;
		
		if( $this->get_option('use_pin_widget') == 'yes' )
		{
			add_action( 'the_content', array( $this, 'InsertWidgetToContent' ) );
			add_action( 'wp_footer', array( $this, 'InsertWidgetJavascript' ) );
		}
		
		// needs to happen after parent construct to load assets
		if( $this->get_option('use_pin_overlay') == 'yes' )
		{
			add_action( 'the_content', array( $this, 'InsertOverlayToContent' ) );
			add_action( 'template_redirect', array( $this, 'InsertOverlayCode' ) );
		}
		
		parent::__construct();
	}
	
	function LoadDefaultOptions()
	{	
		$this->CreateTables();
	}
	
	function CreateTables()
	{
		$fields = array();
		$fields[] = '`id` int(11) NOT NULL AUTO_INCREMENT';
		$fields[] = '`url` varchar(400) NOT NULL';
		$fields[] = '`title` varchar(400) NOT NULL';
		$fields[] = '`keyword` varchar(255) NOT NULL';
		$fields[] = '`status` int(2) NOT NULL';
		$fields[] = '`description` text NOT NULL';
		$fields[] = '`price` varchar(255) NOT NULL';
		$fields[] = '`image` varchar(500) NOT NULL';
		$fields[] = 'PRIMARY KEY (`id`)';
		
		$append_config = 'ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';
		
		$this->GetClass('db')->CreateTable( 'amazon_links', $fields, $append_config, $this );
	}
	
	function InsertWidgetToContent( $content = '' )
	{
		global $post;
		
		// recall system variables
		$count_layout = $this->get_option('pin_widget_display_type');
		$post_types = is_array( $this->get_option('pin_widget_post_types') ) ? $this->get_option('pin_widget_post_types') : array();
		$horizontal_alignment = $this->get_option('pin_widget_horizontal_alignment');
		$vertical_alignment = is_array( $this->get_option('pin_widget_vertical_alignment') ) ? $this->get_option('pin_widget_vertical_alignment') : array();
		
		$clear = '<div style="clear:both;"></div>';
		
		// only show widget on selected post types
		if( !in_array( $post->post_type, $post_types ) )
			return $content;
		
		// build widget code
		$fields = array();
		$fields['url'] = $this->FilterForDynamicPostVariables( get_permalink( $post->ID ) );
		$fields['media'] = $this->get_option('pin_widget_image');
		$fields['description'] = $this->FilterForDynamicPostVariables( $this->get_option('pin_widget_text') );

		$data = $this->CreateButtonCode( $fields, $count_layout );
		
		$container_classes = 'qody_pinner_container ';
		
		// adjust it's position horizontally
		switch( $horizontal_alignment )
		{
			case 'left':
			case 'right':
				
				$data = '<div style="float:'.$horizontal_alignment.';">'.$data.'</div>';
				
				break;
			
			case 'center':
			default:
				
				$data = '<center>'.$data.'</center>'; // don't hate me for this : )
				
				break;
		}
		
		if( in_array( 'above', $vertical_alignment ) )
		{
			$content = $clear.'<div class="qody_pinner_container above">'.$data.$clear.'</div>'.$content;
		}
		
		if( in_array( 'below', $vertical_alignment ) )
		{
			$content = $content.$clear.'<div class="qody_pinner_container below">'.$data.$clear.'</div>';
		}
		
		return $content;
	}
	
	function InsertOverlayToContent( $content = '' )
	{
		global $post;
		
		// recall system variables
		$post_types = is_array( $this->get_option('pin_overlay_post_types') ) ? $this->get_option('pin_overlay_post_types') : array();
		
		// only show widget on selected post types
		if( !in_array( $post->post_type, $post_types ) )
			return $content;
		
		switch( $this->get_option('pin_overlay_image_style') )
		{
			case 'grey':
				
				$overlay_image = $this->GetAsset( 'images', 'overlay-1', 'url' );
				
				break;
				
			case 'red':
			default:
				
				$overlay_image = $this->GetAsset( 'images', 'overlay-2', 'url' );
				
				break;
		}
		
		// this is what we can latch onto when adding stuff to images
		$content = '<span id="qody_pinner_content_container">'.$content.'</span>';
		$content .= '<style>.qody_pinner_wrapper span { background:url('.$overlay_image.') repeat scroll center center transparent;}</style>';
		
		return $content;
	}
	
	function FilterForDynamicPostVariables( $text = '' )
	{
		global $post;
		
		$author_data = get_userdata( $post->post_author );
		
		$text = str_replace( '[post_title]', $post->post_title, $text );
		$text = str_replace( '[post_author]', $author_data->data->display_name, $text );
		$text = str_replace( '[post_date]', $post->post_date, $text );
		$text = str_replace( '[post_excerpt]', $post->post_excerpt, $text );
		$text = str_replace( '[post_content]', $post->post_content, $text );
		
		return $text;
	}
	
	function InsertWidgetJavascript()
	{
		echo '<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>';
	}
	
	function CreateButtonCode( $fields, $count_layout = 'none' )
	{
		$data = '<a href="http://pinterest.com/pin/create/button/';
		$data .= '?'.http_build_query( $fields ).'" class="pin-it-button" count-layout="'.$count_layout.'">';
		$data .= '<img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>';
		
		return $data;
	}
	
	function InsertOverlayCode()
	{
		global $post;
		
		// recall system variables
		$count_layout = $this->get_option('pin_overlay_display_type');
		$post_types = is_array( $this->get_option('pin_overlay_post_types') ) ? $this->get_option('pin_overlay_post_types') : array();
		
		$clear = '<div style="clear:both;"></div>';
		
		// only show widget on selected post types
		if( !in_array( $post->post_type, $post_types ) )
			return;
		
		$fields = array();
		$fields['description'] = $this->FilterForDynamicPostVariables( $this->get_option('pin_overlay_text') );
		$fields['url'] = get_permalink( $post->ID );
		
		$this->RegisterScript( 'pin_overlay', $this->GetAsset( 'js', 'overlay', 'url' ).'?'.http_build_query( $fields ), array( 'jquery' ) );
		$this->EnqueueScript( 'pin_overlay' );
		
		$this->RegisterStyle( 'pin_overlay', $this->GetAsset( 'css', 'overlay', 'url' ) );
		$this->EnqueueStyle( 'pin_overlay' );
	}
	
}
?>