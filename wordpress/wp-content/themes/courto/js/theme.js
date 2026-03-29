"use strict";

wglIsVisibleInit();

jQuery(document).ready(function ($) {
    wglInitZoom();
    wglContentMinHeight();
    wglStickyInit();
    wglSearchInit();
    wglSidePanelInit();
    wglMobileHeader();
    wglWoocommerceHelper();
    wglWoocommerceLoginIn();
    wglInitTimelineAppear();
    wglAccordionInit();
    wglAppear();
    wglServicesAccordionInit();
    wglStripedServicesInit();
    wglProgressBarsInit();
    wglCarouselSwiper();
    wglFilterSwiper();
    wglImageComparison();
    wglCounterInit();
    wglCountdownInit();
    wglImgLayers();
    wglPageTitleParallax();
    wglExtendedParallax();
    wglScrollUp();
    wglLinkOverlay();
    wglLinkScroll();
    wglSkrollrInit();
    wglStickySidebar();
    wglVideoboxInit();
    wglParallaxVideo();
    wglTabsHorizontalInit();
    wglShowcaseInit();
    wglHighlightBoardInit();
    wglCircuitService();
    wglSelectWrap();
    wglScrollAnimation();
    wglWoocommerceMiniCart();
    wglTextBackground();
    wglDynamicStyles();
    wglPieChartInit();
    wglButtonAnimation();
    wglPhysicsButton();
    wglTimelineHorizontal();
    wglButton();
    wglInitDblhAppear();
    wglListingsSearch();
    wglProfile();
    wglInfinityCarousel();
    wglMessageAnimInit();
    wglGalleryAnimation();
    wglAnimatedSideMenu();
    wglVideoHeroInit();
    wglMegaMenuOffset();
});

jQuery(window).load(function () {
    wglInfoboxInit();
    wglTabsInit();
    wglCursorInit();
    wglImagesGallery();
    wglIsotope();
    wglBlogMasonryInit();
    setTimeout(function(){
        jQuery('#preloader-wrapper').fadeOut();
    },1100);
    wglStickyScrollTabsInit();
    wglHorizontalScrollInit();
    wglTextEditor();

    wglParticlesCustom();
    wglParticlesImageCustom();

    document.addEventListener("wglParticlesAdded", function () {
        setTimeout(() => {
            wglParticlesCustom();
            wglParticlesImageCustom();
       }, 0);
    });

    wglMenuLavalamp();
    jQuery(".wgl-currency-stripe_scrolling").each(function(){
        jQuery(this).simplemarquee({
            speed: 40,
            space: 0,
            handleHover: true,
            handleResize: true
        });
    })

    wglPageTransition({
        triggers: 'a:not([data-elementor-open-lightbox="yes"]):not(.isotope-filter a)',
        disabled: false,
    });
    
    wglSmoothScroll();
	requestAnimationFrame(() => {
        if (typeof ScrollTrigger !== 'undefined' && ScrollTrigger.refresh) {
            ScrollTrigger.refresh();
        }
	});
});

jQuery(window).resize(function () {
    wglContentMinHeight();
    setTimeout(function(){
        wglInitZoom();
    },100);
})
