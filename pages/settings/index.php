<?php
class qodyPage_PinnerSettings extends QodyPage
{
	function __construct()
	{
		$this->SetOwner( func_get_args() );
		
		$this->m_raw_file = __FILE__;
		
		$this->SetPriority( 5 );
		
		$this->SetParent( 'home' );
		//$this->m_icon_url = '';
		
		$this->SetTitle( 'Settings' );
		
		parent::__construct();
	}
	
	function LoadMetaboxes()
	{
		$this->AddMetabox( 'settings', 'Settings' );
		$this->AddMetabox( 'widget', 'Widget Options' );
		$this->AddMetabox( 'overlay', 'Image Overlay Options' );
		$this->AddMetabox( 'cron', 'Cronjob' );
		
		$this->AddMetabox( 'save', 'Save Settings', 'side' );
	}
	
	function WhenOnPage()
	{
		if( !parent::WhenOnPage() )
			return;
		
		$this->EnqueueStyle('chosen');
		$this->EnqueueStyle( 'jquery-ui' );
		
		$this->EnqueueScript('chosen');		
		$this->EnqueueScript( 'jquery-ui' );		
	}
	
	function GetCronDir()
	{
		return $this->GetOverseer()->GetAsset( 'cron', 'run', 'dir' );
	}
	
	function GetCronUrl()
	{
		return $this->GetOverseer()->GetAsset( 'cron', 'run', 'url' );
	}
}
?>