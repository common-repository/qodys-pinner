<?php
class qodyPosttype_PinnerPinnablePost extends QodyPostType
{
	 function __construct()
    {
        $this->SetOwner( func_get_args() );
		
		$this->m_raw_file = __FILE__;
		
		$this->SetPriority( 5 );
        
        $this->m_show_in_menu = $this->GetPre().'-home.php';
        
        $this->m_supports[] = 'title';
        $this->m_supports[] = 'editor';
        $this->m_supports[] = 'thumbnail';
        $this->m_supports[] = 'excerpt';
        //$this->m_supports[] = null;
		
		$this->m_rewrite = array( 'slug' => 'pinnable-post' );
        
        $this->SetMassVariables( 'pinnable post', 'pinnable posts', true );
        
        parent::__construct();
    }
	
    function WhenViewingPostList()
    {
		if( !parent::WhenViewingPostList() )
			return;
			
		$this->EnqueueStyle( 'nicer-tables' );
    }
	
	function WhenShowing()
	{
		if( !parent::WhenShowing() )
			return;
		
		$this->GetClass('controller_pinnable-post')->AddContentFilter();
		$this->EnqueueStyle( 'structure-bootstrap' );
	}
	
	function WhenEditing()
	{
		if( !parent::WhenEditing() )
			return;
		
		$this->RemoveAllMetaboxesButMine( null, true );
		
		$this->BootstrapTheForm();
		
		$this->EnqueueStyle('chosen');
		
		$this->EnqueueScript('chosen');
		$this->EnqueueScript('bootstrap-typeahead');
	}

    function LoadMetaboxes()
    {
		$this->AddMetabox( 'general', 'Form Preset Settings' );
    }
    
	function GetAmazonCategories()
	{
		$data = array();
		$data['Apparel'] = 'Apparel';
		$data['Appliances'] = 'Appliances';
		$data['ArtsAndCrafts'] = 'ArtsAndCrafts';
		$data['Automotive'] = 'Automotive';
		$data['Baby'] = 'Baby';
		$data['Beauty" selected="selected'] = 'Beauty';
		$data['Blended'] = 'Blended';
		$data['Books'] = 'Books';
		$data['Classical'] = 'Classical';
		$data['DigitalMusic'] = 'DigitalMusic';
		$data['DVD'] = 'DVD';
		$data['Electronics'] = 'Electronics';
		$data['ForeignBooks'] = 'ForeignBooks';
		$data['Garden'] = 'Garden';
		$data['GourmetFood'] = 'GourmetFood';
		$data['Grocery'] = 'Grocery';
		$data['HealthPersonalCare'] = 'HealthPersonalCare';
		$data['Hobbies'] = 'Hobbies';
		$data['Home'] = 'Home';
		$data['HomeGarden'] = 'HomeGarden';
		$data['HomeImprovement'] = 'HomeImprovement';
		$data['Industrial'] = 'Industrial';
		$data['Jewelry'] = 'Jewelry';
		$data['KindleStore'] = 'KindleStore';
		$data['Kitchen'] = 'Kitchen';
		$data['LawnAndGarden'] = 'LawnAndGarden';
		$data['Lighting'] = 'Lighting';
		$data['Magazines'] = 'Magazines';
		$data['Marketplace'] = 'Marketplace';
		$data['Miscellaneous'] = 'Miscellaneous';
		$data['MobileApps'] = 'MobileApps';
		$data['MP3Downloads'] = 'MP3Downloads';
		$data['Music'] = 'Music';
		$data['MusicalInstruments'] = 'MusicalInstruments';
		$data['MusicTracks'] = 'MusicTracks';
		$data['OfficeProducts'] = 'OfficeProducts';
		$data['OutdoorLiving'] = 'OutdoorLiving';
		$data['Outlet'] = 'Outlet';
		$data['PCHardware'] = 'PCHardware';
		$data['PetSupplies'] = 'PetSupplies';
		$data['Photo'] = 'Photo';
		$data['Shoes'] = 'Shoes';
		$data['Software'] = 'Software';
		$data['SoftwareVideoGames'] = 'SoftwareVideoGames';
		$data['SportingGoods'] = 'SportingGoods';
		$data['Tools'] = 'Tools';
		$data['Toys'] = 'Toys';
		$data['UnboxVideo'] = 'UnboxVideo';
		$data['VHS'] = 'VHS';
		$data['Video'] = 'Video';
		$data['VideoGames'] = 'VideoGames';
		$data['Watches'] = 'Watches';
		$data['Wireless'] = 'Wireless';
		$data['WirelessAccessories'] = 'WirelessAccessories';
		
		return $data;
	}
	
	function GetBlogCategories()
	{
		$fields = array();
		
		$data = get_categories( $fields );
		
		return $data;
	}
	
	function GetPostStatuses()
	{
		$fields = array();
		$fields['publish'] = 'Published';
		$fields['draft'] = 'Draft';
		$fields['private'] = 'Private';
		
		return $fields;		
	}
	
	function FetchAmazonLinks( $keyword )
	{
		$data = $this->GetClass('db')->Select( 'amazon_links', "keyword = '".$keyword."' AND status = 0", $this );
		
		return $data;
	}
	
	function FindAndStoreNewAmazonLinks( $keyword, $campaign )
	{
		$custom = $this->get_post_custom( $campaign->ID );
		
		$amazonPublic = $this->get_option( 'amazon_access_key' );
		$amazonSecret = $this->get_option( 'amazon_secret_key' );
		$amazonAid = $this->get_option( 'amazon_associate_id' );
		
		if( !$amazonPublic )
		{
			echo "Missing the amazon public key";
			return;
		}
		else if( !$amazonSecret )
		{
			echo "Missing the amazon secret key";
			return;
		}
		else if( !$amazonAid )
		{
			echo "Missing the amazon associate id";
			return;
		}
		
		$page_history = maybe_unserialize( $custom['page_history'] );
		
		// check if we've run out of links to get for this keyword
		if( $page_history[ $keyword ] == -1 )
			return;
		
		$page = $page_history[ $keyword ];
		
		if( !$page )
			$page = 1;
			
		require_once( $this->GetAsset( 'includes', 'amazon_api', 'dir' ) );
		
		$amazon_api = new AmazonProductAPI( $amazonPublic, $amazonSecret, $amazonAid );
			
		try
		{
			$result = $amazon_api->searchProducts( $keyword, $page, $custom['amazon_product_category'], "TITLE" );
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
			
			return;
		}
		
		if( count( $result->Items->Item ) > 0 )
		{
			foreach( $result->Items->Item as $key => $value )
			{
				$product_title = $value->ItemAttributes->Title;
				$product_text = $value->EditorialReviews->EditorialReview->Content;
				$product_url = $value->ItemLinks->ItemLink[0]->URL;
				
				$data = $this->GetClass('db')->Select( 'amazon_links', "url = '".mysql_escape_string($product_url)."' and title = '".mysql_escape_string($product_title)."'", $this );
				
				if( $data )
					continue;
				
				$price = $value->OfferSummary->LowestNewPrice->FormattedPrice;
				
				if( !$price && $value->OfferSummary->LowestUsedPrice )
					$price = $value->OfferSummary->LowestUsedPrice->FormattedPrice;
				
				$image_url = $value->LargeImage->URL;
				
				$fields = array();
				$fields['url'] = $product_url;
				$fields['title'] = $product_title;
				$fields['keyword'] = $keyword;
				$fields['status'] = 0;
				$fields['description'] = $product_text;
				$fields['price'] = $price;
				$fields['image'] = $image_url;

				$this->GetClass('db')->InsertToDatabase( $fields, 'amazon_links', $this );
			}
			
			$page_history[ $keyword ] = $page + 1;
		}
		else
		{
			$page_history[ $keyword ] = -1;
		}
		
		$this->update_post_meta( $campaign->ID, 'page_history', $page_history );
	}
	
	function Process( $post )
	{
		$custom = $this->get_post_custom( $post->ID );
		
		$this->update_post_meta( $post->ID, 'last_processed', time() );
		
		$keyword_bits = explode( ',', $custom['keywords'] );
		
		if( !$keyword_bits )
			return;
		
		foreach( $keyword_bits as $key => $value )
		{
			$value = trim( $value );
			
			if( !$value )
				continue;
			
			$amazon_links = $this->FetchAmazonLinks( $value );
			
			if( !$amazon_links )
			{
				$this->FindAndStoreNewAmazonLinks( $value, $post );
				
				$amazon_links = $this->FetchAmazonLinks( $value );
			}
			
			if( !$amazon_links )
				continue;
			
			foreach( $amazon_links as $key2 => $value2 )
			{
				$post_title = $custom['custom_post_title'];
				
				$post_title = str_replace( '[product_title]', $value2['title'], $post_title );
				$post_title = str_replace( '[product_price]', $value2['price'], $post_title );
				$post_title = str_replace( '[product_url]', $value2['url'], $post_title );
				$post_title = str_replace( '[product_description]', $value2['description'], $post_title );
				
				$post_title=str_replace('[product_link]',$offer_url,$post_title);
				$post_title=str_replace('[product_price]',$offer_price,$post_title);
				
				$post_content=$camp->camp_post_content;
				$post_content=$this->spintax->spin($post_content);
				$post_content=str_replace('[product_title]',$offer_title,$post_content);
				$post_content=str_replace('[product_link]',$offer_url,$post_content);
				$post_content=str_replace('[product_price]',$offer_price,$post_content);
				$post_content=str_replace('[product_desc]',$offer_desc,$post_content);
				
				$img='<img class="product_thumb" style="width:'.$wp_amazonpin_tw.'px" src="'.$offer_img.'" />';
				$post_content=str_replace('[product_img]',$img,$post_content);
								

				
				
				//strip links
				if(in_array('OPT_STRIP',$camp_opt)){
					$abcont=strip_tags($abcont,'<p><img><b><strong><br>');
				}
				
				
				if(stristr($post_content,'[matched_content]')){
					$post_content =str_replace('[matched_content]',$abcont,$post_content);
				}else{
					$post_content.="<br>$abcont";
				}
				
				

				
				//replacing the keywords with affiliate links
				foreach($keywords as $keyword){
					if(trim($keyword != '')){
						$post_content=str_ireplace($keyword,'<a href="'.$offer_url.'">'.$keyword.'</a>',$post_content);
						
					}
				}

					  
			  
			  $my_post = array(
				 'post_title' => $post_title,
				 'post_content' => $post_content,
				 'post_status' => $camp->camp_post_status,
				 'post_author' => 1,
				 'post_category' => array($camp->camp_post_category)
			  );
			
			  
			  // Insert the post into the database
			  $id=wp_insert_post( $my_post );
			  add_post_meta($id, 'product_title', $offer_title);
			  add_post_meta($id, 'product_link', $offer_url);
			  add_post_meta($id, 'product_img', $offer_img);
			  
			  
			  echo '<br>New Post posted:'.get_the_title($id);
			  
			  //pin it 
			  if(in_array('OPT_PIN',$camp_opt)){
				  $pin_text=str_replace('[product_title]',$offer_title,$pin_text);
				  $this->pinterest_post($id,$pin_text,$pin_board);
			  }
			  
			  $this->log('Posted:'.$camp->camp_id,'New post posted:<a href="'.get_permalink($id).'">'.get_the_title($id).'</a>' );

			  //update the link status to 1
			  $query="update wp_amazonpin_links set link_status='1' where link_id=$ret->link_id";
			  $this->db->query($query);
			  
				break; // just process one link
			}
		}	
	}
	
    function DisplayListColumns( $column )
    {
		global $post;
		
		$post_id = $post->ID;
		
		$custom = $this->get_post_custom( $post_id );
        $the_meta = get_post_meta( $post_id, $column, true);
        
        switch( $column )
        {
			case "shortcode":
				
				echo '<input class="embed_input" type="text" onclick="this.select()" readonly="readonly" value=\''.$this->GetShortcode( $post_id ).'\'">';
					
				break;
			
			case "embed_code":
				
				echo '<input class="embed_input" type="text" onclick="this.select()" readonly="readonly" value=\''.$this->GetEmbedCode( $post_id ).'\'">';
					
				break;
			
			case "direct_link":
				
				echo '<input class="embed_input" type="text" onclick="this.select()" readonly="readonly" value=\''.get_permalink( $post_id ).'\'">';
					
				break;
			
			case "raw_link":
				
				$value = $this->GetClass('posttype_form')->GetAsset( 'includes', 'optin_iframe', 'url' ).'?p='.$post_id;
				echo '<input class="embed_input" type="text" onclick="this.select()" readonly="readonly" value=\''.$value.'\'">';
					
				break;
			
			case "list_id":
                
				if( !$the_meta )
					$the_meta = array();
				
				if( $the_meta )
				{
					$iter = 0;
					
					foreach( $the_meta as $key => $value )
					{
						$iter++;
						
						echo '<a href="'.admin_url('post.php?post='.$value ).'&action=edit">'.get_the_title( $value )."</a>";
						
						if( $iter < count( $the_meta ) )
							echo ", ";
					}
				}
                
                break;
			
			case "form_id":
				
				if( $the_meta )
				{
					echo '<a href="'.admin_url('post.php?post='.$the_meta ).'&action=edit">'.get_the_title( $the_meta ).'</a>';
				}
				
				break;
			
            case "date":
            
                $productData = get_post( $the_meta );
                
                echo $productData->post_title;
                
                break;
        }
    }
    
    
}
?>