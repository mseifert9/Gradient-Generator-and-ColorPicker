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
	    .cw50{
		width: 50%;
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
	<link rel="stylesheet" type="text/css" href="<?php echo realurl(STATIC_CSS_COMMON) . '/mseifert-common.min.css.php?static-img-common=' . realurl(STATIC_IMG_COMMON) . '&static-site-root=' . realurl(STATIC_SITE_ROOT) . '&static-js-common=' . realurl(STATIC_JS_COMMON) ?>">
	
	<!-- supporting functions - TODO: remove those that are not used by this demo -->
	<script src="<?php echo STATIC_JS_COMMON ?>/mseifert.js"></script>

	<script>
	    /*  VERSION CHECKING of js files
	     *	To turn on optional javascript file version checking
	     *	1) uncomment the code below => $ms.sourceFiles.doVersionChecking( ...
	     *	    This will make sure the browser cache has the newest js versions
	     *	2)  replace the two js definitions in this file
	     *		src="<?php echo STATIC_JS_COMMON ?>/mseifert.min.js">
	     *		src="<?php echo STATIC_JS_COMMON ?>/colorpicker/gradient.js"
	     *	    with
	     *		src="<?php echo version(STATIC_JS_COMMON, "/mseifert.min.js") ?>"
	     *		src="<?php echo version(STATIC_JS_COMMON, "/colorpicker/gradient.js") ?>"
	     */
		
	    /* check file times to manage js file versions for dynamically loaded files (files not explicitly loaded by php)
	     * requires moddata.php be installed in the root of the project
	     * requires .htacess RewriteRule be added to filter out the timestamp in the filenames (see attached .htaccess file)
	     * see github project for furhter information: https://github.com/mseifert9/Javascript-Dynamic-Loading-and-Version-Control
	     */	    
	    /*
	    $ms.sourceFiles.doVersionChecking([
		// specify url of directories to read file times for
		$ms.STATIC_JS_COMMON,
		$ms.STATIC_JS_COMMON + "/colorpicker"
	    ]);
	    */
	</script>
	
	<!-- if you wish to load only the ColorPicker without the gradient generator, substitute colorpicker.js for gradient.js below -->
	<script src="<?php echo STATIC_JS_COMMON ?>/colorpicker/gradient.js"></script>

<!--
	    only two javascript files need be explicitly loaded:
	        mseifert.js
	        gradient.js (or colorpicker.js - if only the colorpicker is desired)
	    All dependent javascript files will be loaded automatically
	    If you prefer to manually load all js files include the files below
	    Plus you should also preload the default color map: map-hue.png
	    <script src="<?php echo STATIC_JS_COMMON ?>/localdata.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/localfile.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/editable-combo.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/custom-dialog.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/dragdrop.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/draggable.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/droppable.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/slider.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/colorpicker/colorlibrary.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/colorpicker/colormethods.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/colorpicker/colorvaluepicker.js"></script>
	    <script src="<?php echo STATIC_JS_COMMON ?>/colorpicker/colorpicker.js"></script>
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