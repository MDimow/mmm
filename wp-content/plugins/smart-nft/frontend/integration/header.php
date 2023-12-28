<?php

class SmartNFT_Theme_Header{
    function __construct(){
        add_filter( 'smartchain_header_elements_list', array( $this, 'add_wallet_connect_btn' ), 10, 1 );
        add_action( 'smartchain_wallet_connect', array( $this, 'smartchain_wallet_connect' ) );
        add_filter( 'smartchain_header_elements_list', array( $this, 'add_header_search' ), 10, 1 );
        add_action( 'smartchain_smartnft_search', array( $this, 'smartchain_nft_search' ) );
        add_action( 'wp_ajax_search_results', array( $this, 'header_instant_search' ) );
        add_action( 'wp_ajax_nopriv_search_results', array( $this, 'header_instant_search' ) );
    }
    function header_instant_search(){
        if( !isset( $_POST['text'] ) || empty($_POST['text']) ){
            wp_send_json( [
                'message' => esc_html__( "Search text cannot be empty", WP_SMART_NFT )
            ], 400 );
        }
        $limit  		= sanitize_text_field( $_POST["limit"] );	
        $contract_addr  = sanitize_text_field( $_POST["contract_addr"] );	
        $search_text    = sanitize_text_field( $_POST["text"] );
        try{

            // Filter collections

            $collargs = array(
                'taxonomy'   => 'smartnft_collection',
                'hide_empty' => false,
                'orderby'    => 'count',
                'order'      => 'DESC',
                'meta_query' => array(),
                'offset'	=> 0,
                'limit'		=> 4,
                'search'	=> $search_text
            );
            $terms = get_terms( $collargs );
            $collections = array();

            foreach( $terms as $term ) {
                $data = array(
                    "term_data" => $term,
                    "term_meta" => get_term_meta( $term->term_id, "collection_meta", true ),
                    "term_link" => get_term_link(  $term->term_id  ),
                );


                $collections[] =  $data;
            }

            // Filter NFTs
            $args = array (
                'post_type'      => 'smartnft',
                'post_status'    => 'publish',
                's'			     => $search_text,
                'posts_per_page' => 5,
                'offset' 		 => 0,
                'fields' 		 => 'ids'
            );

            $the_query = new WP_Query( $args );

            $all_nfts = array();

            $settings = get_option("smartnft_settings",[]);
            $quality = !empty( $settings['thumbQuality']  ) ? $settings['thumbQuality'] : 'medium';
        
            if( $the_query->have_posts() ) {
                while ( $the_query->have_posts() ) {
                    $the_query->the_post();
                    $post_meta = get_post_meta( get_the_ID(), 'smartnftData', true );
                    $post_permalink = get_the_permalink();
                    $thumbnail_id = $post_meta['thumbnailMediaUrl']['attach_id'];
                    $thumbnail_url = wp_get_attachment_image_url( (int)$thumbnail_id, $quality);
                    $post_meta['permalink'] = $post_permalink;
                    $post_meta['post_id'] = get_the_ID();
                    $post_meta['post_status'] = get_post_status();
                    $post_meta['thumbnailMediaUrl']['attach_url'] = $thumbnail_url ? $thumbnail_url : $post_meta["thumbnailMediaUrl"]["attach_url"];
                    array_push( $all_nfts, $post_meta );
                }
            }

            // Filter users
            $args = array(
                'role'         => 'smartnft_creators',
                'offset'       => 0,
                'number'       => 4,
                'search'       => $search_text . "*",
            );
            $all_users = [];
            $users = get_users( $args );
    
            if( is_array( $users ) && !empty( $users ) ){
                foreach( $users as $user ){
                    $profileImg = get_user_meta( $user->ID, 'profile_img', true );
                    $cur_user = [
                        'profileImg'=> $profileImg,
                        'public_url'=> '/profile/' . $user->user_login,
                        'name'      => $user->display_name
                    ];
                    array_push( $all_users, $cur_user );
                }
            }
            wp_send_json (
            [
                "data"    => [
                    "collections"  => $collections, 
                    "nfts"         => $all_nfts,
                    "users"        => $all_users
                ]
            ],
            200
            );
        }catch (Exception $e) {
            wp_send_json(
                [
                    "message" => $e->getMessage()
                ],
                400
            );
        }
    }

    function smartchain_nft_search(){
        echo '<div id="header-nft-search"></div>';
    }
    function smartchain_wallet_connect(){
        echo '<div id="header-wallet-connect">Connect Wallet</div>';
    }
    function add_wallet_connect_btn( $elements ){
        $args = array(
            'wallet-connect' => esc_html__( 'Connect Wallet', WP_SMART_NFT ),
        );
        return array_merge( $elements, $args );
    }
    function add_header_search( $elements ){
        $args = array(
            'smartnft-search' => esc_html__( 'SmartNFT Search', WP_SMART_NFT ),
        );
        return array_merge( $elements, $args );
    }
}
new SmartNFT_Theme_Header();