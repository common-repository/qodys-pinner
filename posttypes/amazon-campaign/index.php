<?php
class qodyPosttype_PinnerCampaign extends QodyPostType
{
	 function __construct()
    {
        $this->SetOwner( func_get_args() );
		
		$this->m_raw_file = __FILE__;
		
		$this->SetPriority( 4 );
        
        $this->m_show_in_menu = $this->GetPre().'-home.php';
        
        $this->m_supports[] = 'title';
        $this->m_supports[] = 'editor';
        //$this->m_supports[] = 'thumbnail';
        //$this->m_supports[] = 'excerpt';
        $this->m_supports[] = null;
		
		//$this->m_rewrite = array( 'slug' => 'form-preset' );
        
        $this->SetMassVariables( 'amazon campaign', 'amazon campaigns', true );
		
		//$this->m_list_columns['cb'] = '<input type="checkbox" />';
        //$this->m_list_columns['title'] = 'Name';
		//$this->m_list_columns['form_id'] = 'Optin Form';
		//$this->m_list_columns['list_id'] = 'Email Lists';		
        //$this->m_list_columns['shortcode'] = 'Shortcode';
        //$this->m_list_columns['embed_code'] = 'Embed Code';
        //$this->m_list_columns['direct_link'] = 'Direct Link';
		//$this->m_list_columns['raw_link'] = 'Raw Link';
        
        parent::__construct();
    }
	
    function WhenViewingPostList()
    {
		if( !parent::WhenViewingPostList() )
			return;
			
		$this->EnqueueStyle( 'nicer-tables' );
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
		$this->AddMetabox( 'general', 'Settings' );
    }
    
	function GetAmazonCategories()
	{
		$data = array();
		$data['Apparel'] = 'Apparel';
		$data['Appliances'] = 'Appliances';
		$data['ArtsAndCrafts'] = 'Arts And Crafts';
		$data['Automotive'] = 'Automotive';
		$data['Baby'] = 'Baby';
		$data['Beauty" selected="selected'] = 'Beauty';
		$data['Blended'] = 'Blended';
		$data['Books'] = 'Books';
		$data['Classical'] = 'Classical';
		$data['DigitalMusic'] = 'Digital Music';
		$data['DVD'] = 'DVD';
		$data['Electronics'] = 'Electronics';
		$data['ForeignBooks'] = 'Foreign Books';
		$data['Garden'] = 'Garden';
		$data['GourmetFood'] = 'Gourmet Food';
		$data['Grocery'] = 'Grocery';
		$data['HealthPersonalCare'] = 'Health Personal Care';
		$data['Hobbies'] = 'Hobbies';
		$data['Home'] = 'Home';
		$data['HomeGarden'] = 'Home Garden';
		$data['HomeImprovement'] = 'Home Improvement';
		$data['Industrial'] = 'Industrial';
		$data['Jewelry'] = 'Jewelry';
		$data['KindleStore'] = 'Kindle Store';
		$data['Kitchen'] = 'Kitchen';
		$data['LawnAndGarden'] = 'Lawn And Garden';
		$data['Lighting'] = 'Lighting';
		$data['Magazines'] = 'Magazines';
		$data['Marketplace'] = 'Marketplace';
		$data['Miscellaneous'] = 'Miscellaneous';
		$data['MobileApps'] = 'Mobile Apps';
		$data['MP3Downloads'] = 'MP3 Downloads';
		$data['Music'] = 'Music';
		$data['MusicalInstruments'] = 'Musical Instruments';
		$data['MusicTracks'] = 'Music Tracks';
		$data['OfficeProducts'] = 'Office Products';
		$data['OutdoorLiving'] = 'Outdoor Living';
		$data['Outlet'] = 'Outlet';
		$data['PCHardware'] = 'PC Hardware';
		$data['PetSupplies'] = 'Pet Supplies';
		$data['Photo'] = 'Photo';
		$data['Shoes'] = 'Shoes';
		$data['Software'] = 'Software';
		$data['SoftwareVideoGames'] = 'Software Video Games';
		$data['SportingGoods'] = 'Sporting Goods';
		$data['Tools'] = 'Tools';
		$data['Toys'] = 'Toys';
		$data['UnboxVideo'] = 'Unbox Video';
		$data['VHS'] = 'VHS';
		$data['Video'] = 'Video';
		$data['VideoGames'] = 'Video Games';
		$data['Watches'] = 'Watches';
		$data['Wireless'] = 'Wireless';
		$data['WirelessAccessories'] = 'Wireless Accessories';
		
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
			$this->Log( 'Missing the Amazon public key; canceling Amazon link search', 'error' );
			return;
		}
		else if( !$amazonSecret )
		{
			$this->Log( 'Missing the Amazon secret key; canceling Amazon link search', 'error' );
			return;
		}
		else if( !$amazonAid )
		{
			$this->Log( 'Missing the Amazon associate id; canceling Amazon link search', 'error' );
			return;
		}
		
		$page_history = maybe_unserialize( $custom['page_history'] );
		
		// check if we've run out of links to get for this keyword
		if( $page_history[ $keyword ] == -1 )
		{
			$this->Log( 'ran out of pages to search through for keyword "'.$keyword.'"' );
			return;
		}
		
		$page = $page_history[ $keyword ];
		
		if( !$page )
			$page = 1;
		
		require_once( $this->GetFrameworkDir().'/includes/apis/amazon/index.php' );
		
		$amazon_api = new AmazonProductAPI( $amazonPublic, $amazonSecret, $amazonAid );
		
		try
		{
			$result = $amazon_api->searchProducts( $keyword, $page, $custom['amazon_product_category'], "TITLE" );
		}
		catch(Exception $e)
		{
			$this->Log( 'product search error: '.$e->getMessage(), 'error' );
			
			return;
		}
		
		if( count( $result->Items->Item ) > 0 )
		{
			$this->Log( count( $result->Items->Item ).' items found for keyword "'.$keyword.'"' );
			
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
			
			$this->Log( 'setting page history for "'.$keyword.'" to '.($page+1) );
			$page_history[ $keyword ] = $page + 1;
		}
		else
		{
			$this->Log( 'no products found for keyword "'.$keyword.'"' );
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
		{
			$this->Log( 'post id #'.$post->ID.' didnt have any keywords to process... skipping', 'error' );
			return;
		}
		
		shuffle( $keyword_bits );
		
		foreach( $keyword_bits as $key => $value )
		{
			$value = trim( $value );
			
			if( !$value )
				continue;
			
			$amazon_links = $this->FetchAmazonLinks( $value );
			
			if( !$amazon_links )
			{
				$this->Log( 'no queued links; fetching more for '.$value );
				
				$this->FindAndStoreNewAmazonLinks( $value, $post );
				
				$amazon_links = $this->FetchAmazonLinks( $value );
			}
			
			if( !$amazon_links )
			{
				$this->Log( 'no links found for '.$value );
				continue;
			}
			
			foreach( $amazon_links as $key2 => $value2 )
			{
				$post_title = $this->FilterProductShortcodes( $custom['custom_post_title'], $value2 );
				
				$post_content = '';
				
				$fields = array();
				$fields['post_title'] = $post_title;
				$fields['post_type'] = $this->GetClass('posttype_pinnable-post')->m_type_slug;
				$fields['post_content'] = $post_content;
				$fields['post_status'] = $custom['new_post_status'] ? $custom['new_post_status'] : 'publish';
				$fields['post_author'] = 1;
				
				$post_id = wp_insert_post( $fields );
				
				if( $post_id )
				{
					$this->Log( 'created new post - '.$this->FilterProductShortcodes( $post_title, $value2 ) );
					
					$this->update_post_meta( $post_id, 'product_url', $value2['url'] );
					$this->update_post_meta( $post_id, 'product_title', $value2['title'] );
					$this->update_post_meta( $post_id, 'product_keyword', $value2['keyword'] );
					$this->update_post_meta( $post_id, 'product_description', $value2['description'] );
					$this->update_post_meta( $post_id, 'product_price', $value2['price'] );
					$this->update_post_meta( $post_id, 'product_image', $value2['image'] );
					
					$pin_text = $this->FilterProductShortcodes( $custom['pinterest_text'], $value2 );
					
					$this->PostToPinterest( $post_id, $pin_text, $custom['pinterest_url'] );
				}
				else
				{
					$this->Log( 'failed to create post', 'error' );
				}
				
				$fields = array();
				$fields['status'] = 1;
				
				$this->GetClass('db')->UpdateDatabase( $fields, 'amazon_links', $value2['id'], 'id', $this );
			  
				break; // just process one link
			}
			
			break; // just process one keyword
		}
	}
	
	function FilterProductShortcodes( $content, $value )
	{
		$content = str_replace( '[product_title]', $value['title'], $content );
		$content = str_replace( '[product_price]', $value['price'], $content );
		$content = str_replace( '[product_url]', $value['url'], $content );
		$content = str_replace( '[product_description]', $value['description'], $content );
		
		return $content;
	}
	
	function PostToPinterest( $post_id, $pin_text, $pin_url )
	{		
		$custom = $this->get_post_custom( $post_id );
		
		// fetch api class from framework
		require_once( $this->GetFrameworkDir().'/includes/apis/pinterest/index.php' );
		
		// create pinterest api instance
		$pinterest_api = new QodyPinterestApi();	
		$pinterest_api->m_caller = $this;
		
		$pinterest_api->Login( $this->get_option('pinterest_login_email'), $this->get_option('pinterest_login_password') );
		
		// setup pin content
		$fields = array();
		$fields['board'] = $this->GetPinBoardId( $pinterest_api, $pin_url, $post_id, $custom );
		$fields['details'] = strlen($pin_text) > 450 ? substr( $pin_text, 0, 450 ).'...' : $pin_text;
		$fields['img_url'] = $custom['product_image'];
		$fields['link'] = get_permalink( $post_id );
		
		// perform pinning action
		$result = $pinterest_api->Pin( $fields );
		$result = json_decode( $result );
		
		if( $result->error )
		{
			$this->Log( $result->error, 'error' );
		}
	}
	
	function GetPinBoardId( $pinterest_api, $pin_url, $post_id, $custom )
	{		
		$pinboard_ids_by_url = maybe_unserialize( $custom['pinboard_ids_by_url'] );
		
		if( is_numeric( $pinboard_ids_by_url[ $pin_url ] ) )
		{
			$board_id = $pinboard_ids_by_url[ $pin_url ];
		}
		else
		{
			$board_id = $pinterest_api->GetBoardIdFromUrl( $pin_url );
			
			$pinboard_ids_by_url[ $pin_url ] = $board_id;
			
			update_post_meta( $post_id, 'pinboard_ids_by_url', $pinboard_ids_by_url );
		}
		
		return $board_id;
	}
	
    function DisplayListColumns( $column )
    {
		global $post;
		
		$post_id = $post->ID;
		
		$custom = $this->get_post_custom( $post_id );
        $the_meta = get_post_meta( $post_id, $column, true);
        
        switch( $column )
        {
            case "date":
            
                $productData = get_post( $the_meta );
                
                echo $productData->post_title;
                
                break;
        }
    }
    
    
}
?>