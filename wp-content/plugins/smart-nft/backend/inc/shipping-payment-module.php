<?php

class Shipping_Payments_Module{
    private $user_id, $subscriber_id, $subscription_id, $pricing_id;
    public $stripe, $product_id;
    public function __construct(){
        $this->InitStripeClient();
        add_action( 'wp_ajax_create_payments', [ $this, 'stripe_payments' ] );
        add_action( 'wp_ajax_update_order_tracking', [ $this, 'update_order_tracking' ] );
        add_action( 'wp_ajax_nopriv_update_order_tracking', [ $this, 'update_order_tracking' ] );
    }
    public function update_tracking( $post_id, $step, $tracking_info ){
      $order_tracking = get_post_meta( $post_id, 'current_order_tracking', true );

      $index;
      foreach( $order_tracking as $key => $value ){
        if( $value['step'] == $step ){
          $index = $key;
        }
      }
      if( empty($key) || $key == '' ){
        return null;
      }else{
        if( $order_tracking[$index]['status'] == 'pending' ){
          $order_tracking[$index]['status'] = 'completed';
          $order_tracking[$index]['time'] = current_datetime()->format('d M Y h:i A');    
          if( $tracking_info != '' ){
            $order_tracking[$index]['tracking_info'] = $tracking_info;    
          }
        }
        return update_post_meta( $post_id, 'current_order_tracking', $order_tracking );
      }
    }
    public function update_order_tracking(){
      $post_id = $_POST['post_id'];
      $step = $_POST['step'];
      $tracking_info = $_POST['trackingInfo'];
      
      try{
        if( isset($_POST['session_id']) || !empty($_POST['session_id']) ){
          $session_id = $_POST['session_id'];
          $session = $this->stripe->checkout->sessions->retrieve($session_id);
          $customer = $this->stripe->customers->retrieve($session->customer);
          $meta = get_post_meta( $post_id, 'current_checkout_data', true );

          if( !$meta['shippingChargePaid'] ){
            $meta['shippingChargePaid'] = true;
            // UPDATE SHIPPING ADDRESS
            $updated = update_post_meta( $post_id, 'current_checkout_data', $meta );
          }
          
        }

        // UPDATE TRACKING ORDER
        $update_tracking = $this->update_tracking($post_id, $step, $tracking_info);

        wp_send_json( [
          'status' => true,
          'data' => get_post_meta( $post_id, 'current_order_tracking', true )
        ], 200 );
      }catch(Exception $e){
        wp_send_json( [ 
          "status" => false, 
          "message" => $e->getMessage() 
        ], 200 );
      }

    }
    public function stripe_payments(){
        $price = $_POST['price'];
        $carrier = $_POST['carrier'];
        $name = $_POST['name'];
        $return = $_POST['return'];
        $checkout_url = $this->createCheckoutSession($price, $carrier, $name, $return);
        wp_send_json( $checkout_url, 200 );
    }
    public function createCheckoutSession( $price, $carrier, $name, $return ){
      $checkout_session = $this->stripe->checkout->sessions->create([
        'success_url' => $return . '?session_id={CHECKOUT_SESSION_ID}',
        'line_items' => [[
            'price_data' => [
              'currency' => 'usd',
              'product_data' => [
                'name' => $carrier . ' SHIPPING CHARGE for ' . $name,
              ],
              'unit_amount' => $price,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
      ]);
      return $checkout_session->url;
    }
    public function InitStripeClient(){
        $this->stripe = new \Stripe\StripeClient('sk_test_HJwBt3vTGb5D3aBDmKVYisnY');
    }
    public function setProductID($id){
        return $this->product_id = $id;
    }
    public function setPricingID($id){
        return $this->pricing_id = $id;
    }
}

new Shipping_Payments_Module();