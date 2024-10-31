<?php
require_once( dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))).'/wp-load.php' );

if( !$qodys_pinner )
{
	echo "Must activate Qody's Pinner";
	exit;
}

$fields = array();
$fields['numberposts'] = -1;
$fields['post_type'] = $qodys_pinner->GetClass("posttype_amazon-campaign")->m_type_slug;
$fields['orderby'] = 'rand';
$fields['post_status'] = 'publish';

$data = get_posts( $fields );

if( !$data )
	exit;

foreach( $data as $key => $value )
{
	$qodys_pinner->GetClass('posttype_amazon-campaign')->Process( $value );
}

echo "done processing";
?>