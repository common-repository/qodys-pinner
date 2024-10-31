<?php
/***************************************************************************
 *	Define any page-specific variables used in the theme
 ***************************************************************************/
$description_text = $this->GetFormattedContentText( $custom['product_description'][0] );
$product_link = $custom['product_url'][0];
$product_image_url = $custom['product_image'][0];
$product_title = $custom['product_title'][0];
$product_price = $custom['product_price'][0];


/***************************************************************************
 *	Fetch the user-designed themed version of the content
 ***************************************************************************/
$themed_content = $this->LoadThemedContent( 'pinnable-post.php' );


/***************************************************************************
 *	Evaluate the code in this namespace to have access to variables defined above
 ***************************************************************************/
ob_start();
eval( '?>'.$themed_content.'<php ?>' );
$new_content = ob_get_contents();
ob_end_clean();


/***************************************************************************
 *	Piece together the new content to show in the_content() function
 ***************************************************************************/
$content .= $notifications;
$content .= $new_content;
?>