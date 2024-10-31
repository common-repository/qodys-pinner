<?php
$cron_dir = $this->GetCronDir();
$cron_url = $this->GetCronUrl();
?>
<div class="row-fluid">
	<div class="span12">
		<p>Cron Command - <a target="_blank" href="<?php echo $cron_url; ?>">run it now</a></p>
		<p><pre>php <?php echo $cron_dir; ?></pre></p>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<p>If above command didn't work use this one</p>
		<p><pre>curl <?php echo $cron_url; ?></pre></p>
	</div>
</div>
