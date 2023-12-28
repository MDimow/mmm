<?php
class SmartNftCutomRoute 
{
	function __construct() {
    add_action("init",array($this,"add_rewrite_rule"));
    add_filter("query_vars",array($this,"add_query_vars"));
    add_filter("template_include",array($this,"change_template"),9999999);
	}
  
	public function add_rewrite_rule () {
    add_rewrite_rule( 'nft/([a-z0-9-]+)[/]?$', 'index.php?nft=$matches[1]', 'top' );
	}

  public function add_query_vars ( $query_vars ) {
    $query_vars[] = 'nft';
    return $query_vars;
  }

  public function change_template ($template) {

    if ( !is_page("nft") && preg_match('/\/nft\//',$_SERVER["REQUEST_URI"] ) ) {
        $file = PLUGIN_ROOT . "single-nft.php";
        if ( file_exists( $file ) ) {
              return $file;
          }
       }

      return $template;
  }

}



new SmartNftCutomRoute();
