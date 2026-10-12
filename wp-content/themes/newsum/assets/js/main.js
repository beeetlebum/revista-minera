document.addEventListener('DOMContentLoaded', function() {
    
    // ── Live Wall Automatic Cycle ───────────────────────────────────────
    let liveWallInterval = null;
    function initLiveWall() {
        if (liveWallInterval) {
            clearInterval(liveWallInterval);
            liveWallInterval = null;
        }
        const liveWall = document.querySelector('.hero-live-wall');
        if (!liveWall) return;

        const rotateAttr = liveWall.getAttribute('data-rotate');
        const isRotateEnabled = (rotateAttr === 'true');
        const animationType = liveWall.getAttribute('data-animation') || 'none';
        
        // Stop completely if side cards rotation is disabled or animation is none
        if (!isRotateEnabled || animationType === 'none') {
            return;
        }

        const poolJson = liveWall.getAttribute('data-update-pool');
        
        if (poolJson) {
            let updateData = [];
            try {
                updateData = JSON.parse(poolJson);
            } catch (e) {
                updateData = [];
            }

            const tiles = Array.from(document.querySelectorAll('.live-tile'));
            let currentTileIndex = 0;

            if (tiles.length > 0 && updateData.length > 0) {
                const rotateTile = () => {
                    const tileToUpdate = tiles[currentTileIndex];
                    if (!tileToUpdate) {
                        currentTileIndex = (currentTileIndex + 1) % tiles.length;
                        return;
                    }
                    
                    const dataIndex = Math.floor(Math.random() * updateData.length);
                    const randomData = updateData[dataIndex];
                    if (!randomData) return;
                    
                    let animClass = 'updating-fade';
                    if (animationType === 'random') {
                        const randomEffects = ['updating-fade', 'updating-slide', 'updating-flash', 'updating-flip', 'updating-zoom', 'updating-slide-right'];
                        animClass = randomEffects[Math.floor(Math.random() * randomEffects.length)];
                    } else if (animationType === 'update-slide' || animationType === 'slide' || animationType === 'slide-up') {
                        animClass = 'updating-slide';
                    } else if (animationType === 'update-flash' || animationType === 'flash') {
                        animClass = 'updating-flash';
                    } else if (animationType === 'update-flip' || animationType === 'flip') {
                        animClass = 'updating-flip';
                    } else if (animationType === 'update-zoom' || animationType === 'zoom') {
                        animClass = 'updating-zoom';
                    } else {
                        animClass = 'updating-fade';
                    }
                    
                    tileToUpdate.classList.add(animClass);
                    
                    setTimeout(() => {
                        const img = tileToUpdate.querySelector('.tile-bg');
                        const cat = tileToUpdate.querySelector('.category');
                        const titleLink = tileToUpdate.querySelector('.tile-title a');
                        const excerpt = tileToUpdate.querySelector('.tile-excerpt');
                        const summaryBtn = tileToUpdate.querySelector('.card-summary-btn');
                        const bookmarkBtn = tileToUpdate.querySelector('.card-bookmark-btn');
                        
                        const oldData = {
                            cat: cat ? cat.textContent : '',
                            title: titleLink ? titleLink.textContent : '',
                            excerpt: excerpt ? excerpt.textContent : '',
                            img: img ? img.src : '',
                            link: titleLink ? titleLink.href : ''
                        };

                        if (img && randomData.img) img.src = randomData.img;
                        if (cat && randomData.cat) cat.textContent = randomData.cat;
                        if (titleLink && randomData.title) {
                            titleLink.textContent = randomData.title;
                            titleLink.href = randomData.link || '#';
                        }
                        if (excerpt && randomData.excerpt) excerpt.textContent = randomData.excerpt;
                        
                        if (summaryBtn) {
                            summaryBtn.setAttribute('data-post-title', randomData.title || '');
                            summaryBtn.setAttribute('data-post-excerpt', randomData.excerpt || '');
                            summaryBtn.setAttribute('data-post-link', randomData.link || '#');
                        }
                        if (bookmarkBtn) {
                            bookmarkBtn.setAttribute('data-post-title', randomData.title || '');
                            bookmarkBtn.setAttribute('data-post-link', randomData.link || '#');
                        }
                        
                        updateData[dataIndex] = oldData;
                        tileToUpdate.classList.remove(animClass);
                    }, 400);
                    
                    currentTileIndex = (currentTileIndex + 1) % tiles.length;
                };

                liveWallInterval = setInterval(rotateTile, 4000);
            }
        }
    }

    window.newsumInitLiveWall = initLiveWall;
    initLiveWall();

    // ── Main Hero Slider Logic ──────────────────────────────────────────
    let heroSliderInterval = null;
    function initHeroSlider() {
        if (heroSliderInterval) {
            clearInterval(heroSliderInterval);
            heroSliderInterval = null;
        }
        const slider = document.getElementById('heroSlider');
        if (!slider) return;

        const slides = Array.from(slider.querySelectorAll('.slide'));
        if (slides.length === 0) return;

        const prevBtn = document.getElementById('sliderPrev');
        const nextBtn = document.getElementById('sliderNext');
        const currentCounter = document.getElementById('currentSlide');
        const progressFill = document.getElementById('progressFill');
        const isAutoplay = slider.getAttribute('data-autoplay') === 'true';
        let currentSlide = 0;
        const slideDuration = 5000; // 5 seconds per slide

        function showSlide(index) {
            if (!slides || slides.length === 0) return;
            currentSlide = (index + slides.length) % slides.length;
            slides.forEach((s, idx) => {
                if (idx === currentSlide) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
            if (currentCounter) {
                currentCounter.textContent = currentSlide + 1;
            }
        }

        function nextSlide() {
            if (slides.length <= 1) return;
            showSlide(currentSlide + 1);
            resetProgress();
        }

        function prevSlide() {
            if (slides.length <= 1) return;
            showSlide(currentSlide - 1);
            resetProgress();
        }

        function resetProgress() {
            if (progressFill) {
                progressFill.style.transition = 'none';
                progressFill.style.width = '0%';
                void progressFill.offsetWidth; // Force synchronous reflow
                if (isAutoplay && slides.length > 1) {
                    setTimeout(() => {
                        progressFill.style.transition = `width ${slideDuration}ms linear`;
                        progressFill.style.width = '100%';
                    }, 20);
                }
            }
            if (isAutoplay && slides.length > 1) {
                if (heroSliderInterval) clearInterval(heroSliderInterval);
                heroSliderInterval = setInterval(nextSlide, slideDuration);
            } else if (heroSliderInterval) {
                clearInterval(heroSliderInterval);
                heroSliderInterval = null;
            }
        }

        // Initialize first slide immediately
        showSlide(0);

        if (nextBtn) nextBtn.onclick = nextSlide;
        if (prevBtn) prevBtn.onclick = prevSlide;

        if (isAutoplay && slides.length > 1) {
            resetProgress();
        } else {
            if (heroSliderInterval) {
                clearInterval(heroSliderInterval);
                heroSliderInterval = null;
            }
            if (progressFill) {
                progressFill.style.transition = 'none';
                progressFill.style.width = '0%';
            }
        }
    }

    window.newsumInitHeroSlider = initHeroSlider;
    initHeroSlider();

    // Hover-Reveal "Deep Dive" Logic
    function initHoverReveal() {
        const listItems = document.querySelectorAll('.hover-index-list li');
        const bgLayers = document.querySelectorAll('.hero-bg-layer');

        if (listItems.length > 0) {
            listItems.forEach(item => {
                item.onmouseenter = () => {
                    const targetId = item.getAttribute('data-bg');
                    listItems.forEach(li => li.classList.remove('active'));
                    bgLayers.forEach(bg => bg.classList.remove('active'));
                    item.classList.add('active');
                    const targetBg = document.getElementById(targetId);
                    if (targetBg) targetBg.classList.add('active');
                };
            });
        }
    }

    initHoverReveal();

    // Staggered Entry Animation for Masonry Items
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.masonry-item').forEach((item, index) => {
            // Add staggered transition delay based on position
            item.style.transitionDelay = `${(index % 3) * 0.1}s`;
            observer.observe(item);
        });
    }

    // Ticker Play/Pause Logic
    const tickerTrack = document.querySelector('.ticker-track');
    const tickerToggle = document.getElementById('tickerToggle');
    let isTickerPaused = false;
    
    if (tickerToggle && tickerTrack) {
        tickerToggle.addEventListener('click', () => {
            isTickerPaused = !isTickerPaused;
            if (isTickerPaused) {
                tickerTrack.style.animationPlayState = 'paused';
                tickerToggle.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>';
            } else {
                tickerTrack.style.animationPlayState = 'running';
                tickerToggle.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>';
            }
        });
    }

    // Accessibility: Keyboard Focus Trap Helper for Modals and Drawers
    const createFocusTrap = (container, onEscape) => {
        if (!container) return { activate: () => {}, deactivate: () => {} };
        const focusableSelector = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
        let previousActiveElement = null;

        const onKeyDown = (e) => {
            if (e.key === 'Escape' && onEscape) {
                e.preventDefault();
                onEscape();
                return;
            }
            if (e.key !== 'Tab') return;

            const focusables = Array.from(container.querySelectorAll(focusableSelector))
                .filter(el => !el.hasAttribute('disabled') && el.offsetParent !== null);
            if (focusables.length === 0) {
                e.preventDefault();
                return;
            }

            const firstFocusable = focusables[0];
            const lastFocusable = focusables[focusables.length - 1];

            if (e.shiftKey) {
                if (document.activeElement === firstFocusable) {
                    e.preventDefault();
                    lastFocusable.focus();
                }
            } else {
                if (document.activeElement === lastFocusable) {
                    e.preventDefault();
                    firstFocusable.focus();
                }
            }
        };

        return {
            activate: (elementToFocus) => {
                previousActiveElement = document.activeElement;
                container.addEventListener('keydown', onKeyDown);
                setTimeout(() => {
                    if (elementToFocus && typeof elementToFocus.focus === 'function') {
                        elementToFocus.focus();
                    } else {
                        const focusables = Array.from(container.querySelectorAll(focusableSelector))
                            .filter(el => !el.hasAttribute('disabled') && el.offsetParent !== null);
                        if (focusables.length > 0) focusables[0].focus();
                    }
                }, 50);
            },
            deactivate: () => {
                container.removeEventListener('keydown', onKeyDown);
                if (previousActiveElement && typeof previousActiveElement.focus === 'function') {
                    previousActiveElement.focus();
                }
            }
        };
    };

    // Off-Canvas Navigation Drawer Controller
    const hamburger = document.getElementById('hamburgerMenu') || document.querySelector('.hamburger-menu');
    const drawer = document.getElementById('offcanvasDrawer');
    const backdrop = document.getElementById('drawerBackdrop');
    const drawerClose = document.getElementById('drawerClose');
    let drawerTrap = null;

    const openDrawer = () => {
        if (!drawer) return;
        drawer.classList.add('active');
        drawer.setAttribute('aria-hidden', 'false');
        if (backdrop) backdrop.classList.add('active');
        if (hamburger) {
            hamburger.classList.add('active');
            hamburger.setAttribute('aria-expanded', 'true');
        }
        document.body.classList.add('drawer-open');
        if (!drawerTrap) {
            drawerTrap = createFocusTrap(drawer, closeDrawer);
        }
        drawerTrap.activate(drawerClose);
    };

    const closeDrawer = () => {
        if (!drawer) return;
        drawer.classList.remove('active');
        drawer.setAttribute('aria-hidden', 'true');
        if (backdrop) backdrop.classList.remove('active');
        if (hamburger) {
            hamburger.classList.remove('active');
            hamburger.setAttribute('aria-expanded', 'false');
        }
        document.body.classList.remove('drawer-open');
        if (drawerTrap) {
            drawerTrap.deactivate();
        }
        // Reset open accordion dropdowns inside drawer
        drawer.querySelectorAll('li.menu-item-has-children.active, li.page_item_has_children.active').forEach((li) => {
            li.classList.remove('active');
            const t = li.querySelector('.dropdown-toggle');
            if (t) t.setAttribute('aria-expanded', 'false');
        });
    };

    if (hamburger && drawer) {
        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();
            if (drawer.classList.contains('active')) {
                closeDrawer();
            } else {
                openDrawer();
            }
        });

        if (drawerClose) {
            drawerClose.addEventListener('click', (e) => {
                e.preventDefault();
                closeDrawer();
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', () => {
                closeDrawer();
            });
        }

        // Close on click outside drawer
        document.addEventListener('click', (e) => {
            if (drawer.classList.contains('active') && !drawer.contains(e.target) && !hamburger.contains(e.target)) {
                closeDrawer();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && drawer.classList.contains('active')) {
                closeDrawer();
                hamburger.focus();
            }
        });
    }

    // Theme Toggle Logic
    const themeToggle = document.getElementById('themeToggle') || document.querySelector('.theme-toggle');
    if (themeToggle) {
        const isCustomizer = window.wp && window.wp.customize;

        const applyTheme = (theme) => {
            const isLight = (theme === 'light');
            document.documentElement.classList.toggle('light-theme', isLight);
            document.body.classList.toggle('light-theme', isLight);
            themeToggle.setAttribute('aria-pressed', isLight ? 'true' : 'false');
            const nextAction = isLight ? 'Switch to dark theme' : 'Switch to light theme';
            themeToggle.setAttribute('aria-label', nextAction);
            themeToggle.setAttribute('title', nextAction);
        };

        // Sync initial state from DOM
        const initialIsLight = document.documentElement.classList.contains('light-theme') || document.body.classList.contains('light-theme');
        applyTheme(initialIsLight ? 'light' : 'dark');

        themeToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const currentlyLight = document.documentElement.classList.contains('light-theme') || document.body.classList.contains('light-theme');
            const newTheme = currentlyLight ? 'dark' : 'light';
            applyTheme(newTheme);
            if (!isCustomizer) {
                localStorage.setItem('newsum_theme', newTheme);
            }
        });
    }

    // Command Palette & Live Search Modal Logic
    const searchModal = document.getElementById('searchModal');
    const searchBtn = document.getElementById('searchBtn') || document.querySelector('.search-btn');
    const searchModalClose = document.getElementById('searchModalClose');
    const searchModalBackdrop = document.getElementById('searchModalBackdrop');
    const searchInput = document.getElementById('searchModalInput');
    const searchClearBtn = document.getElementById('searchClearBtn');
    const searchLiveResults = document.getElementById('searchLiveResults');
    const searchResultsList = document.getElementById('searchResultsList');
    const searchViewAllLink = document.getElementById('searchViewAllLink');
    const searchModalState = document.getElementById('searchModalState');
    const searchStateLoading = document.getElementById('searchStateLoading');
    const searchStateEmpty = document.getElementById('searchStateEmpty');
    const searchQuickTopics = document.getElementById('searchQuickTopics');

    let searchDebounceTimer;
    let searchTrap = null;

    const openSearchModal = () => {
        if (!searchModal) return;
        searchModal.classList.add('active');
        searchModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('search-modal-open');
        if (!searchTrap) {
            searchTrap = createFocusTrap(searchModal, closeSearchModal);
        }
        searchTrap.activate(searchInput);
    };

    const closeSearchModal = () => {
        if (!searchModal) return;
        searchModal.classList.remove('active');
        searchModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('search-modal-open');
        if (searchTrap) {
            searchTrap.deactivate();
        }
        if (searchBtn) searchBtn.focus();
    };

    if (searchBtn) {
        searchBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openSearchModal();
        });
    }

    if (searchModalClose) {
        searchModalClose.addEventListener('click', (e) => {
            e.preventDefault();
            closeSearchModal();
        });
    }

    if (searchModalBackdrop) {
        searchModalBackdrop.addEventListener('click', closeSearchModal);
    }

    // Global keyboard shortcut: Ctrl+K or Cmd+K
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')) {
            e.preventDefault();
            if (searchModal && searchModal.classList.contains('active')) {
                closeSearchModal();
            } else {
                openSearchModal();
            }
        } else if (e.key === 'Escape' && searchModal && searchModal.classList.contains('active')) {
            e.preventDefault();
            closeSearchModal();
        }
    });

    // Topic Pill Click Handler
    if (searchQuickTopics) {
        searchQuickTopics.addEventListener('click', (e) => {
            const pill = e.target.closest('.search-topic-pill');
            if (pill && searchInput) {
                const term = pill.getAttribute('data-search');
                searchInput.value = term;
                searchInput.dispatchEvent(new Event('input'));
                searchInput.focus();
            }
        });
    }

    // Live AJAX Search using WordPress REST API
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            if (searchClearBtn) {
                searchClearBtn.style.display = query ? 'flex' : 'none';
            }

            clearTimeout(searchDebounceTimer);

            if (!query || query.length < 2) {
                if (searchLiveResults) searchLiveResults.style.display = 'none';
                if (searchModalState) searchModalState.style.display = 'none';
                if (searchQuickTopics) searchQuickTopics.style.display = 'block';
                return;
            }

            if (searchQuickTopics) searchQuickTopics.style.display = 'none';
            if (searchModalState) {
                searchModalState.style.display = 'block';
                if (searchStateLoading) searchStateLoading.style.display = 'flex';
                if (searchStateEmpty) searchStateEmpty.style.display = 'none';
            }
            if (searchLiveResults) searchLiveResults.style.display = 'none';

            searchDebounceTimer = setTimeout(() => {
                const endpoint = `/wp-json/wp/v2/posts?search=${encodeURIComponent(query)}&per_page=5&_embed`;
                
                fetch(endpoint)
                    .then(response => {
                        if (!response.ok) throw new Error('Search failed');
                        return response.json();
                    })
                    .then(posts => {
                        if (searchStateLoading) searchStateLoading.style.display = 'none';

                        if (!Array.isArray(posts) || posts.length === 0) {
                            if (searchModalState) searchModalState.style.display = 'block';
                            if (searchStateEmpty) searchStateEmpty.style.display = 'block';
                            if (searchLiveResults) searchLiveResults.style.display = 'none';
                            return;
                        }

                        if (searchModalState) searchModalState.style.display = 'none';
                        if (searchLiveResults) searchLiveResults.style.display = 'block';

                        let html = '';
                        posts.forEach(post => {
                            let thumb = '';
                            if (post._embedded && post._embedded['wp:featuredmedia'] && post._embedded['wp:featuredmedia'][0]) {
                                const media = post._embedded['wp:featuredmedia'][0];
                                const mediaUrl = media.media_details && media.media_details.sizes && media.media_details.sizes.thumbnail ? media.media_details.sizes.thumbnail.source_url : media.source_url;
                                if (mediaUrl) {
                                    thumb = `<img src="${mediaUrl}" class="search-result-thumb" alt="" />`;
                                }
                            }

                            let dateFormatted = '';
                            if (post.date) {
                                const d = new Date(post.date);
                                dateFormatted = d.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
                            }

                            let titleRendered = post.title && post.title.rendered ? post.title.rendered : 'Untitled';

                            html += `
                                <a href="${post.link}" class="search-result-item">
                                    ${thumb ? `<div class="search-result-img">${thumb}</div>` : ''}
                                    <div class="search-result-content">
                                        <h4 class="search-result-title">${titleRendered}</h4>
                                        <div class="search-result-meta">
                                            ${dateFormatted ? `<span class="search-result-date">${dateFormatted}</span>` : ''}
                                        </div>
                                    </div>
                                    <svg class="search-result-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                </a>
                            `;
                        });

                        if (searchResultsList) searchResultsList.innerHTML = html;

                        if (searchViewAllLink) {
                            searchViewAllLink.href = `/?s=${encodeURIComponent(query)}`;
                            searchViewAllLink.style.display = 'flex';
                        }
                    })
                    .catch(() => {
                        if (searchStateLoading) searchStateLoading.style.display = 'none';
                        if (searchModalState) searchModalState.style.display = 'block';
                        if (searchStateEmpty) searchStateEmpty.style.display = 'block';
                    });
            }, 250);
        });
    }

    if (searchClearBtn && searchInput) {
        searchClearBtn.addEventListener('click', (e) => {
            e.preventDefault();
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        });
    }

    // ── 1. Story Highlights Reel Controller ─────────────────
    // ── 1. Story Highlights Reel Controller ─────────────────
    let currentStoryIndex = 0;
    let storyItems = [];
    let storyAutoAdvanceTimer;

    const storyQuickModal = document.getElementById('storyQuickModal');
    const storyModalBackdrop = document.getElementById('storyModalBackdrop');
    const storyModalClose = document.getElementById('storyModalClose');
    const storyModalImg = document.getElementById('storyModalImg');
    const storyModalTitle = document.getElementById('storyModalTitle');
    const storyModalExcerpt = document.getElementById('storyModalExcerpt');
    const storyModalCat = document.getElementById('storyModalCat');
    const storyModalDate = document.getElementById('storyModalDate');
    const storyModalReadtime = document.getElementById('storyModalReadtime');
    const storyModalFullBtn = document.getElementById('storyModalFullBtn');
    const storyModalPrev = document.getElementById('storyModalPrev');
    const storyModalNext = document.getElementById('storyModalNext');
    const storyProgressBar = document.getElementById('storyProgressBar');

    const renderStoryModal = (index) => {
        if (!storyItems[index]) return;
        currentStoryIndex = index;
        const item = storyItems[index];

        if (storyModalTitle) storyModalTitle.textContent = item.getAttribute('data-story-title') || '';
        if (storyModalExcerpt) storyModalExcerpt.textContent = item.getAttribute('data-story-excerpt') || '';
        if (storyModalCat) storyModalCat.textContent = item.getAttribute('data-story-cat') || 'FEATURED';
        if (storyModalDate) storyModalDate.textContent = item.getAttribute('data-story-date') || '';
        if (storyModalReadtime) storyModalReadtime.textContent = item.getAttribute('data-story-readtime') || '2 MIN READ';
        if (storyModalFullBtn) storyModalFullBtn.href = item.getAttribute('data-story-link') || '#';
        if (storyModalImg) storyModalImg.src = item.getAttribute('data-story-img') || '';

        if (storyProgressBar) {
            storyProgressBar.style.transition = 'none';
            storyProgressBar.style.width = '0%';
            setTimeout(() => {
                storyProgressBar.style.transition = 'width 6000ms linear';
                storyProgressBar.style.width = '100%';
            }, 40);
        }

        clearTimeout(storyAutoAdvanceTimer);
        storyAutoAdvanceTimer = setTimeout(() => {
            if (currentStoryIndex < storyItems.length - 1) {
                renderStoryModal(currentStoryIndex + 1);
            } else {
                closeStoryModal();
            }
        }, 6000);
    };

    let storyTrap = null;
    const openStoryModal = (index) => {
        if (!storyQuickModal) return;
        renderStoryModal(index);
        storyQuickModal.classList.add('active');
        storyQuickModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('story-modal-open');
        if (!storyTrap) {
            storyTrap = createFocusTrap(storyQuickModal, closeStoryModal);
        }
        storyTrap.activate(storyModalClose);
    };

    const closeStoryModal = () => {
        if (!storyQuickModal) return;
        clearTimeout(storyAutoAdvanceTimer);
        storyQuickModal.classList.remove('active');
        storyQuickModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('story-modal-open');
        if (storyTrap) {
            storyTrap.deactivate();
        }
    };

    if (storyModalClose) storyModalClose.addEventListener('click', closeStoryModal);
    if (storyModalBackdrop) storyModalBackdrop.addEventListener('click', closeStoryModal);

    if (storyModalPrev) {
        storyModalPrev.addEventListener('click', () => {
            if (currentStoryIndex > 0) renderStoryModal(currentStoryIndex - 1);
        });
    }

    if (storyModalNext) {
        storyModalNext.addEventListener('click', () => {
            if (currentStoryIndex < storyItems.length - 1) renderStoryModal(currentStoryIndex + 1);
            else closeStoryModal();
        });
    }

    // Keyboard Arrow navigation for stories modal
    document.addEventListener('keydown', (e) => {
        if (!storyQuickModal || !storyQuickModal.classList.contains('active')) return;
        if (e.key === 'ArrowLeft') {
            if (currentStoryIndex > 0) renderStoryModal(currentStoryIndex - 1);
        } else if (e.key === 'ArrowRight') {
            if (currentStoryIndex < storyItems.length - 1) renderStoryModal(currentStoryIndex + 1);
            else closeStoryModal();
        } else if (e.key === 'Escape') {
            closeStoryModal();
        }
    });

    const initStoriesReel = () => {
        const storiesTrack = document.getElementById('storiesTrack');
        const storiesPrevBtn = document.getElementById('storiesPrevBtn');
        const storiesNextBtn = document.getElementById('storiesNextBtn');

        if (!storiesTrack) return;
        storyItems = Array.from(storiesTrack.querySelectorAll('.story-item'));

        if (storiesPrevBtn) {
            storiesPrevBtn.onclick = () => storiesTrack.scrollBy({ left: -240, behavior: 'smooth' });
        }
        if (storiesNextBtn) {
            storiesNextBtn.onclick = () => storiesTrack.scrollBy({ left: 240, behavior: 'smooth' });
        }

        storiesTrack.onclick = (e) => {
            const item = e.target.closest('.story-item');
            if (item) {
                const idx = parseInt(item.getAttribute('data-story-index'), 10) || 0;
                openStoryModal(idx);
            }
        };
    };

    initStoriesReel();

    if (window.wp && wp.customize && wp.customize.selectiveRefresh) {
        wp.customize.selectiveRefresh.bind('partial-content-rendered', function(placement) {
            if (!placement || !placement.partial) return;
            const pid = placement.partial.id;
            if (pid === 'newsum_stories_partial') {
                initStoriesReel();
            } else if (pid === 'newsum_hero_partial') {
                initHeroSlider();
                initLiveWall();
                initHoverReveal();
            }
        });
    }

    // ── 2. 30-Second Quick Takeaway Popover Drawer ──────────
    const summaryDrawer = document.getElementById('quickSummaryDrawer');
    const summaryBackdrop = document.getElementById('summaryDrawerBackdrop');
    const summaryClose = document.getElementById('summaryDrawerClose');
    const summaryTitle = document.getElementById('summaryDrawerTitle');
    const summaryList = document.getElementById('summaryTakeawaysList');
    const summaryFullLink = document.getElementById('summaryDrawerFullLink');

    const openSummaryDrawer = (title, excerpt, link) => {
        if (!summaryDrawer) return;
        if (summaryTitle) summaryTitle.textContent = title;
        if (summaryFullLink) summaryFullLink.href = link;

        // Generate 3 clean bullet takeaways from excerpt
        if (summaryList) {
            const cleanExcerpt = excerpt.replace(/\.\.\.$/, '');
            const sentences = cleanExcerpt.split(/[.!?]+/).filter(s => s.trim().length > 8);
            let bullets = [];
            if (sentences.length >= 2) {
                bullets = sentences.slice(0, 3);
            } else {
                bullets = [
                    cleanExcerpt,
                    'Critical strategic analysis and key implications for decision-makers.',
                    'Full editorial context, industry data benchmarks, and expert commentary available in complete dispatch.'
                ];
            }

            summaryList.innerHTML = bullets.map(b => `
                <div class="summary-bullet">
                    <span class="bullet-icon">⚡</span>
                    <p class="bullet-text">${b.trim()}.</p>
                </div>
            `).join('');
        }

        summaryDrawer.classList.add('active');
        summaryDrawer.setAttribute('aria-hidden', 'false');
        document.body.classList.add('summary-drawer-open');
    };

    const closeSummaryDrawer = () => {
        if (!summaryDrawer) return;
        summaryDrawer.classList.remove('active');
        summaryDrawer.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('summary-drawer-open');
    };

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.card-summary-btn');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            const title = btn.getAttribute('data-post-title') || 'Article Brief';
            const excerpt = btn.getAttribute('data-post-excerpt') || '';
            const link = btn.getAttribute('data-post-link') || '#';
            openSummaryDrawer(title, excerpt, link);
        }
    });

    if (summaryClose) summaryClose.addEventListener('click', closeSummaryDrawer);
    if (summaryBackdrop) summaryBackdrop.addEventListener('click', closeSummaryDrawer);

    // ── 3. Client-Side Bookmark & Reading List Engine (localStorage) ──
    const bookmarkToast = document.getElementById('bookmarkToast');
    const bookmarkToastMsg = document.getElementById('bookmarkToastMsg');
    const readingListBtn = document.getElementById('readingListBtn');
    const readingListCount = document.getElementById('readingListCount');
    const readingListModal = document.getElementById('readingListModal');
    const readingListBackdrop = document.getElementById('readingListBackdrop');
    const readingListClose = document.getElementById('readingListClose');
    const readingListBody = document.getElementById('readingListBody');
    const readingListDrawerCount = document.getElementById('readingListDrawerCount');
    const readingListClearBtn = document.getElementById('readingListClearBtn');
    let toastTimer;

    const getBookmarks = () => {
        try {
            return JSON.parse(localStorage.getItem('newsum_bookmarks')) || [];
        } catch (e) {
            return [];
        }
    };

    const showToast = (msg) => {
        if (!bookmarkToast) return;
        if (bookmarkToastMsg) bookmarkToastMsg.textContent = msg;
        bookmarkToast.classList.add('active');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            bookmarkToast.classList.remove('active');
        }, 2600);
    };

    const renderReadingList = () => {
        const bookmarks = getBookmarks();
        const count = bookmarks.length;

        // Update counts
        if (readingListCount) {
            readingListCount.textContent = count;
            readingListCount.classList.toggle('empty', count === 0);
        }
        if (readingListDrawerCount) {
            readingListDrawerCount.textContent = count === 1 ? '1 article' : `${count} articles`;
        }

        if (!readingListBody) return;

        if (count === 0) {
            readingListBody.innerHTML = `
                <div class="reading-list-empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <div class="reading-list-empty-title">Your Reading List is Empty</div>
                    <p class="reading-list-empty-text">Click the bookmark icon on any dispatch or article to save it for later reading.</p>
                </div>
            `;
            if (readingListClearBtn) readingListClearBtn.style.display = 'none';
            return;
        }

        if (readingListClearBtn) readingListClearBtn.style.display = 'block';

        let html = '';
        bookmarks.slice().reverse().forEach((item) => {
            const timeAgo = item.date ? formatTimeAgo(new Date(item.date)) : 'Saved';
            html += `
                <article class="reading-list-item" data-id="${escapeHtml(item.id)}">
                    <h4 class="reading-list-item-title">
                        <a href="${escapeHtml(item.link)}">${escapeHtml(item.title)}</a>
                    </h4>
                    <div class="reading-list-item-footer">
                        <a href="${escapeHtml(item.link)}" class="reading-list-item-link">Read Story →</a>
                        <button type="button" class="reading-list-item-remove" data-remove-id="${escapeHtml(item.id)}" aria-label="Remove from reading list">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            <span>Remove</span>
                        </button>
                    </div>
                </article>
            `;
        });
        readingListBody.innerHTML = html;
    };

    const formatTimeAgo = (date) => {
        const seconds = Math.floor((new Date() - date) / 1000);
        if (seconds < 60) return 'Just now';
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) return `${minutes}m ago`;
        const hours = Math.floor(minutes / 60);
        if (hours < 24) return `${hours}h ago`;
        const days = Math.floor(hours / 24);
        return `${days}d ago`;
    };

    const escapeHtml = (str) => {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    };

    const syncBookmarkButtons = () => {
        const bookmarks = getBookmarks();
        document.querySelectorAll('.card-bookmark-btn').forEach(btn => {
            const id = btn.getAttribute('data-post-id');
            const isSaved = bookmarks.some(b => b.id === id);
            btn.classList.toggle('is-saved', isSaved);
            btn.setAttribute('aria-pressed', isSaved ? 'true' : 'false');
        });
        renderReadingList();
    };

    const openReadingList = () => {
        if (!readingListModal) return;
        renderReadingList();
        readingListModal.classList.add('active');
        readingListModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('reading-list-open');
    };

    const closeReadingList = () => {
        if (!readingListModal) return;
        readingListModal.classList.remove('active');
        readingListModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('reading-list-open');
    };

    if (readingListBtn) {
        readingListBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openReadingList();
        });
    }

    if (readingListClose) {
        readingListClose.addEventListener('click', closeReadingList);
    }

    if (readingListBackdrop) {
        readingListBackdrop.addEventListener('click', closeReadingList);
    }

    if (readingListClearBtn) {
        readingListClearBtn.addEventListener('click', () => {
            if (confirm('Clear all saved articles from your Reading List?')) {
                localStorage.setItem('newsum_bookmarks', JSON.stringify([]));
                syncBookmarkButtons();
                showToast('Reading List cleared');
            }
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && readingListModal && readingListModal.classList.contains('active')) {
            closeReadingList();
        }
    });

    document.addEventListener('click', (e) => {
        // 1. Bookmark toggle button on cards
        const btn = e.target.closest('.card-bookmark-btn');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            const id = btn.getAttribute('data-post-id');
            const title = btn.getAttribute('data-post-title') || 'Article';
            const link = btn.getAttribute('data-post-link') || '#';

            let bookmarks = getBookmarks();
            const existingIndex = bookmarks.findIndex(b => b.id === id);

            if (existingIndex > -1) {
                bookmarks.splice(existingIndex, 1);
                showToast('Removed from Reading List');
            } else {
                bookmarks.push({ id, title, link, date: new Date().toISOString() });
                showToast('Saved to your Reading List');
            }

            localStorage.setItem('newsum_bookmarks', JSON.stringify(bookmarks));
            syncBookmarkButtons();
            return;
        }

        // 2. Remove item button inside reading list drawer
        const removeBtn = e.target.closest('.reading-list-item-remove');
        if (removeBtn) {
            e.preventDefault();
            const id = removeBtn.getAttribute('data-remove-id');
            let bookmarks = getBookmarks();
            bookmarks = bookmarks.filter(b => b.id !== id);
            localStorage.setItem('newsum_bookmarks', JSON.stringify(bookmarks));
            syncBookmarkButtons();
            showToast('Removed from Reading List');
        }
    });

    syncBookmarkButtons();

    // ── 4. Hover-Reveal Typography Hero Crossfader ───────────
    const hoverRevealSections = document.querySelectorAll('.hover-reveal-section');
    hoverRevealSections.forEach(section => {
        const items = section.querySelectorAll('.hover-index-list li');
        const layers = section.querySelectorAll('.hero-bg-layer');

        items.forEach(item => {
            item.addEventListener('mouseenter', () => {
                items.forEach(i => i.classList.remove('active'));
                item.classList.add('active');

                const targetBgId = item.getAttribute('data-bg');
                layers.forEach(layer => {
                    if (layer.id === targetBgId) {
                        layer.classList.add('active');
                    } else {
                        layer.classList.remove('active');
                    }
                });
            });
        });
    });

    // ── 5. Footer Back to Top Button ──────────────────────────
    const footerBackToTop = document.getElementById('footerBackToTop');
    if (footerBackToTop) {
        footerBackToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ── 6. Single Post Reading Progress Bar ───────────────────
    const readingProgressBar = document.getElementById('readingProgressBar');
    const readingProgressContainer = document.querySelector('.reading-progress-container');
    const articleContainer = document.getElementById('singleMainContent') || document.querySelector('.single-article') || document.getElementById('articleBodyContent');
    
    if (readingProgressBar) {
        let isTicking = false;
        
        const updateProgressBar = () => {
            const windowHeight = window.innerHeight;
            const scrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
            
            let progress = 0;
            
            if (articleContainer) {
                const rect = articleContainer.getBoundingClientRect();
                const containerTop = rect.top + scrollY;
                const containerHeight = articleContainer.offsetHeight;
                
                // Total scrollable distance for the article
                const scrollable = Math.max(1, containerHeight - (windowHeight * 0.4));
                const current = scrollY - containerTop + (windowHeight * 0.15);
                
                progress = Math.min(100, Math.max(0, (current / scrollable) * 100));
            } else {
                const totalDocHeight = document.documentElement.scrollHeight - windowHeight;
                progress = totalDocHeight > 0 ? Math.min(100, Math.max(0, (scrollY / totalDocHeight) * 100)) : 0;
            }
            
            readingProgressBar.style.width = progress.toFixed(2) + '%';
            
            if (readingProgressContainer) {
                readingProgressContainer.style.opacity = progress > 0.5 ? '1' : '0';
            }
            
            isTicking = false;
        };

        window.addEventListener('scroll', () => {
            if (!isTicking) {
                window.requestAnimationFrame(updateProgressBar);
                isTicking = true;
            }
        }, { passive: true });

        window.addEventListener('resize', () => {
            if (!isTicking) {
                window.requestAnimationFrame(updateProgressBar);
                isTicking = true;
            }
        }, { passive: true });

        updateProgressBar();
    }

    // ── 7. Copy Article Link Button ───────────────────────────
    document.querySelectorAll('.copy-link-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const url = btn.getAttribute('data-url') || window.location.href;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(() => {
                    showToast('Article link copied to clipboard');
                }).catch(() => {
                    showToast('Link copied');
                });
            } else {
                showToast('Link copied');
            }
        });
    });

    // ── 8. Navigation Menu Dropdown Accordion / Click Toggle ─────
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.dropdown-toggle');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            const parentLi = btn.closest('li');
            if (parentLi) {
                const isExpanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', String(!isExpanded));
                parentLi.classList.toggle('active', !isExpanded);
            }
            return;
        }

        // Support clicking on parent menu items with '#' href inside mobile drawer
        const drawerParentLink = e.target.closest('.drawer-nav li.menu-item-has-children > a, .offcanvas-drawer li.menu-item-has-children > a');
        if (drawerParentLink) {
            const href = drawerParentLink.getAttribute('href');
            if (!href || href === '#' || href === '#!' || href === 'javascript:void(0)') {
                e.preventDefault();
                e.stopPropagation();
                const siblingBtn = drawerParentLink.parentElement ? drawerParentLink.parentElement.querySelector('.dropdown-toggle') : null;
                if (siblingBtn) {
                    siblingBtn.click();
                }
                return;
            }
        }

        // Dismiss any open desktop dropdown menus when clicking outside
        if (!e.target.closest('.offcanvas-drawer') && !e.target.closest('.drawer-nav')) {
            document.querySelectorAll('.main-nav .nav-menu li.active').forEach((li) => {
                li.classList.remove('active');
                const t = li.querySelector('.dropdown-toggle');
                if (t) t.setAttribute('aria-expanded', 'false');
            });
        }
    });

    // Keyboard accessibility: Two-way Tab and Shift+Tab navigation for desktop sub-menus
    document.addEventListener('keydown', (e) => {
        // Handle Shift + Tab going backwards into previous sub-menu
        if (e.key === 'Tab' && e.shiftKey) {
            const activeLink = document.activeElement ? document.activeElement.closest('.main-nav a') : null;
            if (activeLink) {
                const currentLi = activeLink.closest('li');
                // Only intercept when moving backwards from the first link of a menu item
                if (currentLi && activeLink === currentLi.querySelector('a')) {
                    const prevLi = currentLi.previousElementSibling;
                    if (prevLi && prevLi.querySelector('.sub-menu, .children')) {
                        const linksInPrev = prevLi.querySelectorAll('a');
                        const targetLink = linksInPrev[linksInPrev.length - 1];
                        if (targetLink) {
                            e.preventDefault();
                            // Make sub-menu visible to browser focus engine before calling focus
                            prevLi.classList.add('active');
                            const onFocusOut = (ev) => {
                                if (!prevLi.contains(ev.relatedTarget)) {
                                    prevLi.classList.remove('active');
                                    prevLi.removeEventListener('focusout', onFocusOut);
                                }
                            };
                            prevLi.addEventListener('focusout', onFocusOut);
                            targetLink.focus();
                            return;
                        }
                    }
                }
            } else {
                // Check if user is navigating backwards from the first focusable element after .main-nav
                const mainNav = document.getElementById('siteNavigation') || document.querySelector('.main-nav');
                const headerActions = document.querySelector('.header-actions');
                if (mainNav && headerActions && headerActions.contains(document.activeElement)) {
                    const focusables = Array.from(headerActions.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'))
                        .filter(el => !el.hasAttribute('disabled') && el.offsetParent !== null);
                    if (focusables.length > 0 && document.activeElement === focusables[0]) {
                        const lastTopLi = mainNav.querySelector('.nav-menu > li:last-child');
                        if (lastTopLi && lastTopLi.querySelector('.sub-menu, .children')) {
                            const linksInLast = lastTopLi.querySelectorAll('a');
                            const target = linksInLast[linksInLast.length - 1];
                            if (target) {
                                e.preventDefault();
                                lastTopLi.classList.add('active');
                                const onFocusOut = (ev) => {
                                    if (!lastTopLi.contains(ev.relatedTarget)) {
                                        lastTopLi.classList.remove('active');
                                        lastTopLi.removeEventListener('focusout', onFocusOut);
                                    }
                                };
                                lastTopLi.addEventListener('focusout', onFocusOut);
                                target.focus();
                                return;
                            }
                        }
                    }
                }
            }
        }

        // Handle Escape key inside sub-menu to return to parent item
        if (e.key === 'Escape') {
            const activeSubLink = document.activeElement ? document.activeElement.closest('.main-nav .sub-menu a, .main-nav .children a') : null;
            if (activeSubLink) {
                const parentLi = activeSubLink.closest('.main-nav li.menu-item-has-children, .main-nav li.page_item_has_children');
                if (parentLi) {
                    const parentLink = parentLi.querySelector('a');
                    if (parentLink) {
                        e.preventDefault();
                        parentLink.focus();
                        parentLi.classList.remove('active');
                    }
                }
            }
        }
    });

    // ── 9. Page Preloader Dismiss ─────────────────────────────
    const sitePreloader = document.getElementById('newsumPreloader');
    if (sitePreloader) {
        const dismissPreloader = () => {
            sitePreloader.classList.add('loaded');
            setTimeout(() => {
                if (sitePreloader.parentNode) {
                    sitePreloader.parentNode.removeChild(sitePreloader);
                }
            }, 450);
        };
        if (document.readyState === 'complete') {
            dismissPreloader();
        } else {
            window.addEventListener('load', dismissPreloader);
            setTimeout(dismissPreloader, 2000); // Safety fallback
        }
    }

    // ── 10. In-Article Table of Contents (TOC) ───────────────────────────
    function initTableOfContents() {
        const articleBody = document.getElementById('articleBodyContent');
        if (!articleBody || articleBody.dataset.showToc === '0' || articleBody.querySelector('.article-toc-box')) {
            return;
        }
        const headings = Array.from(articleBody.querySelectorAll('h2, h3')).filter(h => {
            return !h.closest('.article-takeaways-card, .article-inline-newsletter, .pull-quote, blockquote, .no-toc');
        });

        if (headings.length >= 2) {
            const tocNav = document.createElement('nav');
            tocNav.className = 'article-toc-box';
            tocNav.setAttribute('aria-label', 'Table of Contents');

            const tocHeader = document.createElement('div');
            tocHeader.className = 'toc-header';

            const tocTitle = document.createElement('span');
            tocTitle.className = 'toc-title';
            tocTitle.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg><span>Contents</span>';

            const tocToggle = document.createElement('button');
            tocToggle.type = 'button';
            tocToggle.className = 'toc-toggle-btn';
            tocToggle.setAttribute('aria-expanded', 'true');
            tocToggle.textContent = 'Hide';

            tocHeader.appendChild(tocTitle);
            tocHeader.appendChild(tocToggle);
            tocNav.appendChild(tocHeader);

            const tocList = document.createElement('ul');
            tocList.className = 'toc-list';

            const usedIds = new Set();
            headings.forEach((heading, idx) => {
                let id = heading.id;
                if (!id) {
                    const text = heading.textContent.trim().toLowerCase()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/\s+/g, '-');
                    id = text ? text : 'heading-' + (idx + 1);
                    if (usedIds.has(id) || document.getElementById(id)) {
                        id = `${id}-${idx + 1}`;
                    }
                    heading.id = id;
                }
                usedIds.add(id);

                const li = document.createElement('li');
                li.className = `toc-item toc-${heading.tagName.toLowerCase()}`;

                const a = document.createElement('a');
                a.href = '#' + id;
                a.textContent = heading.textContent.trim();

                a.addEventListener('click', (e) => {
                    const targetEl = document.getElementById(id);
                    if (targetEl) {
                        e.preventDefault();
                        targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        history.pushState(null, '', '#' + id);
                    }
                });

                li.appendChild(a);
                tocList.appendChild(li);
            });

            tocNav.appendChild(tocList);

            tocToggle.addEventListener('click', () => {
                const isExpanded = tocToggle.getAttribute('aria-expanded') === 'true';
                tocToggle.setAttribute('aria-expanded', String(!isExpanded));
                tocToggle.textContent = isExpanded ? 'Show' : 'Hide';
                tocNav.classList.toggle('is-collapsed', isExpanded);
            });

            // Insert before the first heading in articleBody
            headings[0].parentNode.insertBefore(tocNav, headings[0]);
        }
    }
    initTableOfContents();
    window.newsumInitTOC = initTableOfContents;

    // ── 11. Archive AJAX Pagination (Load More & Infinite Scroll) ────────
    const paginationNav = document.querySelector('.archive-pagination.has-ajax-pagination');
    if (paginationNav) {
        const grid = document.querySelector('.archive-feed-container') || document.querySelector('.archive-grid') || document.querySelector('.feed-grid');
        const loadMoreBtn = paginationNav.querySelector('#archiveLoadMoreBtn');
        const paginationType = paginationNav.getAttribute('data-pagination-type') || 'load_more';
        const maxPages = parseInt(paginationNav.getAttribute('data-max-pages'), 10) || 1;
        let currentPage = parseInt(paginationNav.getAttribute('data-current-page'), 10) || 1;
        let queryVars = {};
        try {
            queryVars = JSON.parse(paginationNav.getAttribute('data-query') || '{}');
        } catch (e) {
            queryVars = {};
        }

        let isLoading = false;

        const loadNextDispatches = () => {
            if (isLoading || currentPage >= maxPages || !grid) return;
            isLoading = true;

            if (loadMoreBtn) {
                loadMoreBtn.classList.add('is-loading');
                const btnText = loadMoreBtn.querySelector('.btn-text');
                if (btnText && window.newsumAjax && window.newsumAjax.loading) {
                    btnText.textContent = window.newsumAjax.loading;
                }
            }

            const nextPage = currentPage + 1;
            const formData = new FormData();
            formData.append('action', 'newsum_load_more');
            formData.append('nonce', (window.newsumAjax && window.newsumAjax.nonce) ? window.newsumAjax.nonce : '');
            formData.append('page', String(nextPage));
            formData.append('query', JSON.stringify(queryVars));

            const ajaxUrl = (window.newsumAjax && window.newsumAjax.ajaxUrl) ? window.newsumAjax.ajaxUrl : '/wp-admin/admin-ajax.php';

            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(response => {
                if (response.success && response.data && response.data.html) {
                    const temp = document.createElement('div');
                    temp.innerHTML = response.data.html;
                    const newCards = Array.from(temp.querySelectorAll('.archive-post-card'));

                    newCards.forEach((card, index) => {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(15px)';
                        card.style.transition = `opacity 0.4s ease ${index * 0.05}s, transform 0.4s cubic-bezier(0.16, 1, 0.3, 1) ${index * 0.05}s`;
                        grid.appendChild(card);
                        requestAnimationFrame(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        });
                    });

                    currentPage = nextPage;
                    paginationNav.setAttribute('data-current-page', String(currentPage));

                    if (typeof syncBookmarkButtons === 'function') {
                        syncBookmarkButtons();
                    }

                    try {
                        const currentUrl = new URL(window.location.href);
                        if (currentUrl.pathname.match(/\/page\/\d+\/?/)) {
                            currentUrl.pathname = currentUrl.pathname.replace(/\/page\/\d+\/?/, `/page/${currentPage}/`);
                        } else {
                            currentUrl.pathname = currentUrl.pathname.replace(/\/?$/, `/page/${currentPage}/`);
                        }
                        history.replaceState(null, '', currentUrl.toString());
                    } catch (e) {
                        // Safe fallback
                    }

                    if (currentPage >= maxPages) {
                        if (loadMoreBtn) {
                            loadMoreBtn.disabled = true;
                            loadMoreBtn.classList.remove('is-loading');
                            loadMoreBtn.classList.add('is-complete');
                            const btnText = loadMoreBtn.querySelector('.btn-text');
                            if (btnText && window.newsumAjax && window.newsumAjax.noMore) {
                                btnText.textContent = window.newsumAjax.noMore;
                            }
                        }
                    } else if (loadMoreBtn) {
                        loadMoreBtn.classList.remove('is-loading');
                        const btnText = loadMoreBtn.querySelector('.btn-text');
                        if (btnText) {
                            btnText.textContent = loadMoreBtn.getAttribute('data-original-text') || 'Load More Stories';
                        }
                    }
                } else {
                    if (loadMoreBtn) {
                        loadMoreBtn.classList.remove('is-loading');
                    }
                }
                isLoading = false;
            })
            .catch(() => {
                if (loadMoreBtn) {
                    loadMoreBtn.classList.remove('is-loading');
                }
                isLoading = false;
            });
        };

        if (loadMoreBtn) {
            const btnText = loadMoreBtn.querySelector('.btn-text');
            if (btnText) {
                loadMoreBtn.setAttribute('data-original-text', btnText.textContent.trim());
            }
            loadMoreBtn.addEventListener('click', loadNextDispatches);
        }

        if (paginationType === 'infinite' && 'IntersectionObserver' in window && loadMoreBtn) {
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && !isLoading && currentPage < maxPages) {
                    loadNextDispatches();
                }
            }, { rootMargin: '350px' });
            observer.observe(loadMoreBtn);
        }
    }
});

