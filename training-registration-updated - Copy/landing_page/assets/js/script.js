// Main JS for LetsCode landing page

if (typeof AOS !== 'undefined') {
  AOS.init({
    duration: 800,
    once: true
  });
}

// Back to top button
const backToTop = document.getElementById('backToTop');
if (backToTop) {
  window.addEventListener('scroll', () => {
    if (window.scrollY > 300) backToTop.style.display = 'block';
    else backToTop.style.display = 'none';
  });

  backToTop.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

// Loader hide on load
window.addEventListener('load', function () {
  const loader = document.querySelector('.loader');
  if (loader) {
    loader.classList.add('hide');
    setTimeout(() => loader.remove(), 800);
  }
});

// Carousel initializer
function initCarousel(wrapperSelector) {
  const wrappers = document.querySelectorAll(wrapperSelector);
  wrappers.forEach(wrapper => {
    const track = wrapper.querySelector('.carousel-track');
    const prev = wrapper.querySelector('.carousel-btn.prev');
    const next = wrapper.querySelector('.carousel-btn.next');

    if (!track || !prev || !next) return;

    const items = Array.from(track.children).filter(child => child.matches("[class*=col-]"));
    if (items.length === 0) return;

    // activeIndex: which card is highlighted (0..items.length-1)
    let activeIndex = Math.floor(Math.min(1, items.length - 1));

    const getVisibleItems = () => {
      if (window.innerWidth <= 767) return 1;
      if (window.innerWidth <= 991) return 2;
      return 3;
    };

    const getMaxPage = () => Math.max(0, items.length - getVisibleItems());

    const updateHighlight = (activeIdx) => {
      items.forEach((item, i) => {
        const isActive = i === activeIdx;
        const isNeighbor = Math.abs(i - activeIdx) === 1;
        item.classList.toggle('carousel-item-active', isActive);
        item.classList.toggle('carousel-item-dim', isNeighbor && !isActive);
        item.classList.toggle('carousel-item-hidden', !isActive && !isNeighbor);
      });
    };

    const computeSlideWidth = () => {
      if (items.length > 1) {
        const a = items[0].getBoundingClientRect();
        const b = items[1].getBoundingClientRect();
        return Math.abs(b.left - a.left);
      }
      const gap = parseFloat(getComputedStyle(track).gap) || 40;
      const w = items[0].getBoundingClientRect().width;
      return w + gap;
    };

    const updatePosition = () => {
      const visibleItems = getVisibleItems();
      const maxPage = getMaxPage();

      if (activeIndex < 0) activeIndex = 0;
      if (activeIndex > items.length - 1) activeIndex = items.length - 1;

      const centerOffset = Math.floor(visibleItems / 2);
      let currentPage = activeIndex - centerOffset;
      if (currentPage < 0) currentPage = 0;
      if (currentPage > maxPage) currentPage = maxPage;

      const width = computeSlideWidth();
      track.style.transform = `translateX(-${currentPage * width}px)`;

      // keep buttons always enabled (we do looping)
      prev.disabled = false;
      next.disabled = false;
      prev.classList.remove('disabled');
      next.classList.remove('disabled');

      updateHighlight(activeIndex);
    };

    // smooth fade on wrap
    const doMove = (newIndex, wrap) => {
      if (wrap) {
          // fade out the current active card, jump, then fade in the new start card
          const prevItem = items[activeIndex];
          if (prevItem) prevItem.classList.add('wrap-out');

          setTimeout(() => {
            if (prevItem) prevItem.classList.remove('wrap-out');

            // set new active and update layout
            activeIndex = newIndex;
            updatePosition();

            // animate the incoming card from transparent to visible
            const newItem = items[activeIndex];
            if (newItem) {
              newItem.classList.add('wrap-in');
              // force reflow so transition applies
              // eslint-disable-next-line no-unused-expressions
              newItem.offsetWidth;
              newItem.classList.remove('wrap-in');
            }
          }, 220);
      } else {
        activeIndex = newIndex;
        updatePosition();
      }
    };

    // attach handlers
    next.addEventListener('click', () => {
      const newIndex = (activeIndex + 1) % items.length;
      const wrap = activeIndex === items.length - 1 && newIndex === 0;
      doMove(newIndex, wrap);
    });

    prev.addEventListener('click', () => {
      const newIndex = (activeIndex - 1 + items.length) % items.length;
      const wrap = activeIndex === 0 && newIndex === items.length - 1;
      doMove(newIndex, wrap);
    });

    // ensure the track has transform transition for normal moves
    track.style.transition = 'transform 0.4s ease';
    track.style.willChange = 'transform, opacity';

    window.addEventListener('resize', updatePosition);
    // initial position
    // start with center-ish index so first view matches earlier behaviour
    activeIndex = Math.min(Math.floor(getVisibleItems() / 2), items.length - 1);
    updatePosition();
  });
}

// Stats counter animation with randomized targets
function initCounters() {
  const counters = document.querySelectorAll('.counter');
  if (!counters.length) return;

  const animateCounter = (el) => {
    if (el.dataset.animated === 'true') return;
    el.dataset.animated = 'true';

    const target = parseInt(el.dataset.target, 10);
    const suffix = el.dataset.suffix || '';

    const duration = 1600;
    const startTime = performance.now();

    const formatNumber = (num) => {
      return num >= 1000 ? num.toLocaleString('en-US') : num.toString();
    };

    const step = (now) => {
      const elapsed = now - startTime;
      const progress = Math.min(elapsed / duration, 1);
      // ease-out for a nice deceleration near the end
      const eased = 1 - Math.pow(1 - progress, 3);
      const current = Math.floor(eased * target);

      el.textContent = formatNumber(current) + suffix;

      if (progress < 1) {
        requestAnimationFrame(step);
      } else {
        el.textContent = formatNumber(target) + suffix;
      }
    };

    requestAnimationFrame(step);
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        animateCounter(entry.target);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.4 });

  counters.forEach((counter) => observer.observe(counter));
}

document.addEventListener('DOMContentLoaded', () => {
  initCarousel('.courses-carousel');
  initCarousel('.why-carousel');
  initCounters();
});
