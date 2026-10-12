(function ($) {
    "use strict";

    /* -----------------------------------------------------
          Variables
      ----------------------------------------------------- */
     var leftArrow = '<i class="fa fa-angle-left"></i>';
     var rightArrow = '<i class="fa fa-angle-right"></i>';

      /*------------------------------------
          lasest post slidr
        ------------------------------------*/
    var LatestPostSlider = function ($scope, $) {

        $('.eblog-slider-1').owlCarousel({
            loop: true,
            margin: 20,
            nav: false,
            dots: false,
            smartSpeed:1500,
            navText: [ leftArrow, rightArrow],
            responsive: {
                0: {
                    items: 1
                },
                576: {
                    items: 2
                },
                992: {
                    items: 3
                },
            }
        });

    } 
      
    /*------------------------------------
         isotop 
    ------------------------------------*/
    var PostFilter = function ($scope, $) {

         $( function() {
          var $grid = $('.grid').isotope({
            itemSelector: 'article'
          });

          // filter buttons
          $('.filters-button-group').on( 'click', 'button', function() {
            var filterValue = $( this ).attr('data-filter');
            $grid.isotope({ filter: filterValue });
          });


          if (jQuery('.grid').css('height') == '0px') { // width not set
               $('.grid.editmode' ).height( 'auto' );
            }
          
          $('.button-group').each( function( i, buttonGroup ) {
            var $buttonGroup = $( buttonGroup );
            $buttonGroup.on( 'click', 'button', function() {
              $buttonGroup.find('.is-checked').removeClass('is-checked');
              $( this ).addClass('is-checked');
            });
          });
        });

    }  


    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/style_three.default', LatestPostSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/style_seven.default', PostFilter);
    });


})(jQuery);
