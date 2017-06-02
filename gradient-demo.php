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
	    .cp-input{
		margin: 5px 0 10px 5px;
	    }
	    #demo{
		width:400px;
		border: 1px solid black;
		padding: 5px;
		display: inline-block;
		vertical-align: top;
		background-color: rgb(240,240,240);
	    }
	    #demo1, #demo2{
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
	    #reset{
		position: absolute;
		bottom: 0;
		left: 200px;
	    }
	    input[type="radio"] + label {
		color:black;
		font-family:Arial, sans-serif;
		display: inline-block;
	    }

	    input[type="radio"] + label span {
		display:inline-block;
		width:19px;
		height:19px;
		margin:-2px 10px 0 0;
		vertical-align:middle;
		cursor:pointer;
	    }

	    input[type="radio"]:checked + label span {
		/* background: ; */
	    }    
	    .cw50{
		width: 50%;
	    }
	</style>
	<script>
	    // set up namespace both in js and php
	    // the only globals placed in the namespace wiil be:
	    //	    com.meifert, $msRoot, $ms
	    //		- com.mseifert can be renamed to anything which doesn't conflict with your environment
	    //		- $msRoot is a shortcut to com.mseifert
	    //		- $ms is a shortcut to com.mseifert.common
	    //	    the globals defined in this demo file (openTool, cpInput, cpInput)
	    //	    the functions defined in this demo file (openSelectedTool, createInput, resetForm, resetElements, createdivs)
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
	<link rel="stylesheet" type="text/css" href="<?php echo STATIC_CSS_COMMON . '/mseifert-common.min.css.php?static-img-common=' . STATIC_IMG_COMMON . '&static-site-root=' . STATIC_SITE_ROOT . '&static-js-common=' . STATIC_JS_COMMON ?>">
	
	<!-- supporting functions - TODO: remove those that are not used by this demo -->
	<script src="<?php echo STATIC_JS_COMMON ?>/mseifert.js"></script>
	
	<!-- if you wish to load only the ColorPicker without the gradient generator, substitute colorpicker.min.js for gradient.min.js below -->
	<script src="<?php echo STATIC_JS_COMMON ?>/colorpicker/gradient.min.js"></script>

<!--
	    only two javascript files need be explicitly loaded:
	        mseifert.min.js
	        gradient.min.js (or colorpicker.min.js - if only the colorpicker is desired)
	    All dependent javascript files will be loaded automatically
	    If you prefer to manually load all js files include the files below
	    Plus you should also preload the default color map: map-hue.png
	    <script src="<?php echo STATIC_JS_COMMON ?>/localdata.min.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/localfile.min.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/editable-combo.min.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/custom-dialog.min.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/dragdrop.min.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/draggable.min.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/droppable.min.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/slider.min.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/colorpicker/colorlibrary.min.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/colorpicker/colormethods.min.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/colorpicker/colorvaluepicker.min.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/colorpicker/colorpicker.min.js"></script>
-->

	<script>
	    var openTool = [],		// open tools from the Simple Click To Open
		    cpOpenTool = [],	// open tools from the Dynamic ColorPicker Input object
		    cpInput;		// the Dynamic ColorPicker Input object
	    function openSelectedTool(input, targetId) {
		if (openTool.length > 0){
		    return;
		}
		var status = $ms.$("status");
		if (targetId == "body") {
		    var source = document.body;
		} else {
		    var source = $ms.$(targetId);
		}
		var tool = document.querySelector('input[name="radio"]:checked').value;
		var property = "background";
		var startColor = "";
		if (input.style.backgroundImage.indexOf("gradient") !== -1) {
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
		targetElements = targetElements.concat(additionalElements);  // if you've added elements for demo purposes, this will sync their colors as well
		var settings = {
		    minimal: false,
		    target: targetElements,
		    startColor: startColor,
		    allowGradient: true, // show "Add Gradient" option in color picker, If the source has a gradient, will open the gradient tool automatically
		    cbClose: function (tool) {
			status.innerHTML = "cbClose " + tool.id;
			for (var i = 0; i < openTool.length; i++){
			    if (openTool[i].id == tool.id){
				openTool.splice(i, 1);
				break
			    }
			}
		    },
		    cbCreate: function (tool) {
			var value;
			status.innerHTML = "cbCreate " + tool.id;
			openTool.push(tool);
		    }
		}
		if (tool == "gradient") {
		    new $msRoot.Gradient(settings);
		} else {
		    new $msRoot.ColorPicker(settings);
		}
	    }

	    function createInput(containerId, targetId) {
		if ($ms.$("cp-input-div"))
		    return;
		var inputContainer = $ms.$(containerId);
		var source = $ms.$(targetId);
		var tool = document.querySelector('input[name="radio"]:checked').value;
		var property = "background";
		var status = $ms.$("status");
		// target can accomodate any number of elements (within reason :)
		var settings = {
		    colorPicker: undefined, // set a reference to the tool when it is created
		    gradient: undefined,
		    target: [],			// we can pass an array of targets (or a blank array)
						// since we are passing an array
						// we can dynamically add to the list of elements to update  at any point 
		    element: source,		// IF we pass an empty array for target, 
						// we can specify the element which will be automatically added to the target array
		    elementProperty: property,	// and we must also pass the property to update
						// NOTE: the dynamically created input is automatically added to the target list
		    parentNode: inputContainer,
		    inputClassName: "",
		    divClassName: "cp-input",
		    inputWidth: "300px",
		    tool: [],		// array of tools open (gradient / colorpicker) - dynamic array so can referene it
		    openGradient: tool == "gradient" ? true : false,
		    cbClose: function (tool) {
			status.innerHTML = "Callback cbClose " + tool.id;
			// note: settings.tool is maintained by createColorPickerInput
		    },
		    cbChange: function (tool, color) {
			var value = "";
			// change can be from the input alone without tool open
			if (!tool)
			    return;
			// live tool could be either since can switch between the two
			if (tool.id.indexOf("gradient") !== -1) {
			    if (color) {
				value = color.noPrefix;	    // unprefixed gradient string
			    }
			} else {
			    if (color) {
				value = color.rgbString;
			    }
			}
			status.innerHTML = "Callback cbChange: " + value;
		    },
		    cbCreate: (function (tool) {
			status.innerHTML = "Callback cbCreate " + tool.id;
			// note: settings.tool is maintained by createColorPickerInput
		    })
		};
		cpOpenTool = settings.tool;	// copy of array maintained by createColorPickerInput
		cpInput = $ms.createColorPickerInput(settings);
		cpInput.div.id = "cp-input-div";
	    }

	    function resetForm() {
		for (var i = 0; i < openTool.length; i++){
		    openTool[i].close();		    
		}
		openTool = [];
		for (var i = 0; i < cpOpenTool.length; i++){
		    cpOpenTool[i].close();		    
		}
		// remove the additionalElements from the list of elements the cpInput updates
		cpInput.settings.target.splice(2);
		
		$ms.$("input-div").innerHTML = "";
		createInput('input-div', 'target');
		$ms.$("target").style.background = "";
		$ms.$("target").style.backgroundImage = "";
		$ms.$("open-on-click").style.background = "";
		$ms.$("open-on-click").style.backgroundImage = "";
		$ms.$("open-on-click").value = "";
	    }
	    function resetElements(){
		for (var i = 0; i < openTool.length; i++){
		    openTool[i].close();		    
		}
		openTool = [];
		for (var i = 0; i < cpOpenTool.length; i++){
		    cpOpenTool[i].close();		    
		}
		for (var i = 0; i < additionalElements.length; i++){
		    additionalElements[i].element.parentNode.removeChild(additionalElements[i].element);
		}
		additionalElements = [];
		// remove the additionalElements from the list of elements the cpInput updates
		cpInput.settings.target.splice(2);
	    }
	    
	    var additionalElements = [];
	    function createdivs(){	
		var top = 300;
		var left = 425;
		var number = $ms.$('number-of-elements').value;
		for (var i = 0; i < number; i++){
		    var div = document.createElement("div");
		    div.style.height = "8px";
		    div.style.width = "8px";
		    div.style.top = top + "px";
		    div.style.left = left + "px";
		    div.style.border = "1px solid black";
		    div.style.position = "absolute";
		    document.body.appendChild(div);
		    additionalElements.push({element: div, property: "background"});
		    if (top > 650){
			top = 300;
			left += 12;
		    } else {
			top += 12;
		    }
		}
		// cpInput.settings.target is an array and so is passed by reference via the settings
		// to the createColorPickerInput object. 
		// Therefore  this dynamic array of elements will be auto updated by the colorpicker & gradient generator 
		// even though the elements were created after the input was created
		cpInput.settings.target = cpInput.settings.target.concat(additionalElements);
	    }

	</script>
    </head>

    <div id="demo">
	<div id="title" class="gradient ms-title-bar">
	    MS Gradient Generator Demo
	</div>
	<div style="position: relative">
	    <div>
		<input id="colorpicker-radio" type="radio" name="radio" value="colorpicker" checked="checked" onclick="resetForm()"><label for="colorpicker-radio"><span><span></span></span>Color Picker</label>
	    </div>
	    <div>
		<input id="gradient-radio" type="radio" name="radio" value="gradient" onclick="resetForm()"><label for="gradient-radio"><span><span></span></span>Gradient Generator</label>
	    </div>
	    <div>
		<input id="reset" type="button" value="Reset" onclick="resetForm(); resetElements()">
	    </div>
	    <div>
		<input id="number-of-elements" value="500" size="4">
		<input id="create-elements" type="button" value="Create Elements" onclick="resetElements(); createdivs()">
	    </div>
	</div>
	<div id="demo1">
	    <h1>Simple Click To Open</h1>
	    <div id="open-on-click" class="chosen-color"  onclick="openSelectedTool(this, 'target')"></div>
	    <p>Click the box above to set the background of the target div.</p>
	</div>

	<div  id="demo2">
	    <h1>Dynamic Color Input</h1>
	    <div id="input-div"></div>
	    <p>Click the button to the right of the input to set the background of the target div(s)</p>
	    <p>Or type a color into the input (rgb, hex, name, or gradient)</p>
	</div>
	<div>Callback Status:</div>
	<div id="status"></div>
    </div>
    <div id="target"></div>

    <script>
	$ms.setOnLoad($ms.$("demo"), function () {	    
	    var interval = setInterval(function() { 
		if (typeof $msRoot["colorMethods"] !== "undefined"){
		    createInput('input-div', 'target');
		    clearInterval(interval);
		    return;
		}		
	    }, 10)
	});
    </script>

</html>