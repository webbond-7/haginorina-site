/* =========================================================
   Haginorina Official Fan Site — script.js
   ---------------------------------------------------------
   - Hero title char-in stagger
   - Hero image slider
   - Scroll reveal
   - Mobile nav toggle
   ========================================================= */

(function () {
  'use strict';

  /* 0. Site loader — hide after first load; skip on subsequent in-session navigations.
     Append ?loader to the URL to always show the loader (preview); does not set h_loaded. */
  (function () {
    var loader = document.getElementById('siteLoader');
    if (!loader) return;
    var loaderPreview = false;
    try {
      loaderPreview = new URLSearchParams(location.search).has('loader');
    } catch (e) {}
    // If user already saw the loader this session, mark immediately so no flash on hide
    if (sessionStorage.getItem('h_loaded') && !loaderPreview) {
      loader.classList.add('is-hidden');
      return;
    }
    function hide() {
      loader.classList.add('is-hidden');
      if (!loaderPreview) sessionStorage.setItem('h_loaded', '1');
    }
    var MIN_SHOW = 1100; // ms — minimum display time so the animation is visible
    var t0 = performance.now();
    function ready() {
      var elapsed = performance.now() - t0;
      var wait = Math.max(0, MIN_SHOW - elapsed);
      setTimeout(hide, wait);
    }
    if (document.readyState === 'complete') ready();
    else window.addEventListener('load', ready);
  })();

  /* 1. Hero title — split into chars and stagger animation */
  document.addEventListener('DOMContentLoaded', function () {
    var title = document.querySelector('[data-split-chars]');
    if (title) {
      var text = title.textContent.trim();
      title.textContent = '';
      Array.from(text).forEach(function (ch, i) {
        var span = document.createElement('span');
        span.className = 'char';
        span.textContent = ch;
        span.style.animationDelay = (0.08 * i + 0.25) + 's';
        title.appendChild(span);
      });
    }

    /* 2. Hero slider — preloaded for even cross-fade */
    var slides = document.querySelectorAll('.hero-slide');
    var dots = document.querySelectorAll('.slide-indicator button');
    if (slides.length > 1) {
      var idx = 0;
      var timer = null;
      var INTERVAL = 6500;

      // Preload all images so first cycle doesn't stutter
      slides.forEach(function (s) {
        var img = s.querySelector('img');
        if (img && img.src) {
          var pre = new Image();
          pre.src = img.src;
        }
      });

      function go(i) {
        // Freeze the outgoing slide's current zoom so it doesn't snap back during fadeout
        var outImg = slides[idx].querySelector('img');
        if (outImg) {
          var t = getComputedStyle(outImg).transform;
          outImg.style.transform = (t && t !== 'none') ? t : 'scale(1.02)';
          outImg.style.animation = 'none';
        }
        slides[idx].classList.remove('is-active');
        if (dots[idx]) dots[idx].classList.remove('is-active');

        idx = (i + slides.length) % slides.length;

        // Reset the incoming slide so its zoom restarts from 1.02 freshly
        var inImg = slides[idx].querySelector('img');
        if (inImg) {
          inImg.style.animation = 'none';
          inImg.style.transform = 'scale(1.02)';
          void inImg.offsetWidth; // force reflow so the next change re-triggers the animation
          inImg.style.animation = '';
          inImg.style.transform = '';
        }
        slides[idx].classList.add('is-active');
        if (dots[idx]) dots[idx].classList.add('is-active');
      }
      function start() {
        stop();
        timer = setInterval(function () { go(idx + 1); }, INTERVAL);
      }
      function stop() { if (timer) { clearInterval(timer); timer = null; } }

      dots.forEach(function (d, i) {
        d.addEventListener('click', function () { go(i); start(); });
      });

      // Pause when tab is not visible (avoids burst of switches on return)
      document.addEventListener('visibilitychange', function () {
        if (document.hidden) stop(); else start();
      });

      start();
    }

    /* 3. Scroll reveal */
    var revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && revealEls.length) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) {
            e.target.classList.add('is-in');
            io.unobserve(e.target);
          }
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
      revealEls.forEach(function (el) { io.observe(el); });
    } else {
      revealEls.forEach(function (el) { el.classList.add('is-in'); });
    }

    /* 4. Mobile nav */
    var toggle = document.querySelector('.nav-toggle');
    var mNav = document.querySelector('.mobile-nav');
    if (toggle && mNav) {
      toggle.addEventListener('click', function () {
        var open = mNav.classList.toggle('is-open');
        toggle.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'メニューを閉じる' : 'メニュー');
        document.body.style.overflow = open ? 'hidden' : '';
      });
      mNav.querySelectorAll('a').forEach(function (a) {
        a.addEventListener('click', function () {
          mNav.classList.remove('is-open');
          toggle.classList.remove('is-open');
          toggle.setAttribute('aria-expanded', 'false');
          toggle.setAttribute('aria-label', 'メニュー');
          document.body.style.overflow = '';
        });
      });
    }

    /* 5a. (mouse-avoid removed per design feedback — decorations float ambient only) */

    /* 5b. Click ripple — two expanding rings at the click point */
    (function () {
      // Skip when user clicks an interactive element? Keep it everywhere for delight,
      // but cap concurrent ripples.
      var live = 0;
      function spawn(x, y) {
        if (live > 6) return;
        var size = 260;
        for (var i = 0; i < 2; i++) {
          var r = document.createElement('span');
          r.className = 'ripple' + (i ? ' ring-2' : '');
          r.style.left = x + 'px';
          r.style.top = y + 'px';
          r.style.width = size + 'px';
          r.style.height = size + 'px';
          document.body.appendChild(r);
          live++;
          (function (node) {
            node.addEventListener('animationend', function () {
              node.remove();
              live--;
            });
          })(r);
        }
      }
      document.addEventListener('click', function (e) {
        // Ignore clicks on actual form controls' interactions so submit/select still feel native
        var t = e.target;
        if (t.closest && t.closest('input, select, textarea')) return;
        spawn(e.clientX, e.clientY);
      });
    })();

    /* 5. Parallax decoration on scroll (subtle) */
    var parallaxEls = document.querySelectorAll('[data-parallax]');
    if (parallaxEls.length) {
      window.addEventListener('scroll', function () {
        var y = window.scrollY;
        parallaxEls.forEach(function (el) {
          var s = parseFloat(el.getAttribute('data-parallax')) || 0.08;
          el.style.transform = 'translateY(' + (y * s).toFixed(2) + 'px)';
        });
      }, { passive: true });
    }

    /* 6. Header active-nav highlight via section scroll */
    var sections = document.querySelectorAll('[data-section]');
    var navLinks = document.querySelectorAll('[data-nav-link]');
    if (sections.length && navLinks.length && 'IntersectionObserver' in window) {
      var navIO = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) {
            var id = e.target.getAttribute('data-section');
            navLinks.forEach(function (l) {
              l.classList.toggle('is-active', l.getAttribute('data-nav-link') === id);
            });
          }
        });
      }, { rootMargin: '-40% 0px -55% 0px' });
      sections.forEach(function (s) { navIO.observe(s); });
    }
    /* 7. Lightbox — click any gallery-item inside [data-lightbox] to view enlarged */
    (function () {
      var container = document.querySelector('[data-lightbox]');
      if (!container) return;
      var items = Array.from(container.querySelectorAll('.gallery-item'));
      if (!items.length) return;

      // Collect <img> sources (skip items that only have a placeholder)
      var entries = items
        .map(function (a) {
          var img = a.querySelector('img');
          return img ? { src: img.src, alt: img.alt || '' } : null;
        })
        .filter(Boolean);

      if (!entries.length) return;

      // Build lightbox markup once
      var lb = document.createElement('div');
      lb.className = 'lightbox';
      lb.setAttribute('role', 'dialog');
      lb.setAttribute('aria-modal', 'true');
      lb.setAttribute('aria-label', 'ギャラリー表示');
      lb.innerHTML =
        '<div class="lb-counter" aria-live="polite"></div>' +
        '<button class="lb-btn lb-close" aria-label="閉じる"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>' +
        '<button class="lb-btn lb-prev" aria-label="前の写真"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></button>' +
        '<button class="lb-btn lb-next" aria-label="次の写真"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>' +
        '<div class="lightbox-stage">' +
          '<img class="lightbox-img" alt="" />' +
          '<div class="lightbox-caption"></div>' +
        '</div>';
      document.body.appendChild(lb);

      var lbImg = lb.querySelector('.lightbox-img');
      var lbCap = lb.querySelector('.lightbox-caption');
      var lbCount = lb.querySelector('.lb-counter');
      var idxLB = 0;

      function show(i) {
        idxLB = (i + entries.length) % entries.length;
        var e = entries[idxLB];
        lbImg.src = e.src;
        lbImg.alt = e.alt;
        lbCap.textContent = e.alt;
        lbCount.textContent = (idxLB + 1) + ' / ' + entries.length;
      }

      function open(i) {
        show(i);
        lb.classList.add('is-open');
        document.body.style.overflow = 'hidden';
      }
      function close() {
        lb.classList.remove('is-open');
        document.body.style.overflow = '';
      }

      items.forEach(function (a, i) {
        // only wire up items that actually have an image
        if (!a.querySelector('img')) return;
        a.addEventListener('click', function (e) {
          e.preventDefault();
          // figure out actual entries index
          var src = a.querySelector('img').src;
          var entryIdx = entries.findIndex(function (en) { return en.src === src; });
          open(entryIdx >= 0 ? entryIdx : 0);
        });
      });

      lb.querySelector('.lb-close').addEventListener('click', close);
      lb.querySelector('.lb-prev').addEventListener('click', function (e) { e.stopPropagation(); show(idxLB - 1); });
      lb.querySelector('.lb-next').addEventListener('click', function (e) { e.stopPropagation(); show(idxLB + 1); });
      lb.addEventListener('click', function (e) {
        // click backdrop (not the stage / buttons) closes
        if (e.target === lb) close();
      });

      document.addEventListener('keydown', function (e) {
        if (!lb.classList.contains('is-open')) return;
        if (e.key === 'Escape') close();
        else if (e.key === 'ArrowLeft') show(idxLB - 1);
        else if (e.key === 'ArrowRight') show(idxLB + 1);
      });

      // Swipe support on touch devices
      var touchX = null;
      lb.addEventListener('touchstart', function (e) {
        touchX = e.touches[0].clientX;
      }, { passive: true });
      lb.addEventListener('touchend', function (e) {
        if (touchX == null) return;
        var dx = e.changedTouches[0].clientX - touchX;
        if (Math.abs(dx) > 40) show(idxLB + (dx < 0 ? 1 : -1));
        touchX = null;
      });
    })();

  });
})();
