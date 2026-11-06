/**
 * Negar Shop - Homepage Interactivity Script
 * 
 * @package Negar_Shop
 * @version 1.0.0
 */

(function () {
  'use strict';

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
    const landingEl = document.querySelector('.swiper-landing-nk');
    if (landingEl) {
      new Swiper(landingEl, {
        loop: true,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        navigation: {
          prevEl: '.swiper-button-prev',
          nextEl: '.swiper-button-next',
        },
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },
      });
    }

    // 2. Landing Flash Product (Left box in Hero)
    const landingProdEl = document.querySelector('.swiper-landing-product-nk');
    if (landingProdEl) {
      new Swiper(landingProdEl, {
        effect: 'fade',
        fadeEffect: { crossFade: true },
        pagination: {
          el: '.landing-nk__products .swiper-pagination',
          clickable: true,
        },
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

    // 5. Generic Data-Swiper Elements (Brands, Selling Products, etc.)
    const swiperDataElements = document.querySelectorAll('.swiper[data-swiper]');
    swiperDataElements.forEach(function(swiperEl) {
      if (swiperEl && !swiperEl.classList.contains('swiper-initialized')) {
        try {
          const configStr = swiperEl.getAttribute('data-swiper');
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
   * DOM Ready Initializer
   */
  function init() {
    initHeroSlider();
    initCountdownTimer();
    initDealsCarousel();
    initProductTabs();
    initKavehSwipers();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
