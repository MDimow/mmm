<?php


    /**
    * Compatibility for Theme Name: Bricks
    * Compatibility checked on Version: 1.3.1
    */
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_bricks
        {
                        
            var $wph;
                           
            function __construct()
                {
                    
                    global $wph;
                    
                    $this->wph  =   $wph;
                    
                                 
                    if ( isset( $_GET['bricks'] ) )
                        {
                            add_filter ('wph/components/css_combine_code',  '__return_false');
                            add_filter ('wph/components/js_combine_code',   '__return_false' );
                            add_filter ('wph/components/_init/',                    array( $this,    'wph_components_init'), 999, 2 );
                        }
                        
                                          
                    if ( isset( $_POST['action'] )  &&  ( $_POST['action']    ==  'elementor_ajax'  ||  $_POST['action']    ==  'heartbeat'  ) )
                        {
                            add_filter ('wph/components/_init/',                    array( $this,    'wph_components_init'), 999, 2 );
                        }
                        
                    add_filter( 'wph/components/components_run/ignore_field_id',    array( $this,    'ignore_field_id'), 999, 3 );

                }                        
                
            
            function ignore_field_id( $ignore_field, $field_id, $saved_field_value )
                {
                    
                    if  ( in_array( $field_id, array( 'js_combine_code', 'css_combine_code' ) ) )
                        {
                            if  (  isset( $_GET['bricks'] ) )
                                {
                                    $ignore_field   =   TRUE;
                                }
                            
                        }
                    
                    return $ignore_field;
                    
                }
                
            function wph_components_init( $status, $component )
                {
                    if ( $component ==  'rewrite_default' )
                        return FALSE;
                        
                        
                    return $status;
                    
                }
                            
        }


    new WPH_conflict_bricks();
    
?>