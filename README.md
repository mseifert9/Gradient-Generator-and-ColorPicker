# ColorPicker & Gradient Generator
**MS ColorPicker** is a javaScript color picker that supports rgb, hsv, hsl, hex, CSS color names and alpha. It does not rely on any external library dependencies. It is based on the original work of John Dyer but has been rewritten to remove the Prototype library and extended significantly. 
This library is not particularly lightweight, although it is possible to easily remove quite a few of the feature and source files (e.g. the library files). I wanted a library that I could use to do serious color work and provide a way to save my color and gradient selections for consistency across all my websites.
These demos include functionality to optimized loading. Only two source files need be loaded up front. All the other will load independently. See my GitHub library for Javascript-Dynamic-Loading-and-Version-Control for documentation.
## Overview
### ColorPicker
- The Color Picker uses the familiar Photoshop-like interface.
- Colors may be chosen using rgb, rgba, hsv (hsb), hex, hsl, hsla, and CSS color names.
- Support for hsl has been added using color maps
- The ColorPicker will update specified source elements real time. It is optimized so that more than 500 independent elements can be updated without any noticeable lag.
- The color picker is integrated with and independent of Gradient Generator. It uses the same interface allowing a seemless integration between the two.
- It supports saving, exporting and importing color swatches to localstorage.
- Movable using a custom drag drop library.
- Open multiple instances of the ColorPicker.
- Use the arrows keys to change the value of any numerical field.
- Pinnable to the Gradient Generator

### Gradient Generator
- Generate gradients using rgb, rgba, hsl, hsla, and hex.
- Familiar Photoshop-like interface.
- Generate linear gradients specifying side, corner or angle.
- Generate radial gradients.
- Generate gradients with multiple transparent gradient layers. The order of the layers can be rearranged. The preview tab will show the complete gradient, while the gradient bar shows the current selected layer.
- Generate PNG files and download for any gradient, preserving transparency. This involves translating between CSS gradients and canvas gradients - not a trivial task.
- Generate CSS for Mozilla, Webkit, W3C and legacy IE format.
- Save generated gradients to localstorage and arrange gradients in libraries. Saved gradients can be exported to a text file for backup and later importing - or importing into a different browser. - Re-importing is necessary if the browser data cache is reset.
- Import gradients from CSS using Mozilla, Webkit, or W3C format.
- Update specified source elements real time with the current gradient.
- Movable using a custom drag drop library.
- Open multiple instances of the gradient generator.
- Use the arrows keys to change the value of any numerical field.
- Option to reverse the order of the color stops
## Demos
**gradient-demo.php** is actually two demos in one and contains most of the commonly used features.
In the following demos, a minimum of two elements are updated by the ColorPicker  and Gradient Generator: The "target" is the "true" destination element. Also included is an optional div (it can be an input or label) to receive a text version of the color or gradient.
1. **Simple Click to Open Demo** creates one div as a "front end." This can be an input, label, or div (as in this demo). This div will contain both the color chosen and the css string of the chosen color (or gradient). The Demo also gives a "target" div which the color is set for. An option is given to add as many additional divs as desired to test performance.
This fully functional demo of the ColorPicker and Gradient Generator is available at http://design.mseifert.com/index.php?topid=1&grade=Gradient&topid=1
2. **Dynamic Color Input Demo** creates a custom input control with a clickable arrow to open the tool. The input is automatically linked to the ColorPicker and Gradient Generator in a two way fashion. If a color is typed into the input, it is reflected immediately in the input as well as in any open tool. Supported formats for typed in text include Hex, RGB, and CSS Color Names.
**gradient-demo-simple.php** includes the minimal features to get going and includes only the Click to Open demo from above.
## Basic Documentation
MS ColorPicker and Gradient Generator run within all major browsers from IE9+. Tested in Firefox, Chrome, Opera, Safari for Windows, Internet Explorer.
### Implementation
The following code is from the simple demo.

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
The following HTML will open the ColorPicker or Gradient Generator (CSS styling is needed)
```
	<div onclick="openSelectedTool(this, 'target')"></div>
	<div id="target"></div>
```
The following settings are configurable and can be passed to the ColorPicker or Gradient Generator
```
imgPath: 		// defaults to "/js-common/colorpicker/img",
startMode		// defaults to hue (from hsv)
startColor		// colors can be passed in the supported formats. 
//A passed gradient will automatically open the gradient generator
startPos		// starting position on the screen
target			// an array of elements to update when values change
fontSize		// a font size in percent to help adjust to a site's css framework
zIndex			// defaults to 1000,
container		// allows a custom container
cbClose		// callback when closed
cbChange		// callback when color changed
cbCreate		// callback when ColorPicker is created
// for gradient
pin			// default: false, true = show the pin for pinning to the gradient generator
startPinned		// default: false, true = start pinned to the gradient generator when open
allowGradient		// allow switching to the Gradient Generator
gradient		// holds an instance of the Gradient Generator
fromGradient		// flags when opened from the Gradient Generator
minimal		// does nothing – considered having a flag to remove the color library
```
### The Files:
With the exception of gradient.js, all files originated from John Dyer's library. The Gradient Generator was written by me from the ground up except for the regex which parses the gradients. The parsing regex is from Dean Taylor from this StackOverflow post: 
https://stackoverflow.com/questions/20215440/parse-css-gradient-rule-with-javascript-regex
###### colorPicker.js 
 - defines the ColorPicker class
 - creates the user interface and controls the workflow for the ColorPicker
###### gradient.js is an all in one file for the Gradient Generator
 - defines the Gradientclass
###### colorvaluepicker.js 
 - defines the ColorValuePicker class
 - creates and processes events for the data entry inputs and radio buttons.
###### colormethods.js
 - defines the Color class
	keeps the values for the current selected color
 - defines the colorMethods class
	contains all the calculations and conversion functions
###### colorlibrary.js
 - allows for saving of chosen colors (ColorPicker) or gradients (Gradient Generator)
###### localdata.js
 - defines the LocalData class
 - support for colorlibrary.js to save libraries to localstorage.
###### localfile.js
 - defines the LocalFile class
 - support for colorlibrary.js to export and import libraries to text files.
###### slider.js
 - defines the Slider class
 - creates the sliders for the ColorPicker maps as well as sliders for numerical input fields (e.g. opacity)
###### custom-dialog.js
 - defines the CustomDialog class
 - handles custom user input prompts
###### editable-combo.js
 - defines the EditableCombo class
 - a custom editable combox element used by colorlibrary.js
###### dragdrop.js
 - defines the DragDrop class
 - support for drag and drop of elements
###### draggable.js
 - defines the Draggable class
 - makes elements draggable
###### droppable.js
 - defines the Droppable class
 - makes elements drop targets –able to receive draggable elements

