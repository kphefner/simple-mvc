<div style="padding:1rem;color:black;background:white;">
	<h2>We're Sorry</h2>
	<p>The following unexpected error has occurred.</p>
	<p style="color:red;"><?php echo (($this->error)?$this->error:'Unknown Error'); ?></p>
	<p>Please notify support by emailing <a href="mailto:<?php echo $GLOBALS['CONFIG']['webmaster_email'];?>">Webmaster</a>.</p>
	<p>Thank you</p>
</div>