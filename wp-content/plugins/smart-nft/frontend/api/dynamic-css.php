<?php 
class Smartnft_Dynamic_Css 
{
	function __construct () {
			add_action( "wp_enqueue_scripts", function () {
				wp_enqueue_style('smartnft-dynamic-css',admin_url('admin-ajax.php').'?action=dynamic_css');
			});

			add_action( "wp_ajax_dynamic_css",array( $this,"smartnft_dynamic_css" ), 10 );
			add_action( "wp_ajax_nopriv_dynamic_css",array( $this,"smartnft_dynamic_css" ), 10 );
			add_action( 'body_class', array( $this, 'smartnft_body_class' ), 10 );
	}
	function smartnft_body_class($classes){
		$settings = get_option( 'smartnft_settings', false );
		
		if( isset($settings['pluginmode']) && !empty($settings['pluginmode']) ){
			$mode = $settings['pluginmode'];
			if( $mode == 'dark' ){
				$classes[] = "smartnft-dark-version";
			}
		}
		return $classes;
	}
	function smartnft_dynamic_css () {
			$settings = get_option("smartnft_settings",false);
  		header('Content-type: text/css');?> 

			/* CSS Starts Here */
			
			/* All nft page */
			#smartnft-root  .all-nft-page.all-nfts, #smartnft-root .all-nft-page.all-nft-skeleton{
					grid-template-columns: repeat(<?php echo $settings["nftpages"]["all"]["cols"]; ?>, 1fr);
			}
			#smartnft-root .all-nft-main-con {
					max-width: <?php echo $settings["nftpages"]["all"]["width"]; ?>px;
			}



			/* all collection page */
			#smartnft-root .all-collections, 
			#smartnft-root .all-collections-skeleton  {
					grid-template-columns: repeat(<?php echo $settings["collections"]["all"]["cols"]; ?>, 1fr);
					max-width: <?php echo $settings["collections"]["all"]["width"]; ?>px;
			}
			#smartnft-root .all-collection-main-container .collection-card-list, 
			#smartnft-root .all-collection-main-container .all-collections-list-skeleton,
			#smartnft-root .all-collection-main-container .all-collections-list{
					max-width: <?php echo $settings["collections"]["all"]["width"]; ?>px;
					margin: 0 auto;
			}


			/* single collection page */
			#smartnft-root  .single-collection.all-nfts, #smartnft-root .single-collection.all-nft-skeleton{
					grid-template-columns: repeat(<?php echo $settings["collections"]["single"]["cols"]; ?>, 1fr);
			}
			#smartnft-root .collection-container {
					max-width: <?php echo $settings["collections"]["single"]["width"]; ?>px;
			}



			/* create collection page */
			#smartnft-root .create-collection {
					max-width: <?php echo $settings["collections"]["single"]["width"]; ?>px;
			}


			/* single category page  */
			#smartnft-root  .single-category-page.all-nfts, #smartnft-root .single-category-page.all-nft-skeleton{
					grid-template-columns: repeat(<?php echo $settings["categories"]["single"]["cols"]; ?>, 1fr);
			}
			#smartnft-root .single-category {
					max-width: <?php echo $settings["categories"]["single"]["width"]; ?>px;
			}


			/*profile page */
			#smartnft-root .smart-nft-profile {
					max-width: <?php echo $settings["profile"]["single"]["width"]; ?>px;
			}
			#smartnft-root  .profile-page.all-nfts, #smartnft-root .profile-page.all-nft-skeleton{
					grid-template-columns: repeat(<?php echo $settings["profile"]["nfts"]["cols"]; ?>, 1fr);
			}

		
			/*profile page */
			#smartnft-root .edit-profile.smart-nft-profile {
					max-width: <?php echo $settings["profile"]["edit"]["width"]; ?>px;
		  }
			
			


			/* Single nft page */
			#smartnft-root .single-nft-info{
					max-width:<?php echo $settings["nftpages"]["single"]["width"]; ?>px;
			}
				
		  

<?php	
	}
}

new Smartnft_Dynamic_Css();
