<html>
	<head>
		<title>Rich Text Editor testing</title>
		<!-- Skin CSS file -->
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/assets/skins/sam/skin.css">
		<!-- Utility Dependencies -->
		<script src="http://yui.yahooapis.com/2.9.0/build/yahoo-dom-event/yahoo-dom-event.js"></script> 
		<script src="http://yui.yahooapis.com/2.9.0/build/element/element-min.js"></script> 
		<!-- Needed for Menus, Buttons and Overlays used in the Toolbar -->
		<script src="http://yui.yahooapis.com/2.9.0/build/container/container_core-min.js"></script>
		<!-- Source file for Rich Text Editor-->
		<script src="http://yui.yahooapis.com/2.9.0/build/editor/simpleeditor-min.js"></script>
		<script>
var myEditor = new YAHOO.widget.SimpleEditor('msgpost', {
    height: '200px',
    width: '385px',
    dompath: false,
    animate: true,
    toolbar: {
        titlebar: 'My Editor',
        buttons: [
            { group: 'textstyle', label: 'Font Style',
                buttons: [
                    { type: 'push', label: 'Bold', value: 'bold' },
                    { type: 'push', label: 'Italic', value: 'italic' },
                    { type: 'push', label: 'Underline', value: 'underline' },
                    { type: 'separator' },
                    { type: 'select', label: 'Arial', value: 'fontname', disabled: true,
                        menu: [
                            { text: 'Arial', checked: true },
                            { text: 'Arial Black' },
                            { text: 'Comic Sans MS' },
                            { text: 'Courier New' },
                            { text: 'Lucida Console' },
                            { text: 'Tahoma' },
                            { text: 'Times New Roman' },
                            { text: 'Trebuchet MS' },
                            { text: 'Verdana' }
                        ]
                    },
                    { type: 'spin', label: '13', value: 'fontsize', range: [ 9, 75 ], disabled: true },
                    { type: 'separator' },
                    { type: 'color', label: 'Font Color', value: 'forecolor', disabled: true },
                    { type: 'color', label: 'Background Color', value: 'backcolor', disabled: true }
                ]
            }
        ]
    }
});
myEditor.render();
		</script>
	</head>
	<body class="yui-skin-sam">
		<textarea name="msgpost" id="msgpost" cols="50" rows="10">
    	<strong>Your</strong> HTML <em>code</em> goes here.<br>
   		This text will be pre-loaded in the editor when it is rendered.
		</textarea>
	</body>
</html>