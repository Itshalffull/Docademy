DESCRIPTION:
-----------------
This module helps to convert multiple select lists into dropdown checkboxes using ddcl library.
It can be used to change difficult to explain multiple select lists into user friendly and elegant dropdown checkboxes.

Convert:
    multiple select lists into dropdown checkboxes
    single select lists into dropdown radio buttons

INSTALLATION:
------------------
 - Download and install Dropdown Checkboxes and Libraries API module.
 - Downlod DDCL library(version 1.3) and extract it in libraries directory.
[you can download it from here: http://code.google.com/p/dropdown-check-list/downloads]
 - Rename extracted directory to 'ddcl'.

USAGE:
------------------
 Method 1.
   - Get the id of the select list to be converted and add in the first textarea.
   - (Optional)For better performance and avoiding un-necessary file load on every page, enter the urls where select lists to be converted are present.
   - Clear cache.
   
 Method 2.
   - In your custom form, add a #pre_render element, its value being array("dropdown_checkboxes").
     // Example.
     $form['some_form'] = array(
	       '#type' => 'select',
		     '#multiple' => TRUE,
		     '#pre_render' => array('dropdown_checkboxes'),
		     '#options' => $options,
	     );
	 // Another example using hook_form_alter.
	 $form['some_form']['#pre_render'] = array('dropdown_checkboxes');

  Both the methods can be used along side. IDs of select lists created using method 2 DO NOT require to be added in method 1.  

TROUBLESHOOTING:
------------------
 - Make sure you are using v1.3 of DDCL library. There are known issues with v1.4
 - Verify you have added correct form ids.
 - In case you have defined specific urls to load js and css, ensure you have added all the urls those have configured form ids.
 - Check the ddcl library limitations at http://dropdown-check-list.googlecode.com/svn/trunk/doc/dropdownchecklist.html
 - Use module's issue queue on D.O


DEPENDENCIES:
-----------------
Libraries API
DDCL library

--
Initial development of this module was sponsored by Faichi solutions.
