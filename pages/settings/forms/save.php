<?php
require_once( dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))).'/wp-load.php' );

$response = array();

if( $_POST )
{
	if( !$_POST['use_pin_widget'] )
		$_POST['use_pin_widget'] = '';
	
	if( !$_POST['use_pin_overlay'] )
		$_POST['use_pin_overlay'] = '';
		
	foreach( $_POST as $key => $value )
	{
		$qodys_pinner->update_option( $key, $value );
	}
	
	$response['results'][] = 'Settings have been saved';
}
else
{
	$response['errors'][] = 'Any unexpected error occured; please try again';
}

$qodys_pinner->GetClass('postman')->SetMessage( $response );

$url = $qodys_pinner->GetClass('tools')->GetPreviousPage();

header( "Location: ".$url );
exit;
?>