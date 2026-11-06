/**
 * Negar Shop - Core JavaScript Controller
 * 
 * @package Negar_Shop
 * @version 1.0.0
 */

(function () {
  'use strict';

  // Initialize Namespace
  window.NegarShop = window.NegarShop || {
    version: '1.0.0',
    modules: {},
    params: window.negarShopParams || {},
  };

  /**
   * Event Dispatcher Helper
   */
  NegarShop.trigger = function (eventName, detail) {
    const event = new CustomEvent('negar:' + eventName, {
      bubbles: true,
      cancelable: true,
      detail: detail || {},
    });
    document.dispatchEvent(event);
  };

  /**
   * Toast Notification System
   */
  NegarShop.toast = function (message, type = 'success') {
    let container = document.getElementById('negar-toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'negar-toast-container';
      container.style.cssText = `
        position: fixed;
        bottom: 24px;
        left: 24px;
        z-index: 999999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        pointer-events: none;
      `;
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `negar-toast toast-${type}`;
    const bgColor = type === 'success' ? '#059669' : type === 'error' ? '#dc2626' : '#ea580c';
    toast.style.cssText = `
      background-color: ${bgColor};
      color: #ffffff;
      padding: 12px 20px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 500;
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
      direction: rtl;
      text-align: right;
      pointer-events: auto;
      transform: translateY(20px);
      opacity: 0;
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    `;
    toast.textContent = message;
    container.appendChild(toast);

    // Animate In
    requestAnimationFrame(() => {
      toast.style.transform = 'translateY(0)';
      toast.style.opacity = '1';
    });

    // Animate Out
    setTimeout(() => {
      toast.style.transform = 'translateY(-10px)';
      toast.style.opacity = '0';
      setTimeout(() => {
        if (toast.parentNode) {
          toast.parentNode.removeChild(toast);
        }
      }, 300);
    }, 3500);
  };

  /**
   * Accessibility Helper: Skip Link Focus Fix
   */
  function initSkipLinkFocus() {
    const isIe = /(trident|msie)/i.test(navigator.userAgent);
    if (isIe && document.getElementById && window.addEventListener) {
      window.addEventListener(
        'hashchange',
        function () {
          const id = location.hash.substring(1);
          if (!/^[A-z0-9_-]+$/.test(id)) {
            return;
          }
          const element = document.getElementById(id);
          if (element) {
            if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
              element.tabIndex = -1;
            }
            element.focus();
          }
        },
        false
      );
    }
  }

  /**
   * DOM Ready Initializer
   */
  function init() {
    initSkipLinkFocus();
    NegarShop.trigger('ready');
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
