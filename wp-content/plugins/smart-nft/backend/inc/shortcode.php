<?php

class SmartNFTShortCodes{
    public function __construct(){
        add_shortcode('AllNftPage', [ $this, 'allNfts' ] );
        add_shortcode('AllNftCollections', [ $this, 'allNftCollections' ] );
        add_shortcode('EditeProfilePage', [ $this, 'edite_profile_page_content' ] );
        add_shortcode('ProfilePage', [ $this, 'profile_page_content' ] );

        add_shortcode('PublicProfilePage', [ $this, 'publicProfile' ] );
        add_shortcode('CreateCollectionPage', [ $this, 'create_collection_page' ] );
    }

    public function allNftCollections(){
        return '<div id="smartnft-root"></div>';
    }

    public function allNfts(){
        return '<div id="smartnft-root"></div>';
    }

    public function edite_profile_page_content () {
        return '<div id="smartnft-root"></div>';
    }

    public function profile_page_content () {
        return '<div id="smartnft-root"></div>';
    }

    public function create_collection_page() {
        return '<div id="smartnft-root" class="create-collection-page"></div>';
    }

    public function publicProfile(){
        return '<div id="smartnft-root"></div>';
    }
}
new SmartNFTShortCodes();
