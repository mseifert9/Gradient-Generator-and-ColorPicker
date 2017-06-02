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
	<?php 
	    // common.php 
	    //	    defines php path constants and js path variables
	    //	    creates the js namespace where the paths are stored
	    //	    contains basic error checking code - error.log in the demo directory will contain php errors
	    include "common.php" ;
	?>
	
	<!-- the css file is in php format so that image path information can be passed to it -->
	<link rel="stylesheet" type="text/css" href="<?php echo realurl(STATIC_CSS_COMMON) . '/mseifert-common.css.php?static-img-common=' . realurl(STATIC_IMG_COMMON) . '&static-site-root=' . realurl(STATIC_SITE_ROOT) . '&static-js-common=' . realurl(STATIC_JS_COMMON) ?>">
	
	<!-- only the following two javascript files need be explicitly loaded
	All dependent javascript files will be loaded automatically
	mseifert.js contains supporting functions (TODO: remove those that are not used by this demo) -->
	<script src="<?php echo STATIC_JS_COMMON ?>/mseifert.min.js"></script>
	
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