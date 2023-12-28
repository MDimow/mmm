<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module
        {
            var $components =   array();
            
            var $functions;
            var $wph;
            
            var $use_tabs;
                     
            function __construct()
                {
                    $this->use_tabs     =   $this->use_tabs();
                    
                    $this->functions    =   new WPH_functions();
                    
                    global $wph;
                    $this->wph          =   &$wph;
                    
                    $this->load_components();
                }
     
                
            function load_components()
                {
                
                }
            
            /**
            * Indicate if this module use a tabs to output included components
            * 
            */
            function use_tabs()
                {
                    
                    return FALSE;   
                }
            
            /**
            * Return module components settings
            *     
            */
            function get_module_components_settings($module_id =   FALSE)
                {
                    
                    $module_components_settings                    =   array();

                    foreach($this->components   as  $module_component)
                        {
                            if(!empty($module_id)   &&  $module_component->id   !== FALSE   &&  $module_component->id   !=  $module_id)
                                continue;
                            
                            if(count($module_components_settings)  >   0)
                                {
                                    //add a split for interface
                                    $module_components_settings[]                  =   array(
                                                                                    'type'          =>  'split',
                                                                                    );   
                                }
                            
                            if(is_array($module_component->component_settings) &&  count($module_component->component_settings) > 0)
                                {
                                    foreach($module_component->component_settings  as  $module_setting)
                                        {
                                            $module_setting['class_instance'] =   $module_component;   
                                            $module_components_settings[]  =   $module_setting;
                                        }   
                                    
                                }
                                
                            
                        }
                                  
                    $module_components_settings    =   apply_filters('wp-hide/module_settings',    $module_components_settings,   $this);
                    
                    
                    return $module_components_settings;
                    
                }
      
        }
    
 
?>