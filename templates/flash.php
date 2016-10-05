<?php
$flash = core::getFlash();
if($flash['nbflash'] > 0){
?>
<div class="row">
	<div id="flashzone" class="col-lg-12">
<?php
foreach($flash as $k => $v){
	if($k != 'nbflash'){
		foreach($v as $m){
			echo '<div class="alert alert-', $k, 
					'"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>',
					$m, '</div>', PHP_EOL;
		}
	}
}
?>
	</div>
</div>
<?php
core::resetFlash();
}
