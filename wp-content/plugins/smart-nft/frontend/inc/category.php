<?php
class Smartnft_Category_Page 
{
	function __construct() {
        add_filter( 'taxonomy_template',  array( $this, 'acpt_tax_template' ),100); 
	}

  function acpt_tax_template( $template ) {
      $mytemplate = PLUGIN_ROOT . "taxonomy-smartnft_category.php";
  
      if( is_tax( 'smartnft_category' ) && is_readable( $mytemplate ) ) {
          return $mytemplate;
      }

      return $template;
  }
}

new Smartnft_Category_Page();

