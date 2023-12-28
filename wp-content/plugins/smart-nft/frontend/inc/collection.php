<?php
class SmartNftCollection 
{

	function __construct() {
    add_filter( 'taxonomy_template',  array( $this, 'cpt_tax_template' )); 
	}

  function cpt_tax_template( $template ){
      $mytemplate = PLUGIN_ROOT . "taxonomy-smartnft_collection.php";
  
      if( is_tax( 'smartnft_collection' ) && is_readable( $mytemplate ) ) {
          return $mytemplate;
      }

      return $template;
  }
}

new SmartNftCollection();
