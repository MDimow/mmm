<?php
class SmartNftCutomRoutePublicProfile 
{
	function __construct() {
    add_action("init",array($this,"add_rewrite_rule"));
    add_filter("query_vars",array($this,"add_query_vars"));
    add_filter("template_include",array($this,"change_template"));
	}

	public function add_rewrite_rule () {
    add_rewrite_rule( 'profile/([a-z0-9-]+)[/]?$', 'index.php?profile=$matches[1]', 'top' );
	}


  public function add_query_vars ($query_vars) {
    $query_vars[] = 'profile';
    return $query_vars;
  }

  public function change_template ($template) {

    if (!is_page("profile") && preg_match('/\/profile\//',$_SERVER["REQUEST_URI"] ) && !is_buddypress_page()) {
        $file = PLUGIN_ROOT . "public-profile.php";
        if ( file_exists( $file ) ) {
              return $file;
          }
       }

      return $template;
  }

}



new SmartNftCutomRoutePublicProfile();
