/**
 * Kaveh Product Image Magnifier
 *
 * Initialises jQuery Magnify (https://thdoan.github.io/magnify/) on the
 * WooCommerce single product main image. Settings are passed from PHP via
 * window.kavehZoomOptions (wp_localize_script).
 */
(function ($) {
    'use strict';

    if (!$ || typeof $.fn.magnify !== 'function') {
        return;
    }

    var cfg = window.kavehZoomOptions || {};
    if (!cfg.enabled) {
        return;
    }

    // Touch / mobile guard.
    // PHP already short-circuits on wp_is_mobile() devices, so reaching this
    // point means we're either on a non-mobile UA or wp_is_mobile() missed it
    // (common for iPadOS 13+, which masquerades as desktop Safari). Use a
    // belt-and-suspenders client check covering touch APIs, coarse-pointer
    // media query, and a narrow-viewport fallback.
    // cfg.disableMobile arrives as 1 / 0 (int) from wp_localize_script.
    var disableMobile = cfg.disableMobile === 1 ||
        cfg.disableMobile === '1' ||
        cfg.disableMobile === true;
    var hasTouch = ('ontouchstart' in window) ||
        (navigator.maxTouchPoints > 0) ||
        (navigator.msMaxTouchPoints > 0);
    var coarsePointer = !!(window.matchMedia && window.matchMedia('(pointer: coarse)').matches);
    var narrowViewport = (window.innerWidth || document.documentElement.clientWidth || 0) < 768;
    var isMobileLike = (hasTouch && coarsePointer) || narrowViewport;
    if (disableMobile && isMobileLike) {
        return;
    }

    var zoomFactor = parseFloat(cfg.zoomLevel) || 2.5;
    var lensSize   = parseInt(cfg.lensSize, 10) || 200;
    var speed      = parseInt(cfg.speed, 10);
    if (isNaN(speed)) { speed = 120; }

    // Inject lens-size override (CSS file ships with 200px default).
    function injectLensCss() {
        if (document.getElementById('kaveh-magnify-size')) { return; }
        var style = document.createElement('style');
        style.id = 'kaveh-magnify-size';
        style.appendChild(document.createTextNode(
            '.magnify > .magnify-lens{width:' + lensSize + 'px;height:' + lensSize + 'px;}'
        ));
        document.head.appendChild(style);
    }

    function getZoomSrc(img) {
        // WooCommerce sets data-large_image on the gallery image.
        return img.getAttribute('data-large_image') ||
            img.getAttribute('data-magnify-src') ||
            img.currentSrc ||
            img.src;
    }

    function destroyAll() {
        // Remove lenses and unwrap any existing .magnify containers.
        $('.magnify > .magnify-lens').remove();
        $('.magnify > img').each(function () {
            var $img = $(this);
            $img.off('mousemove touchmove mouseenter mouseleave touchend');
            $img.unwrap();
            $img.removeData('kavehZoomBound');
        });
    }

    function bindImage(img) {
        var $img = $(img);
        if ($img.data('kavehZoomBound')) { return; }

        var zoomSrc = getZoomSrc(img);
        if (!zoomSrc) { return; }

        var natW = img.naturalWidth  || 0;
        var natH = img.naturalHeight || 0;

        var dispW = $img.width()  || natW;
        var dispH = $img.height() || natH;

        var magW = Math.round(dispW * zoomFactor);
        var magH = Math.round(dispH * zoomFactor);

        // Cap magnified dims to the natural source so we don't upscale.
        if (natW && magW > natW) { magW = natW; }
        if (natH && magH > natH) { magH = natH; }

        $img.attr('data-magnify-src', zoomSrc);
        if (magW > 0 && magH > 0) {
            $img.attr('data-magnify-magnifiedwidth',  magW);
            $img.attr('data-magnify-magnifiedheight', magH);
        }
        $img.addClass('zoom');

        try {
            $img.magnify({
                speed: speed,
                limitBounds: true
            });
            $img.data('kavehZoomBound', true);
        } catch (e) {
            // Silent fail — magnifier is purely an enhancement.
        }
    }

    function initAll() {
        destroyAll();
        injectLensCss();
        $('.woocommerce-product-gallery__wrapper img.wp-post-image, .woocommerce-product-gallery__image img').each(function () {
            var img = this;
            if (img.complete && img.naturalWidth) {
                bindImage(img);
            } else {
                $(img).one('load', function () { bindImage(img); });
            }
        });
    }

    $(function () {
        initAll();

        // Re-bind on variation change / gallery swap (WooCommerce core Variation Gallery).
        $(document.body).on(
            'found_variation reset_image woocommerce_gallery_init_zoom wc-product-gallery-after-init',
            function () {
            setTimeout(initAll, 120);
        });

        // Thumbnail click in custom kaveh gallery layouts.
        $(document).on(
            'click',
            '.swiper-gallery-product-two .swiper-slide, .swiper-gallery-product-three .swiper-slide, .woocommerce-product-gallery__image a',
            function () { setTimeout(initAll, 150); }
        );
    });
})(window.jQuery);
