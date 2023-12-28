<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
window.addEventListener('load', function() {
    function replaceImageSrc(selector, newSrc) {
        var images = document.querySelectorAll(selector);

        images.forEach(function(image) {
            if (!image.dataset.replacedSrc) {
                console.log('Replacing src for', image.src);
                image.src = newSrc;
                image.dataset.replacedSrc = 'true'; // Set a flag to indicate replacement
            }
        });
    }

    // Replace search.svg immediately after page load
    replaceImageSrc('img[src$="smart-nft/frontend/assets/images/search.svg"]', 'https://mintmingle.ai/temporary/images/search.svg');
    
    // Replace verified.svg after a delay
    setTimeout(function() {
        replaceImageSrc('img[src$="smart-nft/frontend/assets/images/verified.svg"]', 'https://mintmingle.ai/temporary/images/verified.svg');
    }, 500); // Delay of 500 milliseconds
});
</script>
<!-- end Simple Custom CSS and JS -->
