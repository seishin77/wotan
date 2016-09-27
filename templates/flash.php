<?php
$flash = core::getFlash();
if($flash['nbflash'] > 0){
?>
<div class="row">
	<div id="flashzone" class="col-lg-8">
<?php
foreach($flash as $k => $v){
	if($k != 'nbflash'){
		foreach($v as $m){
			echo '<div class="bg-', $k, '">', $m, '</div>', PHP_EOL;
		}
	}
}
?>
	</div>
</div>
<br/>
<?php
core::resetFlash();
}
