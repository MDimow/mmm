<?php
class SmartNftActivity 
{
	function __construct() {
		register_activation_hook( PLUGIN_ROOT . "smart-nft.php", [ $this, "create_activity_table" ] );
        add_action( 'wp_ajax_insert_activity', [ $this, 'insert_activity' ], 10 );
        add_action( 'wp_ajax_nopriv_insert_activity', [ $this, 'insert_activity' ], 10 );
        add_action( 'wp_ajax_samrtnft_get_coll_stats_on_time_range', [ $this, 'get_coll_stats_on_time_range' ], 10);
        add_action( 'wp_ajax_nopriv_samrtnft_get_coll_stats_on_time_range', [ $this, 'get_coll_stats_on_time_range' ], 10);
	}

    function create_activity_table(){
        global $wpdb;
        $query = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix ."smartnft_activity (
            id int(11) NOT NULL auto_increment,
            post_id int(11) NOT NULL,
            activity_type varchar(15) NOT NULL,
            price float(10) NOT NULL,
            addr_from varchar(200) NOT NULL,
            addr_to varchar(200) NOT NULL,
            chain_id int(15) NOT NULL,
            collection_id int(10) NOT NULL,
            category_id int(10) NOT NULL,
            input_time DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
            UNIQUE KEY id (id)
        )";
        $wpdb->query($query);
    }

    function insert_data( $data ){
        global $wpdb;
        $table = $wpdb->prefix . 'smartnft_activity';
        
        $format = array( '%d', '%s', '%f', '%s', '%s', '%d', '%d', '%d' );
        $wpdb->insert( $table, $data, $format );
        $id = $wpdb->insert_id;
		return $id;
    }

    function insert_activity(){
		if( !isset( $_POST['activityData'] ) || empty( $_POST['activityData'] ) ){
			wp_send_json(array("status" => "fail", "message" => "Send valid data"), 400);
		}

		$activity_data = map_deep( $_POST['activityData'], 'sanitize_text_field' );

        $id = $this->insert_data( $activity_data );

		wp_send_json(array("status" => "success", "data" =>  array("id" => $id)), 200);
    }

	function get_coll_stats_on_time_range() {
		if( 
			!isset( $_POST['number'] ) || 
			empty( $_POST['number'] ) ||
			!isset( $_POST['unit'] ) || 
			empty( $_POST['unit'] ) ||
			!isset( $_POST['collId'] ) || 
			empty( $_POST['collId'] ) 

		  )
	   	{
			wp_send_json(array("status" => "fail", "message" => "No start time / coll id is send"), 400);
		}

		$number = $_POST['number'];
		$unit = $_POST['unit'];
		$collId = intval( $_POST["collId"] );
		$term = get_term( $collId, 'smartnft_collection' );

		if( is_wp_error( $term ) ) { wp_send_json([ "message" => esc_html__("coll not exist", WP_SMART_NFT) ], 400); }

        global $wpdb;
        $table = $wpdb->prefix.'smartnft_activity';

		$query = " SELECT * FROM " . $table . " WHERE input_time BETWEEN DATE_SUB( NOW(), INTERVAL " . $number .  " " . $unit . " ) AND NOW() " . " AND collection_id = " . $collId;
		$result = $wpdb->get_results($query);
		//var_dump( $result );
		wp_send_json(array("status" => "success", "data" => $result ), 200);
	}

    function CollectionVolumeSpan( $range, $current_timespan ){}
    function updateData(){}
    function createData(){}
    function getData(){}
}

new SmartNftActivity();
