(() => {
  'use strict';



  const colorModeButton = document.querySelector('[data-nf-color-mode]');
  const colorModeLabel = colorModeButton ? colorModeButton.querySelector('[data-nf-color-mode-label]') : null;
  const colorModeKey = 'novaflow-color-mode';
  const localizedData = window.novaflowData || {};
  const initialColorModeLabel = colorModeLabel ? colorModeLabel.textContent : '';

  const syncColorModeControl = () => {
    if (!colorModeButton) return;
    const isDark = document.documentElement.classList.contains('nf-dark-mode');
    colorModeButton.setAttribute('aria-pressed', isDark ? 'true' : 'false');
    if (colorModeLabel) {
      const nextLabel = isDark ? localizedData.darkMode : localizedData.lightMode;
      colorModeLabel.textContent = nextLabel || initialColorModeLabel;
    }
  };

  if (colorModeButton) {
    syncColorModeControl();
    colorModeButton.addEventListener('click', () => {
      const willBeDark = !document.documentElement.classList.contains('nf-dark-mode');
      document.documentElement.classList.toggle('nf-dark-mode', willBeDark);
      try {
        window.localStorage.setItem(colorModeKey, willBeDark ? 'dark' : 'light');
      } catch (error) {
        // Storage can be unavailable in hardened/private browser contexts.
      }
      syncColorModeControl();
    });
  }

  const nav = document.getElementById('site-navigation');
  const menuToggle = document.querySelector('.nf-menu-toggle-review');
  if (nav && menuToggle) {
    const setMenuOpen = (open) => {
      nav.classList.toggle('nf-menu-expanded', open);
      menuToggle.setAttribute('aria-expanded', String(open));
    };
    menuToggle.addEventListener('click', () => {
      setMenuOpen(menuToggle.getAttribute('aria-expanded') !== 'true');
    });
    nav.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && window.innerWidth <= 980) {
        setMenuOpen(false);
        menuToggle.focus();
      }
    });
  }
  const headerSearch = document.querySelector('.nf-header-search .nf-search-form input[type="search"]');
  const isEditableElement = (element) => Boolean(
    element && (
      element.tagName === 'INPUT' ||
      element.tagName === 'TEXTAREA' ||
      element.tagName === 'SELECT' ||
      element.isContentEditable
    )
  );

  document.addEventListener('keydown', (event) => {
    if (!headerSearch) return;

    const wantsCommandSearch = (event.key && event.key.toLowerCase() === 'k' && (event.metaKey || event.ctrlKey));
    const wantsSlashSearch = event.key === '/' && !event.metaKey && !event.ctrlKey && !event.altKey;

    if (!wantsCommandSearch && !wantsSlashSearch) return;
    if (wantsSlashSearch && isEditableElement(event.target)) return;

    event.preventDefault();
    headerSearch.focus();
    headerSearch.select();
  });


  // Move the original WordPress menu nodes; retain their URLs, order and submenus.
  const primaryList = nav && nav.querySelector('.nf-nav-list');
  const extra = nav && nav.querySelector('.nf-menu-extra');
  if (primaryList && extra) {
    const extraButton = extra.querySelector('button');
    const extraList = extra.querySelector('ul');
    const items = Array.from(primaryList.children);
    const desktopMenu = window.matchMedia('(min-width: 981px)');
    let layoutFrame = 0;
    let observedWidth = -1;
    const setExtraOpen = (open) => {
      extraList.hidden = !open;
      extraButton.setAttribute('aria-expanded', String(open));
    };
    const layoutMenu = () => {
      layoutFrame = 0;
      const focused = document.activeElement;
      const hadMenuFocus = items.some((item) => item.contains(focused));
      const wasOpen = !extraList.hidden;
      items.forEach((item) => primaryList.appendChild(item));
      nav.classList.toggle('nf-priority-nav', desktopMenu.matches);
      extra.hidden = !desktopMenu.matches || !items.length;
      if (!desktopMenu.matches || !items.length) {
        setExtraOpen(false);
        if (hadMenuFocus && nav.classList.contains('nf-menu-expanded')) focused.focus();
        return;
      }
      // Measure intrinsic widths, then share spare space equally between items.
      // Both changes happen synchronously, so the measuring state is never painted.
      nav.classList.add('nf-menu-measuring');
      const listStyle = window.getComputedStyle(primaryList);
      const gap = parseFloat(listStyle.columnGap) || 0;
      const widths = items.map((item) => {
        const style = window.getComputedStyle(item);
        return item.getBoundingClientRect().width + (parseFloat(style.marginLeft) || 0) + (parseFloat(style.marginRight) || 0);
      });
      const padding = (parseFloat(listStyle.paddingLeft) || 0) + (parseFloat(listStyle.paddingRight) || 0);
      const reserved = extra.getBoundingClientRect().width + (parseFloat(window.getComputedStyle(nav).columnGap) || 0);
      const available = primaryList.getBoundingClientRect().width - padding;
      const total = widths.reduce((sum, width) => sum + width, 0) + gap * Math.max(0, items.length - 1);
      if (total <= available + reserved - 1) {
        extra.hidden = true;
        setExtraOpen(false);
      } else {
        let used = 0;
        let overflow = false;
        items.forEach((item, index) => {
          const next = widths[index] + (index ? gap : 0);
          overflow = overflow || used + next > available - 1;
          if (overflow) extraList.appendChild(item);
          else used += next;
        });
        setExtraOpen(wasOpen);
      }
      nav.classList.remove('nf-menu-measuring');
      if (hadMenuFocus) {
        if (extraList.contains(focused) && extraList.hidden) extraButton.focus();
        else focused.focus();
      } else if (focused === extraButton && extra.hidden) {
        const firstLink = primaryList.querySelector('a');
        if (firstLink) firstLink.focus();
      }
    };
    const scheduleMenu = () => {
      if (!layoutFrame) layoutFrame = window.requestAnimationFrame(layoutMenu);
    };
    extraButton.addEventListener('click', () => setExtraOpen(extraList.hidden));
    extraButton.addEventListener('keydown', (event) => {
      if (event.key === 'ArrowDown') {
        event.preventDefault();
        setExtraOpen(true);
        const firstLink = extraList.querySelector('a');
        if (firstLink) firstLink.focus();
      }
    });
    extra.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        event.preventDefault();
        setExtraOpen(false);
        extraButton.focus();
      }
    });
    extra.addEventListener('focusout', (event) => {
      if (!extra.contains(event.relatedTarget)) setExtraOpen(false);
    });
    document.addEventListener('pointerdown', (event) => {
      if (!extra.contains(event.target)) setExtraOpen(false);
    });
    window.addEventListener('resize', scheduleMenu);
    window.addEventListener('load', scheduleMenu);
    if (colorModeButton) colorModeButton.addEventListener('click', scheduleMenu);
    if ('ResizeObserver' in window) {
      new ResizeObserver((entries) => {
        const width = entries[0].contentRect.width;
        if (Math.abs(width - observedWidth) > 0.5) {
          observedWidth = width;
          scheduleMenu();
        }
      }).observe(nav);
    }
    layoutMenu();
    const revealMenu = () => {
      // Measure loaded fonts before the first visible desktop layout.
      layoutMenu();
      document.documentElement.classList.remove('nf-menu-pending');
    };
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(revealMenu, revealMenu);
    else revealMenu();
  } else {
    document.documentElement.classList.remove('nf-menu-pending');
  }

  const tickerTrack = document.querySelector('[data-ticker-track]');
  const tickerViewport = document.querySelector('[data-ticker-viewport]');
  const tickerPrev = document.querySelector('[data-ticker-prev]');
  const tickerNext = document.querySelector('[data-ticker-next]');
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (tickerTrack && tickerViewport) {
    const originalItems = Array.from(tickerTrack.querySelectorAll('.nf-ticker-item'));
    let tickerFrame = null;
    let tickerOffset = 0;
    let tickerOriginalWidth = 0;
    let lastTickerTime = 0;
    let tickerPaused = false;
    const tickerSpeed = 36;

    if (originalItems.length > 1) {
      originalItems.forEach((item) => {
        const clone = item.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        clone.setAttribute('tabindex', '-1');
        tickerTrack.appendChild(clone);
      });
    }

    const itemOuterWidth = (item) => {
      const rect = item.getBoundingClientRect();
      const style = window.getComputedStyle(item);
      return rect.width + (parseFloat(style.marginLeft) || 0) + (parseFloat(style.marginRight) || 0);
    };

    const measureTicker = () => {
      tickerOriginalWidth = originalItems.reduce((total, item) => total + itemOuterWidth(item), 0);

      if (tickerOriginalWidth > 0) {
        tickerOffset %= tickerOriginalWidth;
      } else {
        tickerOffset = 0;
      }

      tickerTrack.style.setProperty('--nf-ticker-offset', `${-tickerOffset}px`);
    };

    const renderTicker = (time) => {
      if (!lastTickerTime) {
        lastTickerTime = time;
      }

      const elapsed = Math.min(50, Math.max(0, time - lastTickerTime));
      lastTickerTime = time;

      if (!tickerPaused && !reducedMotion && tickerOriginalWidth > 0) {
        tickerOffset += tickerSpeed * (elapsed / 1000);

        if (tickerOffset >= tickerOriginalWidth) {
          tickerOffset %= tickerOriginalWidth;
        }

        tickerTrack.style.setProperty('--nf-ticker-offset', `${-tickerOffset}px`);
      }

      tickerFrame = window.requestAnimationFrame(renderTicker);
    };

    const jumpTicker = (direction) => {
      if (!originalItems.length || tickerOriginalWidth <= 0) {
        return;
      }

      tickerOffset += direction * itemOuterWidth(originalItems[0]);

      while (tickerOffset < 0) {
        tickerOffset += tickerOriginalWidth;
      }

      tickerOffset %= tickerOriginalWidth;
      tickerTrack.style.setProperty('--nf-ticker-offset', `${-tickerOffset}px`);
    };

    if (tickerPrev) {
      tickerPrev.addEventListener('click', () => jumpTicker(-1));
    }

    if (tickerNext) {
      tickerNext.addEventListener('click', () => jumpTicker(1));
    }

    const pauseTicker = () => {
      tickerPaused = true;
    };

    const resumeTicker = () => {
      tickerPaused = false;
      lastTickerTime = 0;
    };

    tickerViewport.addEventListener('mouseenter', pauseTicker);
    tickerViewport.addEventListener('mouseleave', resumeTicker);
    tickerViewport.addEventListener('focusin', pauseTicker);
    tickerViewport.addEventListener('focusout', resumeTicker);

    window.addEventListener('resize', measureTicker);
    window.addEventListener('load', measureTicker, { once: true });

    measureTicker();
    tickerFrame = window.requestAnimationFrame(renderTicker);

    window.addEventListener('pagehide', () => {
      if (tickerFrame) {
        window.cancelAnimationFrame(tickerFrame);
      }
    }, { once: true });
  }

  document.querySelectorAll('.nf-primary-nav .menu-item-has-children > a').forEach((link) => {
    const parent = link.parentElement;
    if (!parent) return;

    link.setAttribute('aria-haspopup', 'true');
    link.setAttribute('aria-expanded', 'false');
    link.addEventListener('focus', () => link.setAttribute('aria-expanded', 'true'));
    parent.addEventListener('mouseenter', () => link.setAttribute('aria-expanded', 'true'));
    parent.addEventListener('mouseleave', () => link.setAttribute('aria-expanded', 'false'));
    parent.addEventListener('focusout', (event) => {
      if (!parent.contains(event.relatedTarget)) link.setAttribute('aria-expanded', 'false');
    });
  });

  document.querySelectorAll('.nf-home-feed').forEach((grid) => {
    const stories = Array.from(grid.querySelectorAll('[data-nf-headline]'));
    const controls = grid.querySelector('[data-nf-headline-controls]');

    if (!controls || stories.length < 2) return;

    const previousButton = controls.querySelector('.nf-headline-prev');
    const nextButton = controls.querySelector('.nf-headline-next');
    const dots = Array.from(controls.querySelectorAll('[data-nf-headline-dot]'));
    const positionClasses = ['nf-story-lead', 'nf-story-side-top', 'nf-story-side-bottom'];
    let leadIndex = 0;

    const showHeadline = (requestedIndex) => {
      leadIndex = (requestedIndex + stories.length) % stories.length;
      stories.forEach((story) => story.classList.remove(...positionClasses));

      stories.forEach((story, position) => {
        const storyIndex = (leadIndex + position) % stories.length;
        stories[storyIndex].classList.add(positionClasses[position]);
      });

      dots.forEach((dot, index) => {
        if (index === leadIndex) {
          dot.setAttribute('aria-current', 'true');
        } else {
          dot.removeAttribute('aria-current');
        }
      });
    };

    if (previousButton) previousButton.addEventListener('click', () => showHeadline(leadIndex - 1));
    if (nextButton) nextButton.addEventListener('click', () => showHeadline(leadIndex + 1));
    dots.forEach((dot, index) => dot.addEventListener('click', () => showHeadline(index)));
  });
})();
