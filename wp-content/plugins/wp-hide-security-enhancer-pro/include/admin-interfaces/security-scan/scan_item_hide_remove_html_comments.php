<?php


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_security_scan_hide_remove_html_comments    extends WPH_security_scan_item
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
                    return 'hide_remove_html_comments';
                }
                
                
            public function get_settings()
                {
                    
                    return array(
                                        'title'         =>  __('Remove Comments',    'wp-hide-security-enhancer'),
                                        'icon'          =>  'dashicons-hidden',
                                        
                                        'help'          =>  __("The HTML source code usually contain many comment lines, however there is no use for that, unless debugging. Remove all HTML Comments, which usually specify Plugins Name and Version. Any Internet Explorer conditional tags are preserved.",    'wp-hide-security-enhancer'),
                                        
                                        'score_points'  =>  10,
                                        );
                }
                
            
            function scan()
                {
                    $_JSON_response     =   array();
                    
                    $found_issue        =   FALSE;
                    
                    $option       =   $this->wph->functions->get_site_module_saved_value( 'remove_html_comments',  $this->wph->functions->get_blog_id_setting_to_use());
                    
                    if (    empty ( $option )   ||  $option ==  'no' )
                        $found_issue    =   TRUE;

                    if ( $found_issue   )
                        {
                            $_JSON_response['status']       =   FALSE;
                            
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-no"></span>The site pages still contain HTML comments which may provide essential pieces of information regarding the active plugins and themes.', 'wp-hide-security-enhancer' );
                            
                            $_JSON_response['actions']      =   array (
                                                                        'fix'       =>  '<a class="button-primary" href="'. network_admin_url ( 'admin.php?page=wp-hide-general&component=html' ) .'">Fix</a>',
                                                                        'ignore'            =>  '//--post-generated--',
                                                                        'restore'           =>  '//--post-generated--',
                                                                        );
                        }
                        else
                        {
                            $_JSON_response['status']       =   TRUE;
                            $_JSON_response['description']  =   __( '<span class="dashicons dashicons-yes"></span>The option appears properly configured.', 'wp-hide-security-enhancer' );
                        }  
                        
                    return $this->return_json_response( $_JSON_response );
                
                }    
            
        }
        
        
?>