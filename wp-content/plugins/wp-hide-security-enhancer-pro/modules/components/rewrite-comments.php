<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_rewrite_comments extends WPH_module_component
        {
            
            function get_component_title()
                {
                    return "Comments";
                }
                                                
            function get_module_component_settings()
                {
                    $this->component_settings[]                  =   array(
                                                                        'id'            =>  'new_wp_comments_post',
                                                                        'label'         =>  __('New wp-comments-post.php Path',    'wp-hide-security-enhancer'),
                                                                        'description'   =>  __('The default path is set to wp-comments-post.php',    'wp-hide-security-enhancer'),
                                                                        
                                                                        'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New wp-comments-post.php',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("As default the form data is being sent and processed at:",    'wp-hide-security-enhancer') ." <br />  <br />
                                                                                                                                            <code>https://-domain-name-/wp-comments-post.php</code>
                                                                                                                                            <br /><br /> " . __("This makes it easy to recognise as WordPress form. Boots always search for such file ( wp-comments-post.php ) and automatically submit spam messages.",    'wp-hide-security-enhancer') .
                                                                                                                                            __("Though this option a new file slug can replace the default.",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-comments/',
                                                                                                        'input_value_extension'     =>  'php'
                                                                                                        ),
                                                                        
                                                                        'value_description' =>  __( 'e.g. user-input.php',    'wp-hide-security-enhancer'),
                                                                        'input_type'    =>  'text',
                                                                        
                                                                        'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name'), array($this->wph->functions, 'extension_required', array('extension' => 'php'))),
                                                                        'processing_order'  =>  60
                                                                        );
                                                                    
                    $this->component_settings[]                  =   array(
                                                                        'id'            =>  'block_wp_comments_post_url',
                                                                        'label'         =>  __('Block default wp-comments-post.php',    'wp-hide-security-enhancer'),
                                                                        'description'   =>  __('Block default wp-comments-post.php.',    'wp-hide-security-enhancer'),
                                                                        
                                                                        'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Block default wp-comments-post.php',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("After changing the default wp-comments-post.php, the old url is still accessible, this provide a way to block the old.<br />The functionality apply only if <b>New wp-comments-post.php</b> option is filled in.",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-comments/'
                                                                                                        ),
                                                                        
                                                                        'input_type'    =>  'radio',
                                                                        'options'       =>  array(
                                                                                                    'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                    'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                    ),
                                                                        'default_value' =>  'no',
                                                                        
                                                                        'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                        'processing_order'  =>  61
                                                                        
                                                                        );
                    
                                                                    
                    return $this->component_settings;   
                }
                
            
            
            function _init_new_wp_comments_post($saved_field_data)
                {
                   
                    if(empty($saved_field_data))
                        return FALSE;
                    
                    //add default plugin path replacement
                    $url            =   trailingslashit(    site_url()  ) .  'wp-comments-post.php';
                    $replacement    =   trailingslashit(    home_url()  ) .  $saved_field_data;
                    $this->wph->functions->add_replacement( $url , $replacement );
                    
                    return TRUE;
                }
                
            function _callback_saved_new_wp_comments_post($saved_field_data)
                {
                    
                    if(empty($saved_field_data))
                        return FALSE;
                    
                    $processing_response    =   array();
                    
                    global $blog_id;
                    if(is_multisite())
                        {
                            $blog_details   =   get_blog_details( $blog_id );
                            $ms_settings    =   $this->wph->functions->get_site_settings('network');
                        }
                        
                    $rewrite                            =  '';
                    
                    $rewrite_base       =   $this->wph->functions->get_rewrite_base( $saved_field_data, FALSE, FALSE );
                    $rewrite_to         =   $this->wph->functions->get_rewrite_to_base( 'wp-comments-post.php' , TRUE, FALSE, 'full_path' );
                               
                    if($this->wph->server_htaccess_config   === TRUE)
                        {
                            
                            if(!is_multisite() )
                                {
                                    $rewrite  .= "\nRewriteRule ^"    .   $rewrite_base   .   ' '. $rewrite_to .' [END,QSA]' . "";
                                }
                                else
                                {
                                    $rewrite  .= "\nRewriteRule ^([_0-9a-zA-Z-]+/)?"    .   $rewrite_base   .   ' '. $rewrite_to .' [END,QSA]' . "";   
                                }
                        }
                    
                    if($this->wph->server_web_config   === TRUE)
                        {
                            $rewrite    =   "\n" . '<rule name="wph-new_wp_comments_post" stopProcessing="true">';
                            
                            if(!is_multisite() )
                                {
                                    $rewrite .=  "\n"  .    '    <match url="^'.  $rewrite_base   .'"  />';
                                    $rewrite .=   "\n" .    '    <action type="Rewrite" url="'.  $rewrite_to .'"  appendQueryString="true" />';
                                }
                                else
                                {
                                    $rewrite .=  "\n"  .    '    <match url="^([_0-9a-zA-Z-]+/)?'.  $rewrite_base   .'"  />';
                                    $rewrite .=   "\n" .    '    <action type="Rewrite" url="'.  $rewrite_to .'"  appendQueryString="true" />';
                                }
                            
                            $rewrite .=  "\n" . '</rule>';
                        }
                        
                    if($this->wph->server_nginx_config   === TRUE)           
                        {
                            $rewrite        =   array();
                            $rewrite_list   =   array();
                            $rewrite_rules  =   array();
                            
                            $global_settings    =   $this->wph->functions->get_global_settings ( );
                            
                            $home_root_path =   $this->wph->functions->get_home_root();
                               
                            if(!is_multisite() )
                                {
                                    $rewrite_list['blog_id'] =   $blog_id;
                                    if( is_multisite() )
                                        {
                                            $rewrite_base   =   ltrim($this->wph->functions->string_left_replacement($rewrite_base, ltrim($blog_details->path, '/')));
                                        }
                                }
                                else
                                    $rewrite_list['blog_id'] =   'network';
                                
                            $rewrite_list['type']        =   'location';
                            $rewrite_list['description'] =   '~ ^__WPH_SITES_SLUG__/' . untrailingslashit($rewrite_base) ;
                            
                            
                            if( $global_settings['nginx_generate_simple_rewrite']   !=  'yes' )
                                {
                                    $rewrite_rules[]  =   '         set $wph_remap  "${wph_remap}wp-comments__";';
                                }
                            
                            $rewrite_data   =   '';
                            
                            $rewrite_data .= "\n         rewrite \"^". untrailingslashit($home_root_path) ."__WPH_SITES_SLUG__/". $rewrite_base ."\" ". $rewrite_to .' '.  $this->wph->functions->get_nginx_flag_type() .';';
                                                            
                            $rewrite_rules[]            =   $rewrite_data;
                            $rewrite_list['data']       =   $rewrite_rules;
                            
                            $rewrite[]  =   $rewrite_list;   
                        }
                    
                    $processing_response['rewrite'] =   $rewrite;   
                                
                    return  $processing_response;     
                    
                    
                }
            
            
            function _callback_saved_block_wp_comments_post_url($saved_field_data)
                {

                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    $processing_response    =   array();
                    
                    global $blog_id;
                    if ( is_multisite() )
                        {
                            $blog_details   =   get_blog_details( $blog_id );
                            $ms_settings    =   $this->wph->functions->get_site_settings('network');
                        }
                    
                    //prevent from blocking if the wp_comments_post is not modified
                    $new_path     =   $this->wph->functions->get_site_module_saved_value('new_wp_comments_post',  $this->wph->functions->get_blog_id_setting_to_use(), 'display');
                    if (empty(  $new_path ))
                        return FALSE;

                        
                    $rewrite                            =  '';

                    $rewrite_base       =   $this->wph->functions->get_rewrite_base( 'wp-comments-post.php', FALSE, FALSE, 'wp_path' );
                    $rewrite_to         =   $this->wph->functions->get_rewrite_to_base( 'index.php?wph-throw-404' , TRUE, FALSE, 'site_path' );
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                                        

                            if(!is_multisite() )
                                {
                                    $rewrite   .=   "\nRewriteRule ^" . $rewrite_base ." ".  $rewrite_to ." [END]";
                                }
                                else
                                {
                                    $rewrite   .=   "\nRewriteRule ^([_0-9a-zA-Z-]+/)?" . $rewrite_base ." ".  $rewrite_to ." [END]";
                                }
                            
                        }
                        
                    if($this->wph->server_web_config   === TRUE)
                        {
                            $rewrite    =   "\n" . '<rule name="wph-block_wp_comments_post_url" stopProcessing="true">';
                            
                            if(!is_multisite() )
                                {
                                    $rewrite .=  "\n"  .    '    <match url="^'.  $rewrite_base   .'"  />';
                                    $rewrite .=   "\n" .    '    <action type="Rewrite" url="'.  $rewrite_to .'"  appendQueryString="false" />';
                                }
                                else
                                {
                                    $rewrite .=  "\n"  .    '    <match url="^([_0-9a-zA-Z-]+/)?'.  $rewrite_base   .'"  />';
                                    $rewrite .=   "\n" .    '    <action type="Rewrite" url="'.  $rewrite_to .'"  appendQueryString="false" />';
                                }
                            
                            $rewrite .=  "\n" . '</rule>';

                        }
                    
                    if($this->wph->server_nginx_config   === TRUE)           
                        {
                            $global_settings    =   $this->wph->functions->get_global_settings ( );
                            
                            $home_root_path =   $this->wph->functions->get_home_root();
                            
                            if ( $global_settings['nginx_generate_simple_rewrite']   ==  'yes' )
                                {
                                    if ( ! is_multisite() )
                                        {
                                            $rewrite        =   array();    
                                            $rewrite_list   =   array();
                                            $rewrite_rules  =   array();
                                            
                                            $rewrite_list['blog_id']        =   'network';
                                            $rewrite_list['type']           =   'location';
                                            $rewrite_list['description']    =   '~ ^__WPH_SITES_SLUG__/' . untrailingslashit($rewrite_base);
                                            
                                            $rewrite_data               =   "rewrite \"^". untrailingslashit($home_root_path) ."__WPH_SITES_SLUG__/". $rewrite_base ."\" ". $rewrite_to .' '.  $this->wph->functions->get_nginx_flag_type() .';';    
                                            
                                            $rewrite_rules[]            =   $rewrite_data;
                                            $rewrite_list['data']       =   $rewrite_rules; 
                                            
                                            $rewrite[]                  =   $rewrite_list; 
                                        }
                                    
                                    $processing_response['rewrite'] = $rewrite;            
                                    return  $processing_response;    
                                }
                            
                            $rewrite        =   array();    
                            $rewrite_list   =   array();
                            $rewrite_rules  =   array();
                            
                            if(!is_multisite() )
                                {
                                    $rewrite_list['blog_id'] =   $blog_id;
                                    if( is_multisite() )
                                        {
                                            $rewrite_base   =   ltrim($this->wph->functions->string_left_replacement($rewrite_base, ltrim($blog_details->path, '/')));
                                        }
                                }
                                else
                                    $rewrite_list['blog_id'] =   'network';
                            
                            $rewrite_list   =   array();
                            $rewrite_rules  =   array();
                            
                            if(!is_multisite()  )
                                {
                                    $rewrite_list['blog_id'] =   $blog_id;
                                }
                                else
                                    $rewrite_list['blog_id'] =   'network';
                                    
                            $rewrite_list['type']        =   'location';
                            $rewrite_list['description'] =   '~ ^__WPH_SITES_SLUG__/' . untrailingslashit($rewrite_base) . '';
                                                        
                            $rewrite_data   =   '';

                            $rewrite_data  .=    "\n".'         if ( $wph_remap = "" ) {';
                            $rewrite_data  .= "\n             rewrite ^__WPH_SITES_SLUG__/". $rewrite_base ." ". $rewrite_to .' last;';
                            $rewrite_data  .=    "\n         }";
                            $rewrite_data  .=    "\n\n         #" . __('REPLACE THE FOLLOWING LINE WITH YOUR OWN INCLUDE! This can be found within block', 'wp-hide-security-enhancer') ."  location ~ \.php$";
                            $rewrite_data  .=    "\n" .'         include snippets/fastcgi-php.conf; fastcgi_pass unix:/run/php/php7.0-fpm.sock;';
                            
                            $rewrite_rules[]            =   $rewrite_data;
                            $rewrite_list['data']       =   $rewrite_rules;
                            
                            $rewrite[]  =   $rewrite_list;    
                        }
                               
                    $processing_response['rewrite'] = $rewrite;            
                                
                    return  $processing_response;     
                    
                    
                }
                
            
        }
?>