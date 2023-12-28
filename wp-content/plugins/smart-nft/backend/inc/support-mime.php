<?php

class SmartNft_Support_Mime {
	function __construct() {
		add_filter( "upload_mimes", array( $this, "support_avif_file_type" ) );
	}

	function support_avif_file_type( $mimes ) {
		$mimes['avif'] = 'image/avif';
		return $mimes;
	}
}

new SmartNft_Support_Mime();
