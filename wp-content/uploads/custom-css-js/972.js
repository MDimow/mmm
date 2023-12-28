<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
window.addEventListener('load', function() {
    setTimeout(function() {
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

        replaceImageSrc('img[src$="/wp-content/plugins/smart-nft/frontend/assets/images/search.svg"]', 'https://mintmingle.ai/temporary/images/search.svg');
        replaceImageSrc('img[src$="jsdistus/frontend/assets/images/verified.svg"]', 'https://mintmingle.ai/temporary/images/verified.svg');
    }, 1000); // Delay of 1 second
});
</script>
<!-- end Simple Custom CSS and JS -->
