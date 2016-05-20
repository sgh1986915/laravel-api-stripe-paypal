<?php require_once __DIR__."/CONFIG.php";
	$css_includes=array("<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"$app_root/css/custom-theme/jquery-ui-1.8.8.custom.css\"/>",
						"<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"$app_root/css/ui.jqgrid.css\"/>" 
						);

	$js_includes=array("<script src=\"$app_root/js/dev/i18n/grid.locale-en.js\" type=\"text/javascript\"></script>",
						"<script src=\"$app_root/js/dev/jquery.jqGrid.min.js\" type=\"text/javascript\"></script>"
	);
	$pages = array('right' => 'myinvoices_main.php');	
	include "template.php";
?>
