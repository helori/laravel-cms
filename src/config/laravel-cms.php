<?php

return [
    
	/*
	* All the options will be converted to javascript and merge to default TinyMCE editor options.
	* Check TinyMCE 4 config options more more details.
	*/

    'editorOptions' => [
    	
    	'toolbar' => 'undo redo | bold italic | forecolor backcolor | styleselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | blockquote | code',
    	
    	'textcolor_map' => [
    		"000000", "Black",
    		"FFFFFF", "White",
    	],
    ]
    
];
