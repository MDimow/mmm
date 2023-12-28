<?php

// Class for users management

class SmartNFT_Users{

    public $user_id;
    public $user_data = [];
    public $wallet_address;
    public $follower_id, $following_id;
    public function __construct(){
        add_action( 'wp_ajax_smartnft_create_user', [$this, 'ajax_smartnft_create_user'] );
        add_action( 'wp_ajax_nopriv_smartnft_create_user', [$this, 'ajax_smartnft_create_user'] );

        add_action( 'wp_ajax_wp_save_profile', [$this, 'ajax_smartnft_save_profile'] );
        add_action( 'wp_ajax_nopriv_wp_save_profile', [$this, 'ajax_smartnft_save_profile'] );

        add_action( 'wp_ajax_get_users', [$this, 'ajax_smartnft_get_users'] );
        add_action( 'wp_ajax_nopriv_get_users', [$this, 'ajax_smartnft_get_users'] );

        // Check user access
        add_action( 'wp_ajax_check_user_access', [$this, 'ajax_smartnft_check_user_access'] );
        add_action( 'wp_ajax_nopriv_check_user_access', [$this, 'ajax_smartnft_check_user_access'] );

        // Follow / unfollow users
        add_action( 'wp_ajax_follow_unfollow_user', [$this, 'ajax_smartnft_follow_unfollow_user'] );
        add_action( 'wp_ajax_nopriv_follow_unfollow_user', [$this, 'ajax_smartnft_follow_unfollow_user'] );

        // Is follwoing
        add_action( 'wp_ajax_is_following', [$this, 'ajax_smartnft_is_following'] );
        add_action( 'wp_ajax_nopriv_is_following', [$this, 'ajax_smartnft_is_following'] );
        
        // Get followers
        add_action( 'wp_ajax_get_followers', [$this, 'ajax_smartnft_get_followers'] );
        add_action( 'wp_ajax_nopriv_get_followers', [$this, 'ajax_smartnft_get_followers'] );

        add_action( 'wp_ajax_change_user_access', [$this, 'ajax_smartnft_change_user_access'] );
    }
    public function ajax_smartnft_get_followers(){
        if( empty( $_POST['address'] ) || !isset( $_POST['address'] ) ){
            wp_send_json( [
                'message' => __("address not found.")
            ], 201 );
        }
        $this->set_wallet_address( $_POST['address'] );
        $user_id = $this->get_userid_by_wallet_address( $this->wallet_address );

        $followers = get_user_meta( $user_id, 'followers', true );
        $followers_count = !empty($followers) ? count($followers) : 0;
        $followings = get_user_meta( $user_id, 'followings', true );
        $followings_count = !empty($followings) ? count($followings) : 0;

        wp_send_json( [
            'status' => true,
            'followers' => $followers,
            'followers_count' => $followers_count,
            'followings' => $followings,
            'followings_count' => $followings_count,
        ], 200 );
    }
    public function ajax_smartnft_check_user_access(){
        if( empty( $_POST['address'] ) || !isset( $_POST['address'] ) ){
            wp_send_json( [
                'access' => false,
                'wallet' => false,
                'message' => __("Wallet not found.")
            ], 201 );
        }
        $this->set_wallet_address($_POST['address']);
        if( $this->is_user_exists() ){
            $this->get_userdata();

            $restricted = $this->check_user_accessibility( $this->user_data->ID );
            if( $restricted ){
                $res = [
                    'access' => false,
                    'wallet' => true,
                    'message' => __("User access restricted")
                ];
            }else{
                $res = [
                    'access' => true,
                    'wallet' => true,
                    'message' => __("User access allowed")
                ];
            }
        }else{
            $res = [
                'access' => true,
                'wallet' => true,
                'message' => __("User not found")
            ];
        }
        wp_send_json( $res, 200 );
    }
    public function ajax_smartnft_change_user_access(){
        if( empty( $_POST['user_id'] ) || !isset( $_POST['user_id'] ) ){
            wp_send_json( [
                'message' => __("User id not found.")
            ], 201 );
        }

        if( empty( $_POST['accessible'] ) || !isset( $_POST['accessible'] ) ){
            wp_send_json( [
                'message' => __("Accessibility not found.")
            ], 201 );
        }

        $this->set_userid( $_POST['user_id'] );
        $accessibility = $_POST['accessible'];
        
        $updated = ($accessibility == "true") ? $this->unban_user() : $this->ban_user(); 
        wp_send_json( $updated, 200 );
    }
    public function ajax_smartnft_get_users(){
        $offset = isset($_POST['offset']) ? $_POST['offset'] : 0;
        $number = isset($_POST['limit']) ? $_POST['limit'] : 10;
        $search = isset($_POST['search']) ? $_POST['search'] : '';
        $args = array(
            'role'         => 'smartnft_creators',
            'offset'       => $offset,
            'number'       => $number,
            'search'       => $search
        );
        $res = [];
        $all_users = [];
        $users = get_users( $args );

        if( is_array( $users ) && !empty( $users ) ){
            foreach( $users as $user ){
                $accessibility = get_user_meta( $user->ID, 'restricted', true ) ? false : true;
                $profileImg = get_user_meta( $user->ID, 'profile_img', true );
                $cur_user = [
                    'ID'        => $user->ID,
                    'profileImg'=> $profileImg,
                    'name'      => $user->display_name,
                    'wallet'    => $user->user_login,
                    'email'     => $user->user_email,
                    'registered'=> $user->user_registered,
                    'user_access' => $accessibility
                ];
                array_push( $all_users, $cur_user );
            }
            $users_count = count( $users );
            $res = [
                'users' => $all_users,
                'users_count' => $users_count
            ];
        }else{
            $res = [
                'users' => [],
                "users_count" => 0
            ];
        }
        wp_send_json( $res, 200 );
    }
    public function ajax_smartnft_save_profile(){
        if( empty( $_POST['account'] ) || !isset( $_POST['account'] ) ){
            wp_send_json( [
                'message' => __("Wallet address not found")
            ], 201 );
        }
        if( empty( $_POST['profile'] ) || !isset( $_POST['profile'] ) ){
            wp_send_json( [
                'message' => __("Profile is not provided.")
            ], 201 );
        }

        $this->set_wallet_address( $_POST['account'] );

        $profiledata = $_POST['profile'];
        $display_name = $profiledata['name'];
        $email = $profiledata['email'];
        $desc = $profiledata['shortBio'];
        $profileImg = $profiledata['profileImg'];

        if( $this->is_user_exists() ){
            $this->get_userdata();
            $this->user_data->display_name = $display_name;
            $this->user_data->nickname = $display_name;
            $this->user_data->first_name = $display_name;
            $this->user_data->user_email = $email;
            $this->user_data->description = $desc;

            update_user_meta( $this->user_data->ID, 'profile_img', $profileImg );
            $updated = $this->update_user();
            if( $updated ){
                wp_send_json( [
                    'message' => __("User updated successfully")
                ], 200 );
            }else{                
                wp_send_json( [
                    'message' => __("User update failed")
                ], 201 );
            }
        }

    }
    public function ajax_smartnft_create_user(){
        if( empty( $_POST['wallet_address'] ) || !isset( $_POST['wallet_address'] ) ){
            wp_send_json( [
                'message' => __("Wallet address not found")
            ], 201 );
        }

        // Set wallet address
        $this->set_wallet_address( $_POST['wallet_address'] );

        // create user
        if($this->is_user_exists()){
            wp_send_json( [
                'message' => __("User already exists.Please login")
            ], 201 );
        }else{
            $created = $this->create_user();
            wp_send_json( [
                'message' => __("User created successfully")
            ], 200 );
        }
    }
    private function set_userid( $id ){
        $this->user_id = $id;
    }
    private function set_wallet_address( $addr ){
        $this->wallet_address = $addr;
    }
    private function get_userdata(){
        $args = array(
            'search'         => $this->wallet_address,
            'search_columns' => array( 'user_login' )
        );
        $user_query = new WP_User_Query( $args );

        // User Loop
        if ( ! empty( $user_query->get_results() ) ) {
            foreach ( $user_query->get_results() as $user ) {
                $this->set_userdata($user);
            }
        } else {
            return[
                'message' => __( "No users found" ),
            ];
        }
    }
    private function set_userdata( $user ){
        return $this->user_data = $user;
    }
    private function is_user_exists(){
        if( username_exists( $this->wallet_address ) ){
            return true;
        }else{
            return false;
        }
    }
    private function create_user(){
        $args = array(
            'user_login'            => $this->wallet_address,
            'user_pass'             => $this->wallet_address,
            'user_nicename'         => $this->wallet_address,
            'display_name'          => $this->wallet_address,
            'nickname'              => $this->wallet_address,
            'show_admin_bar_front'  => false,
            'role'                  => 'smartnft_creators',
            'meta_input'            => array(
                'theme' => 'light',
                'wallet_address' => $this->wallet_address,
                'followers' => [],
                'followings' => [],
                'restricted' => false
            )
        );

        $user_id = wp_insert_user( $args );
        if( !is_wp_error( $user_id ) ){
            return [
                'message'   => "User created successfully",
                'status'    => 200,
                'user_id'   => $user_id
            ];
        }else{
            return [
                'message' => "User creation failed",
                'status' => 201
            ];
        }
    }

    private function update_user(){
        $update = wp_insert_user( $this->user_data );
        if( !is_wp_error( $update ) ){
            return true;
        }else{
            return false;
        }
    }

    private function ban_user(){
        return update_user_meta( $this->user_id, 'restricted', 1 );
    }
    
    private function unban_user(){
        return update_user_meta( $this->user_id, 'restricted', 0 );
    }

    private function get_userid_by_wallet_address( $address ){
        $user = get_user_by( 'login', $address );
        if( !$user ){
            return false;
        }else{
            return $user->ID;
        }
    }

    private function check_user_accessibility( $id ){
        return get_user_meta( $id, 'restricted', true );
    }

    private function already_followed(){
        $followers = get_user_meta( $this->following_id, 'followers', true );

        if( is_array($followers) ){
            if( in_array( $this->follower_id, $followers ) ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    private function follow_user(){
        $followers = get_user_meta( $this->following_id, 'followers', true );
        array_push( $followers, $this->follower_id );
        $updated = update_user_meta( $this->following_id, 'followers', $followers );
        
        if( $updated ){
            $res = [
                'message' => __("You started following"),
                'status' => true,
                'btntext' => ("Following")
            ];
        }else{
            $res = [
                'message' => __("Followers update failed"),
                'status' => false,
            ];
        }

        // Update following in follower id
        $followings = get_user_meta( $this->follower_id, 'followings', true );
        array_push( $followings, $this->following_id );
        update_user_meta( $this->follower_id, 'followings', $followings );

        return $res;
    }
    
    private function unfollow_user(){
        $followers = get_user_meta( $this->following_id, 'followers', true );
        $followers = array_diff( $followers, [ $this->follower_id ] );
        $updated = update_user_meta( $this->following_id, 'followers', $followers );

        if( $updated ){
            $res = [
                'message' => __("You are now unfollowing"),
                'status' => true,
                'btntext' => ("Follow +")
            ];
        }else{
            $res = [
                'message' => __("Followers remove failed"),
                'status' => false
            ];
        }

        // Remove following in followers id
        $followings = get_user_meta( $this->follower_id, 'followings', true );
        $followings = array_diff( $followings, [ $this->following_id ] );
        $updated = update_user_meta( $this->follower_id, 'followings', $followings );

        return $res;
    }

    public function ajax_smartnft_is_following(){
        if( empty( $_POST['follower'] ) || !isset( $_POST['follower'] ) ){
            wp_send_json( [
                'message' => __("Follower is not found")
            ], 201 );
        }
        if( empty( $_POST['following'] ) || !isset( $_POST['following'] ) ){
            wp_send_json( [
                'message' => __("Following is not found.")
            ], 201 );
        }
        $follower_id = $this->get_userid_by_wallet_address( $_POST['follower'] );
        $following_id = $this->get_userid_by_wallet_address( $_POST['following'] );

        if( $follower_id && $following_id ){
            $this->following_id = $following_id;
            $this->follower_id = $follower_id;
        }
        if( !$follower_id ){
            $this->set_wallet_address( $_POST['follower'] );
            $user = $this->create_user();
            if( $user['status'] == 200 ){
                $this->following_id = $user['user_id'];
            }else{
                wp_send_json( [
                    'message' => __("You are not following"),
                    'status' => false,
                    'btntext' => ("Follow +")
                ], 200 );
            }
        }
        if( !$following_id ){
            $this->set_wallet_address( $_POST['following'] );
            $user = $this->create_user();
            if( $user['status'] == 200 ){
                $this->following_id = $user['user_id'];
            }else{
                wp_send_json( [
                    'message' => __("You are not following"),
                    'status' => false,
                    'btntext' => ("Follow +")
                ], 200 );
            }
        }

        if( $this->already_followed() ){
            $res = [
                'message' => __("You are now unfollowing"),
                'status' => true,
                'btntext' => ("Following")
            ];
        }else{
            $res = [
                'message' => __("You are not following"),
                'status' => false,
                'btntext' => ("Follow +")
            ];
        }
        wp_send_json( $res, 200 );
    }

    public function ajax_smartnft_follow_unfollow_user(){
        if( empty( $_POST['follower'] ) || !isset( $_POST['follower'] ) ){
            wp_send_json( [
                'message' => __("Follower is not found")
            ], 201 );
        }
        if( empty( $_POST['following'] ) || !isset( $_POST['following'] ) ){
            wp_send_json( [
                'message' => __("Following is not found.")
            ], 201 );
        }
        
        $follower_id = $this->get_userid_by_wallet_address( $_POST['follower'] );
        $following_id = $this->get_userid_by_wallet_address( $_POST['following'] );

        $this->follower_id = $follower_id;
        $this->following_id = $following_id;

        if($this->already_followed()){
            $res = $this->unfollow_user();
        }else{
            $res = $this->follow_user();
        }

        wp_send_json( $res, 200 );
    }
}

new SmartNFT_Users();