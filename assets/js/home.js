/**
 * Negar Shop - Homepage Interactivity Script
 * 
 * @package Negar_Shop
 * @version 1.0.0
 */

(function () {
  'use strict';

  function applyHeroMediaRecords() {
    document.querySelectorAll('[data-negar-hero-media]').forEach(function (slide) {
      var image = slide.querySelector('img');
      var link = slide.querySelector('.detail a.btn');
      if (image && slide.dataset.negarMediaImage) {
        image.src = slide.dataset.negarMediaImage;
        image.alt = slide.dataset.negarMediaAlt || '';
      }
      if (link && slide.dataset.negarMediaLink) {
        link.href = slide.dataset.negarMediaLink;
      }
    });
  }

  function applyBannerMediaRecords() {
    document.querySelectorAll('[data-negar-banner]').forEach(function (banner) {
      var image = banner.querySelector('img');
      var link = banner.querySelector('a');
      var title = banner.querySelector('.title');
      var subtitle = banner.querySelector('.sub-title');

      if (banner.dataset.negarMediaEnabled === '0') {
        banner.hidden = true;
        return;
      }
      if (image && banner.dataset.negarMediaImage) {
        image.src = banner.dataset.negarMediaImage;
        image.alt = banner.dataset.negarMediaAlt || '';
      }
      if (link && banner.dataset.negarMediaLink) {
        link.href = banner.dataset.negarMediaLink;
        link.setAttribute('aria-label', banner.dataset.negarMediaCta || link.getAttribute('aria-label') || '');
      }
      if (title && typeof banner.dataset.negarMediaTitle !== 'undefined') {
        title.textContent = banner.dataset.negarMediaTitle;
      }
      if (subtitle && typeof banner.dataset.negarMediaSubtitle !== 'undefined') {
        subtitle.textContent = banner.dataset.negarMediaSubtitle;
      }
    });
  }

  /**
   * Utility: Convert Latin Numbers to Persian Digits
   */
  function toPersianDigits(num) {
    const persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    const formatted = String(num).padStart(2, '0');
    return formatted.replace(/[0-9]/g, function (w) {
      return persianDigits[+w];
    });
  }

  /**
   * 1. Hero Slider Controller
   */
  function initHeroSlider() {
    const sliderContainer = document.querySelector('.hero-slider-container');
    if (!sliderContainer) return;

    const slides = sliderContainer.querySelectorAll('.hero-slide');
    const dots = sliderContainer.querySelectorAll('.hero-dot');
    const prevBtn = sliderContainer.querySelector('.hero-nav-prev');
    const nextBtn = sliderContainer.querySelector('.hero-nav-next');

    if (!slides.length) return;

    let currentIndex = 0;
    let autoPlayTimer = null;

    function goToSlide(index) {
      slides.forEach((slide, i) => {
        if (i === index) {
          slide.classList.add('active');
        } else {
          slide.classList.remove('active');
        }
      });

      dots.forEach((dot, i) => {
        if (i === index) {
          dot.classList.add('active');
        } else {
          dot.classList.remove('active');
        }
      });

      currentIndex = index;
    }

    function nextSlide() {
      const nextIndex = (currentIndex + 1) % slides.length;
      goToSlide(nextIndex);
    }

    function prevSlide() {
      const prevIndex = (currentIndex - 1 + slides.length) % slides.length;
      goToSlide(prevIndex);
    }

    function startAutoPlay() {
      stopAutoPlay();
      autoPlayTimer = setInterval(nextSlide, 5000);
    }

    function stopAutoPlay() {
      if (autoPlayTimer) clearInterval(autoPlayTimer);
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        nextSlide();
        startAutoPlay();
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        prevSlide();
        startAutoPlay();
      });
    }

    dots.forEach((dot) => {
      dot.addEventListener('click', function () {
        const slideIdx = parseInt(this.getAttribute('data-slide'), 10);
        if (!isNaN(slideIdx)) {
          goToSlide(slideIdx);
          startAutoPlay();
        }
      });
    });

    sliderContainer.addEventListener('mouseenter', stopAutoPlay);
    sliderContainer.addEventListener('mouseleave', startAutoPlay);

    startAutoPlay();
  }

  /**
   * 2. Flash Deals Countdown Timer
   */
  function initCountdownTimer() {
    const timerElements = document.querySelectorAll('.negar-countdown');

    timerElements.forEach((el) => {
      const endTimeAttr = el.getAttribute('data-endtime');
      if (!endTimeAttr) return;

      const endTime = parseInt(endTimeAttr, 10);
      const hoursEl = el.querySelector('.countdown-hours');
      const minutesEl = el.querySelector('.countdown-minutes');
      const secondsEl = el.querySelector('.countdown-seconds');

      function updateClock() {
        const now = Math.floor(Date.now() / 1000);
        const distance = endTime - now;

        if (distance <= 0) {
          if (hoursEl) hoursEl.textContent = '۰۰';
          if (minutesEl) minutesEl.textContent = '۰۰';
          if (secondsEl) secondsEl.textContent = '۰۰';
          return;
        }

        const hours = Math.floor((distance % (3600 * 24)) / 3600);
        const minutes = Math.floor((distance % 3600) / 60);
        const seconds = Math.floor(distance % 60);

        if (hoursEl) hoursEl.textContent = toPersianDigits(hours);
        if (minutesEl) minutesEl.textContent = toPersianDigits(minutes);
        if (secondsEl) secondsEl.textContent = toPersianDigits(seconds);
      }

      updateClock();
      setInterval(updateClock, 1000);
    });
  }

  /**
   * 3. Flash Deals Carousel Navigation Controls
   */
  function initDealsCarousel() {
    const carousel = document.querySelector('.flash-deals-carousel');
    const prevBtn = document.querySelector('.deals-nav-prev');
    const nextBtn = document.querySelector('.deals-nav-next');

    if (!carousel) return;

    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        carousel.scrollBy({ left: -260, behavior: 'smooth' });
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        carousel.scrollBy({ left: 260, behavior: 'smooth' });
      });
    }
  }

  /**
   * 4. Product Tabs Switching Controller
   */
  function initProductTabs() {
    const tabTriggers = document.querySelectorAll('.product-tab-buttons .tab-trigger');
    const tabPanels = document.querySelectorAll('.product-tab-panel');

    tabTriggers.forEach((trigger) => {
      trigger.addEventListener('click', function () {
        const targetSelector = this.getAttribute('data-target');
        if (!targetSelector) return;

        tabTriggers.forEach((btn) => btn.classList.remove('active'));
        this.classList.add('active');

        tabPanels.forEach((panel) => {
          panel.classList.add('hidden');
          panel.classList.remove('active');
        });

        const targetPanel = document.querySelector(targetSelector);
        if (targetPanel) {
          targetPanel.classList.remove('hidden');
          targetPanel.classList.add('active');
        }
      });
    });
  }

  /**
   * 5. Kaveh Demo 43 Swiper Carousels Initializer
   */
  function initKavehSwipers() {
    if (typeof Swiper === 'undefined') return;

    // 1. Landing Hero Carousel
    const landingContainer = document.querySelector('.landing-nk__carousel');
    const landingEl = landingContainer ? landingContainer.querySelector('.swiper-landing-nk') : document.querySelector('.swiper-landing-nk');
    if (landingEl && !landingEl.classList.contains('swiper-initialized')) {
      const prevBtn = landingContainer ? landingContainer.querySelector('.swiper-button-prev') : landingEl.parentElement?.querySelector('.swiper-button-prev');
      const nextBtn = landingContainer ? landingContainer.querySelector('.swiper-button-next') : landingEl.parentElement?.querySelector('.swiper-button-next');
      new Swiper(landingEl, {
        loop: true,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        speed: 600,
        navigation: {
          prevEl: prevBtn,
          nextEl: nextBtn,
        },
        autoplay: {
          delay: 5500,
          disableOnInteraction: false,
          pauseOnMouseEnter: true,
        },
      });
    }

    // 2. Landing Flash Product (Left box in Hero)
    const landingProdContainer = document.querySelector('.landing-nk__products');
    const landingProdEl = landingProdContainer ? landingProdContainer.querySelector('.swiper-landing-product-nk') : document.querySelector('.swiper-landing-product-nk');
    if (landingProdEl && !landingProdEl.classList.contains('swiper-initialized')) {
      const pagEl = landingProdContainer ? landingProdContainer.querySelector('.swiper-pagination') : null;
      new Swiper(landingProdEl, {
        effect: 'fade',
        fadeEffect: { crossFade: true },
        pagination: pagEl ? {
          el: pagEl,
          clickable: true,
        } : false,
      });
    }

    // 3. Category Bubbles Slider
    const catEl = document.querySelector('.swiper-categories-nk');
    if (catEl) {
      new Swiper(catEl, {
        watchSlidesProgress: true,
        watchOverflow: true,
        slidesPerView: 2.1,
        spaceBetween: 12,
        breakpoints: {
          320: { slidesPerView: 2.1, spaceBetween: 12 },
          576: { slidesPerView: 3, spaceBetween: 12 },
          768: { slidesPerView: 3, spaceBetween: 12 },
          992: { slidesPerView: 5, spaceBetween: 12 },
          1200: { slidesPerView: 5, spaceBetween: 12 },
        },
      });
    }

    // 4. Popular Deals Slider (Initialize all instances across the page)
    const popularElements = document.querySelectorAll('.swiper-products-popular');
    popularElements.forEach(function(popularEl) {
      if (popularEl && !popularEl.classList.contains('swiper-initialized')) {
        new Swiper(popularEl, {
          watchSlidesProgress: true,
          slidesPerView: 1.5,
          spaceBetween: 10,
          breakpoints: {
            320: { slidesPerView: 1.5, spaceBetween: 10 },
            768: { slidesPerView: 2.2, spaceBetween: 12 },
            992: { slidesPerView: 2.8, spaceBetween: 14 },
            1200: { slidesPerView: 3.2, spaceBetween: 14 },
          },
        });
      }
    });

    // 5. Generic Data-Swiper Elements (Brands, Selling Products, Top-View, etc.)
    const swiperDataElements = document.querySelectorAll('.swiper[data-swiper], .swiper[data-swiper-config]');
    swiperDataElements.forEach(function(swiperEl) {
      if (swiperEl && !swiperEl.classList.contains('swiper-initialized')) {
        try {
          const configStr = swiperEl.getAttribute('data-swiper') || swiperEl.getAttribute('data-swiper-config');
          const config = configStr ? JSON.parse(configStr) : {};
          new Swiper(swiperEl, config);
          swiperEl.setAttribute('data-kaveh-swiper-mounted', '1');
        } catch(e) {
          console.warn('Swiper init error', e);
        }
      }
    });
  }

  /**
   * Video Review Popup / Modal Handler (Demo 43 parity)
   */
  function initVideoPopups() {
    const playButtons = document.querySelectorAll('.videos-nk__play');
    playButtons.forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const popup = document.getElementById('videos-nk-popup-39f2483d') || document.querySelector('.videos-nk-popup');
        if (!popup) return;

        const videoSrc = btn.getAttribute('data-video-src');
        const player = popup.querySelector('[data-videos-nk-player]');
        if (player && videoSrc) {
          player.innerHTML = '<iframe src="' + videoSrc + '?autoplay=1" allow="autoplay; fullscreen" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>';
        }

        popup.removeAttribute('hidden');
        popup.setAttribute('aria-hidden', 'false');
        popup.classList.add('is-open');
        document.body.classList.add('videos-nk-popup-open');
      });
    });

    document.addEventListener('click', function(e) {
      const closeTarget = e.target.closest('[data-videos-nk-close]');
      if (closeTarget) {
        const popup = closeTarget.closest('.videos-nk-popup');
        if (popup) {
          closePopup(popup);
        }
      }
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        const openPopup = document.querySelector('.videos-nk-popup.is-open');
        if (openPopup) {
          closePopup(openPopup);
        }
      }
    });

    function closePopup(popup) {
      popup.classList.remove('is-open');
      popup.setAttribute('hidden', '');
      popup.setAttribute('aria-hidden', 'true');
      const player = popup.querySelector('[data-videos-nk-player]');
      if (player) {
        player.innerHTML = '';
      }
      document.body.classList.remove('videos-nk-popup-open');
    }
  }

  /**
   * DOM Ready Initializer
   */
  function init() {
	applyHeroMediaRecords();
	applyBannerMediaRecords();
    initHeroSlider();
    initCountdownTimer();
    initDealsCarousel();
    initProductTabs();
    initKavehSwipers();
    initVideoPopups();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
