<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_hide_check_child_theme    extends WPH_security_scan_item
        {
            var $wph;
                     
            function __construct()
                {
                    $this->id       =   $this->get_id();
                   
                    global $wph;
                    
                    $this->wph  =   $wph;
                }   
            
            public function get_id()
                {
                    return 'hide_check_child_theme';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  __('New Child Theme Path',    'wp-hide-security-enhancer'),
                                        'icon'          =>  'dashicons-hidden',
                                        
                                        'help'          =>  __("This option helps to change the theme url to a custom one. As default all theme assets ( styles, JavaScript etc ) are loaded using the theme url and appear on front side HTML source code.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  10,
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();
                    
                    if ( get_template_directory() === get_stylesheet_directory() )
                        {
                            $_JSON_response =   array ( 
                                                        'status'        =>  TRUE, 
                                                        'description'   =>  __( '<span class="dashicons dashicons-yes"></span>On the last scan, you are not using a Child Theme.', 'wp-hide-security-enhancer' )  
                                                        );
                            
                            return $this->return_json_response( $_JSON_response );
                        }
                    
                    $found_issue        =   FALSE;
                    $option_value       =   $this->wph->functions->get_site_module_saved_value( 'new_theme_child_path',  $this->wph->functions->get_blog_id_setting_to_use());
                    
                    if ( empty ( $option_value ) )
                        $found_issue    =   TRUE;
                    
                    $found_within_code  =   FALSE;
                    if ( ! $found_issue &&  $this->wph->security_scan->remote_html )
                        {
                            $seek_url   =   $this->wph->default_variables['stylesheet_uri'];
                            $seek_url   =   str_replace( array('https://', 'http://'), "", $seek_url );
                            if ( stripos( $this->wph->security_scan->remote_html, $seek_url ) )
                                $found_within_code    =   TRUE;
                        }
                    
                    if ( $found_within_code )
                        $found_issue    =   TRUE;

                    if ( $found_issue   )
                        {
                            $_JSON_response['status']       =   FALSE;
                            
                            if ( empty ( $option_value ) )
                                {
                                    $_JSON_response['description']  =   __( '<span class="dashicons dashicons-no"></span>The default theme url has not been customised.', 'wp-hide-security-enhancer' );
                                }
                                else
                                {
                                    $_JSON_response['description']  =   __( '<span class="dashicons dashicons-no"></span>The default theme url is still found within the source HTML.', 'wp-hide-security-enhancer' );
                                    if ( $found_within_code )
                                        $_JSON_response['description']  =   __( '<br />Ensure you cleared the site cache, then check again.', 'wp-hide-security-enhancer' );
                                }
                            
                            $_JSON_response['actions']      =   array (
                                                                        'fix'       =>  '<a class="button-primary" href="'. network_admin_url ( 'admin.php?page=wp-hide&component=theme' ) .'">Fix</a>',
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        );
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>The default theme url cannot be found anymore through the site source.', 'wp-hide-security-enhancer' );
                        }  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>