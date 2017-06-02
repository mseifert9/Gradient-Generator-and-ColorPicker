<?php ob_start(); ?>
<!DOCTYPE html>
<html>
    <head>	
	<style>
	    html {
		height: 100% !important;
	    }
	    body {
		min-height: 100%;
	    }
	    #title{
		font-size: 120%;
	    }
	    .chosen-color{
		width: 380px;
		height: 36px;
		border: 1px solid black;
		margin: 5px 0 10px 5px;
		background: white;
		overflow-y: auto;
	    }
	    #demo{
		width:400px;
		border: 1px solid black;
		padding: 5px;
		display: inline-block;
		vertical-align: top;
		background-color: rgb(240,240,240);
	    }
	    #demo1{
		border: 3px groove gray;
		margin: 2px 0;
	    }
	    #target{
		height:275px;
		width:275px;
		border: 1px solid black;
		display: inline-block;
		padding: 5px;
	    }
	</style>
	<script>
	    // set up namespace both in js and php
	    // the only globals placed in the namespace wiil be:
	    //	    com.meifert, $msRoot, $ms
	    //	    the openSelectedTool function defined in this file
	    var com = com || {};
	    $msRoot = com.mseifert = {common: {}};
	    $ms = $msRoot.common;
	    
	    // set up path vars
	    var root = window.location.origin ? window.location.origin : window.location.protocol + '//' + window.location.host;
	    // one installation on the server can serve multiple domains
	    // server may have multiple domains (SITEs) - 
	    //	    TOP = uppermost domain in the domain tree (it can be the same as SITE)
	    //	    SITE = the domain in the domain tree which this php is called from
	    // STATIC can be a cookieless domain different from root - for this example, it will be the same
	    $ms.STATIC_TOP_ROOT = root;			    // STATIC = cookieless domain address, TOP = top most domain on server, ROOT = root directory of TOP domain
	    $ms.STATIC_SITE_ROOT = root;		    // STATIC = cookieless domain address, SITE = any domain in the tree, ROOT = root directory of SITE domain
	    $ms.STATIC_IMG_COMMON = root + "/img-common";   // directory of images common to all SITEs
	    $ms.STATIC_JS_COMMON = root + "/js-common";	    // directory of javascript files common to all SITEs
	    $ms.STATIC_CSS_COMMON = root + "/css-common";   // directory of CSS files common to all SITEs
	</script>
	<?php
	    $root = "http://" . (isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : (isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : '.'));
	    define("STATIC_TOP_ROOT", $root);
	    define("STATIC_SITE_ROOT", $root);
	    define("STATIC_JS_COMMON", $root . "/js-common");
	    define("STATIC_CSS_COMMON", $root . "/css-common");
	    define("STATIC_IMG_COMMON", $root . "/img-common");
	?>
	<!-- the css file is in php format so that image path information can be passed to it -->
	<link rel="stylesheet" type="text/css" href="<?php echo STATIC_CSS_COMMON . '/mseifert-common.css.php?static-img-common=' . STATIC_IMG_COMMON . '&static-site-root=' . STATIC_SITE_ROOT . '&static-js-common=' . STATIC_JS_COMMON ?>">
	
	<!-- only the following two javascript files need be explicitly loaded
	All dependent javascript files will be loaded automatically
	mseifert.js contains supporting functions (TODO: remove those that are not used by this demo) -->
	<script src="<?php echo STATIC_JS_COMMON ?>/mseifert.js"></script>
	
	<!-- if you wish to load only the ColorPicker without the Gradient Generator, substitute colorpicker.js for gradient.js below -->
	<script src="<?php echo STATIC_JS_COMMON ?>/colorpicker/gradient.js"></script>

	<script>
	    function openSelectedTool(input, targetId) {
		var source = $ms.$(targetId);
		
		var property = "background";
		var startColor = "";
		var tool = "colorpicker";
		if (input.style.backgroundImage.indexOf("gradient") !== -1) {
		    tool = "gradient";
		    startColor = input.style.backgroundImage;
		} else if (input.style.backgroundColor.length > 0) {
		    startColor = input.style.backgroundColor;
		} else {
		    startColor = input.style.background;
		}
		
		// target can accomodate any number of elements (within reason :)
		var targetElements = [
		    {element: source, property: property},
		    {element: input, property: property, valueAttribute: "innerHTML", textColor: "contrast"}
		];
		var settings = {
		    minimal: false,
		    target: targetElements,
		    startColor: startColor,
		    allowGradient: true	    // show "Add Gradient" option in color picker, If the source has a gradient, will open the gradient tool automatically
		}
		if (tool == "gradient"){
		    new $msRoot.Gradient(settings);
		} else {
		    new $msRoot.ColorPicker(settings);
		}
	    }

	</script>
    </head>

    <div id="demo">
	<div id="title" class="gradient ms-title-bar">
	    MS Gradient Generator Demo (Simple)
	</div>
	<div id="demo1">
	    <h2>Click Below To Open the<br>ColorPicker or Gradient Generator</h2>
	    <div id="open-on-click" class="chosen-color"  onclick="openSelectedTool(this, 'target')"></div>
	</div>
    </div>
    <div id="target"></div>
</html>