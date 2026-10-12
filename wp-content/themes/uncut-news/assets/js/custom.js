jQuery(function ($) {

  /* -----------------------------------------
    Preloader
    ----------------------------------------- */
  $("#preloader").delay(1000).fadeOut();
  $("#loader").delay(1000).fadeOut("slow");

  /* -----------------------------------------
    News Highlights
    ----------------------------------------- */
    if ( $('.news-highlights').length ) {
      $('.js-conveyor').jConveyorTicker({
        anim_duration:   200,    // Specify the time (in milliseconds) the animation takes to move 10 pixels
        force_loop:      true,   // Force the initialization even if the items are too small in total width
      });
    }

  /*--------------------------------------------------------------
    # Navigation menu responsive
    --------------------------------------------------------------*/
  $(document).ready(function () {
    var $menuToggle = $(".menu-toggle"),
    $navMenu = $(".main-navigation .nav-menu"),
    $mainNav = $(".main-navigation"),
    $masthead = $("#masthead");
    
      // Toggle navigation menu
    $menuToggle.click(function () {
      $navMenu.slideToggle("slow");
      $(this).toggleClass("open");
    });
    
      // Handle keyboard navigation
    function handleKeyboardNavigation() {
      if ($(window).width() < 1200) {
        $mainNav.find("li").last().off("keydown").on("keydown", function (e) {
            if (e.which === 9) { // Tab key
              e.preventDefault();
              $menuToggle.focus();
            }
          });
      } else {
        $mainNav.find("li").off("keydown");
      }
    }
    
      // Bind resize and load event for menu accessibility
    $(window).on("load resize", handleKeyboardNavigation);
    
      // Handle Shift + Tab key to close the menu when navigating backwards
    $menuToggle.on("keydown", function (e) {
      if ($(this).hasClass("open") && e.shiftKey && e.keyCode === 9) {
        e.preventDefault();
          $navMenu.slideUp("slow");  // Close the menu
          $menuToggle.removeClass("open");
          $mainNav.removeClass("toggled");
        }
      });
  });
  /*--------------------------------------------------------------
    # Navigation Search
    --------------------------------------------------------------*/
  var $searchWrap = $(".header-search-wrap");
  var $searchIcon = $(".header-search-icon");
  var $searchInput = $searchWrap.find("input.search-field");
  var $searchSubmit = $searchWrap.find(".search-submit");
  
    // Toggle search bar on icon click
  $searchIcon.on("click", function (e) {
    e.preventDefault();
    $searchWrap.toggleClass("show");
    $searchInput.focus();
  });
  
    // Close search bar when clicking outside
  $(document).on("click", function (e) {
    if (!$searchWrap.is(e.target) && !$searchWrap.has(e.target).length) {
      $searchWrap.removeClass("show");
    }
  });
  
    // Handle tab key navigation
  $searchSubmit.on("keydown", function (e) {
    if (e.key === "Tab") {
      e.preventDefault();
      $searchIcon.focus();
    }
  });
  
  $searchIcon.on("keydown", function (e) {
    if ($searchWrap.hasClass("show") && e.shiftKey && e.key === "Tab") {
      e.preventDefault();
      $searchWrap.removeClass("show");
      $searchIcon.focus();
    }
  });

  /* -----------------------------------------
    Scroll Top
    ----------------------------------------- */
  let scrollToTopBtn = $(".uncut-news-scroll-to-top");

  $(window).on("scroll", function () {
    scrollToTopBtn.toggleClass("visible", $(this).scrollTop() > 400);
  });
  
  scrollToTopBtn.on("click", function (e) {
    e.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, 300);
  });

});
