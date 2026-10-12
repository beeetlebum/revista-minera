jQuery(function ($) {
  /* -----------------------------------------
  Preloader
  ----------------------------------------- */
  $("#preloader").delay(1000).fadeOut();
  $("#loader").delay(1000).fadeOut("slow");
  
  /* -----------------------------------------
  rtl
  ----------------------------------------- */
  var isRTL = $("html").attr("dir") === "rtl";

  /* -----------------------------------------
  Main Slider
  ----------------------------------------- */
  $(".banner-style-1 .banner-slider").slick({
    dots: false,
    infinite: false,
    speed: 300,
    slidesToShow: 2,
    slidesToScroll: 1,
    infinite: true,
    rtl: isRTL,
    arrows: true,
    prevArrow: "<button class='fa fa-angle-left'</button>",
    nextArrow: "<button class='fa fa-angle-right'</button>",
    responsive: [
    {
      breakpoint: 780,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
      },
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
      },
    },
    ],
  });
  
  /* -----------------------------------------
  Toggle Button
  ----------------------------------------- */
  $(".menu-toggle").click(function () {
    $(this).toggleClass("show");
  });
  
  /* -----------------------------------------
  Keyboard Navigation
  ----------------------------------------- */
  $(window).on("load resize", function () {
    if ($(window).width() < 992 && $(window).width() >= 991) {
      $(".main-navigation").find("a").unbind("keydown");
      $(".main-navigation")
      .find("li")
      .last()
      .bind("keydown", function (e) {
        if (e.which === 9) {
          e.preventDefault();
          $("#masthead").find(".menu-toggle").focus();
        }
      });
    } else if ($(window).width() < 992) {
      $(".main-navigation").find("li").unbind("keydown");
      $(".main-navigation")
      .find("a")
      .last()
      .bind("keydown", function (e) {
        if (e.which === 9) {
          e.preventDefault();
          $("#masthead").find(".menu-toggle").focus();
        }
      });
    } else {
      $(".main-navigation").find("li").unbind("keydown");
      $(".main-navigation").find("a").unbind("keydown");
    }
  });
  
  var beyond_blog_primary_menu_toggle = $("#masthead .menu-toggle");
  beyond_blog_primary_menu_toggle.on("keydown", function (e) {
    var tabKey = e.keyCode === 9;
    var shiftKey = e.shiftKey;
    
    if (beyond_blog_primary_menu_toggle.hasClass("show")) {
      if (shiftKey && tabKey) {
        e.preventDefault();
        $(".main-navigation").toggleClass("toggled");
        beyond_blog_primary_menu_toggle.removeClass("show");
      }
    }
  });
  
  $(".header-search-wrap")
  .find(".search-submit")
  .bind("keydown", function (e) {
    var tabKey = e.keyCode === 9;
    if (tabKey) {
      e.preventDefault();
      $(".search-icon").focus();
    }
  });
  
  $(".search-icon").on("keydown", function (e) {
    var tabKey = e.keyCode === 9;
    var shiftKey = e.shiftKey;
    if ($(".header-search-wrap").hasClass("show")) {
      if (shiftKey && tabKey) {
        e.preventDefault();
        $(".header-search-wrap").removeClass("show");
        $(".search-icon").focus();
      }
    }
  });
  
  /* -----------------------------------------
  Header Search Bar
  ----------------------------------------- */
  var beyond_blog_searchWrap = $(".header-search-wrap");
  $(".search-icon").click(function (e) {
    e.preventDefault();
    beyond_blog_searchWrap.toggleClass("show");
    beyond_blog_searchWrap.find("input.search-field").focus();
  });
  $(document).click(function (e) {
    if (!beyond_blog_searchWrap.is(e.target) && !beyond_blog_searchWrap.has(e.target).length) {
      $(".header-search-wrap").removeClass("show");
    }
  });
  
  /* -----------------------------------------
  Scroll To Top Button
  ----------------------------------------- */
  var beyond_blog_scroll_btn = $(".scroll-to-top");
  
  $(window).scroll(function () {
    if ($(window).scrollTop() > 400) {
      beyond_blog_scroll_btn.addClass("show");
    } else {
      beyond_blog_scroll_btn.removeClass("show");
    }
  });
  
  beyond_blog_scroll_btn.on("click", function (e) {
    e.preventDefault();
    $("html, body").animate(
    {
      scrollTop: 0,
    },
    "300"
    );
  });
});