<?php
class SmartNft_Custom_Post_Type 
{

    public function __construct () {
		add_action( 'admin_menu', array($this,'smart_nft_remove_menu_items') );
        add_action( 'init', array( $this, 'smart_nft_cpt') );
        add_action( 'init', array( $this, 'smart_nft_taxonomy_collections') );
        add_action( 'init', array( $this, 'smart_nft_taxonomy_category') );
        add_action( 'init', array( $this, 'update_volume_changes' ) );
        add_action( 'single_template', array($this, 'smartNFTSingle') );
    }
    public function update_volume_changes(){
        $current_time = strtotime(current_time( 'Y-m-d H:i:s', false ));

        // 5m calculations
        $current_time_5m = get_option( 'current_5m_limit' ) ? get_option( 'current_5m_limit' ) + 300 : strtotime(current_time('Y-m-d H:i:s')) + 300;

        $current_time_limit_5m = get_option( 'current_5m_limit' );

        if( $current_time_limit_5m ){
            if( intval($current_time_limit_5m) < intval($current_time) ){
                update_option( 'prev_5m_limit', $current_time_limit_5m );
                update_option( 'current_5m_limit', $current_time_5m );
                $terms = get_terms( array(
                        'taxonomy'      => 'smartnft_collection',
                        'hide_empty'    => false,
                        'fields'	    => 'ids'
                    )
                );
                foreach( $terms as $term_id ){
                    $get_current_5m_vol = get_term_meta( $term_id, 'total_vol_5m', true );
                    update_term_meta( $term_id, 'total_vol_5m_prev', $get_current_5m_vol );
                    update_term_meta( $term_id, 'total_vol_5m', 0 );
                    // update_term_meta( $term_id, '5m_vol_changed', false );
                }
            }
        }else{
            update_option( 'current_5m_limit', $current_time_5m );
        }

        // 15m calculations
        $current_time_15m = get_option( 'current_15m_limit' ) ? get_option( 'current_15m_limit' ) + 900 : strtotime(current_time('Y-m-d H:i:s')) + 900;

        $current_time_limit_15m = get_option( 'current_15m_limit' );

        if( $current_time_limit_15m ){
            if( intval($current_time_limit_15m) < intval($current_time) ){
                update_option( 'prev_15m_limit', $current_time_limit_15m );
                update_option( 'current_15m_limit', $current_time_15m );
                $terms = get_terms( array(
                        'taxonomy'      => 'smartnft_collection',
                        'hide_empty'    => false,
                        'fields'	    => 'ids'
                    )
                );
                foreach( $terms as $term_id ){
                    $get_current_15m_vol = get_term_meta( $term_id, 'total_vol_15m', true );
                    update_term_meta( $term_id, 'total_vol_15m_prev', $get_current_15m_vol );
                    update_term_meta( $term_id, 'total_vol_15m', 0 );
                    // update_term_meta( $term_id, '15m_vol_changed', false );
                }
            }
        }else{
            update_option( 'current_15m_limit', $current_time_15m );
        }

        // 30m calculations
        $current_time_30m = get_option( 'current_30m_limit' ) ? get_option( 'current_30m_limit' ) + 1800 : strtotime(current_time('Y-m-d H:i:s')) + 1800;
        $current_time_limit_30m = get_option( 'current_30m_limit' );

        if( $current_time_limit_30m ){
            if( intval($current_time_limit_30m) < intval($current_time) ){
                update_option( 'prev_30m_limit', $current_time_limit_30m );
                update_option( 'current_30m_limit', $current_time_30m );
                $terms = get_terms( array(
                        'taxonomy'      => 'smartnft_collection',
                        'hide_empty'    => false,
                        'fields'	    => 'ids'
                    )
                );
                foreach( $terms as $term_id ){
                    $get_current_30m_vol = get_term_meta( $term_id, 'total_vol_30m', true );
                    update_term_meta( $term_id, 'total_vol_30m_prev', $get_current_30m_vol );
                    update_term_meta( $term_id, 'total_vol_30m', 0 );
                    // update_term_meta( $term_id, '30m_vol_changed', false );
                }
            }
        }else{
            update_option( 'current_30m_limit', $current_time_30m );
        }

        // 1h calculations
        $current_time_1h = get_option( 'current_1h_limit' ) ? get_option( 'current_1h_limit' ) + 3600 : strtotime(current_time('Y-m-d H:i:s')) + 3600;

        $current_time_limit_1h = get_option( 'current_1h_limit' );

        if( $current_time_limit_1h ){
            if( intval($current_time_limit_1h) < intval($current_time) ){
                update_option( 'prev_1h_limit', $current_time_limit_1h );
                update_option( 'current_1h_limit', $current_time_1h );
                $terms = get_terms( array(
                        'taxonomy'      => 'smartnft_collection',
                        'hide_empty'    => false,
                        'fields'	    => 'ids'
                    )
                );
                foreach( $terms as $term_id ){
                    $get_current_1h_vol = get_term_meta( $term_id, 'total_vol_1h', true );
                    update_term_meta( $term_id, 'total_vol_1h_prev', $get_current_1h_vol );
                    update_term_meta( $term_id, 'total_vol_1h', 0 );
                    // update_term_meta( $term_id, '1h_vol_changed', false );
                }
            }
        }else{
            update_option( 'current_1h_limit', $current_time_1h );
        }

        // 6h calculations
        $current_time_6h = get_option( 'current_6h_limit' ) ? get_option( 'current_6h_limit' ) + 21600 : strtotime(current_time('Y-m-d H:i:s')) + 21600;
        
        $current_time_limit_6h = get_option( 'current_6h_limit' );

        if( $current_time_limit_6h ){
            if( intval($current_time_limit_6h) < intval($current_time) ){
                update_option( 'prev_6h_limit', $current_time_limit_6h );
                update_option( 'current_6h_limit', $current_time_6h );
                $terms = get_terms( array(
                        'taxonomy'      => 'smartnft_collection',
                        'hide_empty'    => false,
                        'fields'	    => 'ids'
                    )
                );
                foreach( $terms as $term_id ){
                    $get_current_6h_vol = get_term_meta( $term_id, 'total_vol_6h', true );
                    update_term_meta( $term_id, 'total_vol_6h_prev', $get_current_6h_vol );
                    update_term_meta( $term_id, 'total_vol_6h', 0 );
                    // update_term_meta( $term_id, '6h_vol_changed', false );
                }
            }
        }else{
            update_option( 'current_6h_limit', $current_time_6h );
        }
        // 24h calculations
        $current_time_24h = strtotime(current_time( 'Y-m-d 23:59:59', false ));
        $current_time_limit_24h = get_option( 'current_24h_limit' );

        if( $current_time_limit_24h ){
            if( intval($current_time_limit_24h) < intval($current_time) ){
                update_option( 'prev_24h_limit', $current_time_limit_24h );
                update_option( 'current_24h_limit', $current_time_24h );
                $terms = get_terms( array(
                        'taxonomy' => 'smartnft_collection',
                        'hide_empty' => false,
                        'fields'	=> 'ids'
                    )
                );
                foreach( $terms as $term_id ){
                    $get_current_24h_vol = get_term_meta( $term_id, 'total_vol_24h', true );

                    $volume_record_24h = get_option( 'volume_record_24h' ) ? get_option( 'volume_record_24h' ) : [];
                    $volume_record_24h[] = array(
                        'time' => $current_time_limit_24h,
                        'volume' => $get_current_24h_vol
                    );
                    update_term_meta( $term_id, 'volume_record_24h', $volume_record_24h );
                    update_term_meta( $term_id, 'total_vol_24h_prev', $get_current_24h_vol );
                    update_term_meta( $term_id, 'total_vol_24h', 0 );
                    // update_term_meta( $term_id, '24h_vol_changed', false );
                }
            }

        }else{
            update_option( 'current_24h_limit', $current_time_24h );
        }
        // 3d calculations
        $current_time_3d = strtotime(current_time( 'Y-m-d 23:59:59', false )) + 259200;
        $current_time_limit_3d = get_option( 'current_3d_limit' );

        if( $current_time_limit_3d ){
            if( intval($current_time_limit_3d) < intval($current_time) ){
                update_option( 'prev_3d_limit', $current_time_limit_3d );
                update_option( 'current_3d_limit', $current_time_3d );
                $terms = get_terms( array(
                        'taxonomy' => 'smartnft_collection',
                        'hide_empty' => false,
                        'fields'	=> 'ids'
                    )
                );
                foreach( $terms as $term_id ){
                    $get_current_3d_vol = get_term_meta( $term_id, 'total_vol_3d', true );
                    update_term_meta( $term_id, 'total_vol_3d_prev', $get_current_3d_vol );
                    update_term_meta( $term_id, 'total_vol_3d', 0 );
                    // update_term_meta( $term_id, '3d_vol_changed', false );
                }
            }

        }else{
            update_option( 'current_3d_limit', $current_time_3d );
        }
        
        // 7d calculations
        $current_time_7d = strtotime(current_time( 'Y-m-d 23:59:59', false )) + 604800;
        $current_time_limit_7d = get_option( 'current_7d_limit' );

        if( $current_time_limit_7d ){
            if( intval($current_time_limit_7d) < intval($current_time) ){
                update_option( 'prev_7d_limit', $current_time_limit_7d );
                update_option( 'current_7d_limit', $current_time_7d );
                $terms = get_terms( array(
                        'taxonomy' => 'smartnft_collection',
                        'hide_empty' => false,
                        'fields'	=> 'ids'
                    )
                );
                foreach( $terms as $term_id ){
                    $get_current_7d_vol = get_term_meta( $term_id, 'total_vol_7d', true );
                    update_term_meta( $term_id, 'total_vol_7d_prev', $get_current_7d_vol );
                    update_term_meta( $term_id, 'total_vol_7d', 0 );
                    // update_term_meta( $term_id, '7d_vol_changed', false );
                }
            }

        }else{
            update_option( 'current_7d_limit', $current_time_7d );
        }

        // 30d calculations
        $current_time_30d = strtotime(current_time( 'Y-m-d 23:59:59', false ) . ' + 30 days');
        $current_time_limit_30d = get_option( 'current_30d_limit' );

        if( $current_time_limit_30d ){
            if( intval($current_time_limit_30d) < intval($current_time) ){
                update_option( 'prev_30d_limit', $current_time_limit_30d );
                update_option( 'current_30d_limit', $current_time_30d );
                $terms = get_terms( array(
                        'taxonomy' => 'smartnft_collection',
                        'hide_empty' => false,
                        'fields'	=> 'ids'
                    )
                );
                foreach( $terms as $term_id ){
                    $get_current_30d_vol = get_term_meta( $term_id, 'total_vol_30d', true );
                    update_term_meta( $term_id, 'total_vol_30d_prev', $get_current_30d_vol );
                    update_term_meta( $term_id, 'total_vol_30d', 0 );
                    // update_term_meta( $term_id, '30d_vol_changed', false );
                }
            }

        }else{
            update_option( 'current_30d_limit', $current_time_30d );
        }
            
    }


   public  function smart_nft_cpt(){
        register_post_type('smartnft', 
            array(
                'labels' => [
                    'name' => esc_html__('Smart NFT', 'smartnft' ),
                ],
                'public' => true,
                'has_archive' => true,
                'taxonomies' => array('smartnft_collection'),
                'rewrite'   => array( 'slug' => 'token' )
            )
        );
    }

    public function smart_nft_taxonomy_collections () {
        $labels = array(
            'name'                       => esc_html__( 'Collections', 'Collection', WP_SMART_NFT ),
            'singular_name'              => esc_html__( 'Collection', 'Collection', WP_SMART_NFT ),
            'search_items'               => esc_html__( 'Search Collection', WP_SMART_NFT ),
            'popular_items'              => esc_html__( 'Popular Collection', WP_SMART_NFT ),
            'all_items'                  => esc_html__( 'All Collection', WP_SMART_NFT ),
            'edit_item'                  => esc_html__( 'Edit Collection', WP_SMART_NFT ),
            'update_item'                => esc_html__( 'Update Collection', WP_SMART_NFT ),
            'add_new_item'               => esc_html__( 'Add New Collection', WP_SMART_NFT ),
            'new_item_name'              => esc_html__( 'New Collection Name', WP_SMART_NFT ),
            'separate_items_with_commas' => esc_html__( 'Separate Collections with commas', WP_SMART_NFT ),
            'add_or_remove_items'        => esc_html__( 'Add or remove Collection', WP_SMART_NFT ),
            'choose_from_most_used'      => esc_html__( 'Choose from the most used Collections', WP_SMART_NFT ),
            'not_found'                  => esc_html__( 'No Collections found.', WP_SMART_NFT ),
            'menu_name'                  => esc_html__( 'Collections', WP_SMART_NFT ),
	      );

        $taxonomy_args = array(
            'hierarchical'          => false,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'collection' ),
            'labels'                => $labels
         );

        register_taxonomy("smartnft_collection","smartnft",$taxonomy_args);
    }

    public function smart_nft_taxonomy_category () {
        $labels = array(
            'name'                       => esc_html__( 'Categories', 'Categories', WP_SMART_NFT ),
            'singular_name'              => esc_html__( 'Category', 'Category', WP_SMART_NFT ),
            'search_items'               => esc_html__( 'Search Category', WP_SMART_NFT ),
            'popular_items'              => esc_html__( 'Popular Category', WP_SMART_NFT ),
            'all_items'                  => esc_html__( 'All Category', WP_SMART_NFT ),
            'edit_item'                  => esc_html__( 'Edit Category', WP_SMART_NFT ),
            'update_item'                => esc_html__( 'Update Category', WP_SMART_NFT ),
            'add_new_item'               => esc_html__( 'Add New Category', WP_SMART_NFT ),
            'new_item_name'              => esc_html__( 'New Category Name', WP_SMART_NFT ),
            'separate_items_with_commas' => esc_html__( 'Separate Categories with commas', WP_SMART_NFT ),
            'add_or_remove_items'        => esc_html__( 'Add or remove Category', WP_SMART_NFT ),
            'choose_from_most_used'      => esc_html__( 'Choose from the most used Categories', WP_SMART_NFT ),
            'not_found'                  => esc_html__( 'No Categories found.', WP_SMART_NFT ),
            'menu_name'                  => esc_html__( 'Categories', WP_SMART_NFT ),
	      );

        $category_args = array(
            'hierarchical'          => false,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'categories' ),
            'labels'                => $labels
         );

        register_taxonomy("smartnft_category", "smartnft", $category_args);
    }

    public function smart_nft_remove_menu_items() {
        remove_menu_page( 'edit.php?post_type=smartnft' );
    }


    public function smartNFTSingle( $singlenft ) {
        if ('smartnft' == get_post_type(get_queried_object_id())) {
            $singlenft = PLUGIN_ROOT . '/single-smartnft.php';
        }
        return $singlenft;
    }

}

new SmartNft_Custom_Post_Type();


