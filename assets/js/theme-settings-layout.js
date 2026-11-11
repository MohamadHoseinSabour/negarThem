(function () {
  'use strict';

  var list = document.getElementById('negar-layout-sections');
  var status = document.getElementById('negar-layout-status');
  var draggedItem = null;

  if (!list) {
    return;
  }

  function items() {
    return Array.prototype.slice.call(list.querySelectorAll('.negar-layout-item'));
  }

  function itemLabel(item) {
    var label = item.querySelector('.negar-layout-item__title span');
    return label ? label.textContent.trim() : '';
  }

  function announce(message) {
    if (status) {
      status.textContent = message;
    }
  }

  function refreshControls() {
    var sectionItems = items();
    sectionItems.forEach(function (item, index) {
      var up = item.querySelector('[data-direction="up"]');
      var down = item.querySelector('[data-direction="down"]');
      if (up) {
        up.disabled = index === 0;
      }
      if (down) {
        down.disabled = index === sectionItems.length - 1;
      }
    });
  }

  function move(item, direction) {
    var sibling = direction === 'up' ? item.previousElementSibling : item.nextElementSibling;
    if (!sibling) {
      return false;
    }

    if (direction === 'up') {
      list.insertBefore(item, sibling);
    } else {
      list.insertBefore(sibling, item);
    }

    refreshControls();
    announce(itemLabel(item) + (direction === 'up' ? ' به بالا منتقل شد.' : ' به پایین منتقل شد.'));
    return true;
  }

  list.addEventListener('click', function (event) {
    var button = event.target.closest('.negar-layout-move');
    if (!button) {
      return;
    }

    var item = button.closest('.negar-layout-item');
    if (item && move(item, button.getAttribute('data-direction'))) {
      button.focus();
    }
  });

  list.addEventListener('dragstart', function (event) {
    var item = event.target.closest('.negar-layout-item');
    if (!item) {
      return;
    }

    draggedItem = item;
    item.classList.add('is-dragging');
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', item.getAttribute('data-section-id') || '');
  });

  list.addEventListener('dragover', function (event) {
    if (!draggedItem) {
      return;
    }

    event.preventDefault();
    var target = event.target.closest('.negar-layout-item');
    if (!target || target === draggedItem) {
      return;
    }

    var before = event.clientY < target.getBoundingClientRect().top + (target.offsetHeight / 2);
    list.insertBefore(draggedItem, before ? target : target.nextElementSibling);
  });

  list.addEventListener('drop', function (event) {
    if (draggedItem) {
      event.preventDefault();
      announce(itemLabel(draggedItem) + ' جابه‌جا شد.');
      refreshControls();
    }
  });

  list.addEventListener('dragend', function () {
    if (draggedItem) {
      draggedItem.classList.remove('is-dragging');
      draggedItem = null;
      refreshControls();
    }
  });

  refreshControls();
}());
