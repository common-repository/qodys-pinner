<?php
class qodyController_ShopperPinnablePost extends QodyContentController
{
	function __construct()
	{
		$this->SetOwner( func_get_args() );
		
		parent::__construct();
	}
	
	function WhenOnPage()
    {
		if( !parent::WhenOnPage() )
			return;
    }
}
?>