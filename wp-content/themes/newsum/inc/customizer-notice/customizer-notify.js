/**
 * NewSum Customizer Notify Scripts
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        // Add custom class to body for styling
        $('body').addClass('newsum-customizer-notify-active');
        
        // Handle button clicks with analytics tracking
        $('.newsum-customizer-notify-pro-btn').on('click', function(e) {
            // Track pro button click
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    'event_category': 'Customizer',
                    'event_label': 'Upgrade to Pro Button'
                });
            }
        });
        
        $('.newsum-customizer-notify-demo-btn').on('click', function(e) {
            // Track demo button click
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    'event_category': 'Customizer',
                    'event_label': 'Pro Demo Button'
                });
            }
        });
        
        $('.newsum-customizer-notify-help-btn').on('click', function(e) {
            // Track help button click
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    'event_category': 'Customizer',
                    'event_label': 'Ask Help Button'
                });
            }
        });

        // Add hover effects
        $('.newsum-customizer-notify-pro-btn, .newsum-customizer-notify-demo-btn').hover(
            function() {
                $(this).addClass('newsum-btn-hover');
            },
            function() {
                $(this).removeClass('newsum-btn-hover');
            }
        );

        // Add click animation
        $('.newsum-customizer-notify-pro-btn, .newsum-customizer-notify-demo-btn').on('mousedown', function() {
            $(this).addClass('newsum-btn-active');
        }).on('mouseup mouseleave', function() {
            $(this).removeClass('newsum-btn-active');
        });

        // Position the notify section after theme info
        function positionNotifySection() {
            var $notifySection = $('.control-section-newsum-notify-section');
            var $themeSection = $('#accordion-section-themes');

            if ($notifySection.length && $themeSection.length) {
                $notifySection.insertAfter($themeSection);
            }
        }

        // Position on load
        positionNotifySection();

        // Position when customizer is fully loaded
        wp.customize.bind('ready', function() {
            setTimeout(positionNotifySection, 200);
        });

        // Handle section expansion (prevent expansion)
        $(document).on('click', '.control-section-newsum-notify-section .accordion-section-title', function(e) {
            // Don't expand the section, just handle button clicks
            if (!$(e.target).is('a')) {
                e.preventDefault();
                e.stopPropagation();
            }
        });

        // Remove arrow icons completely
        function removeArrowIcons() {
            // Remove all possible arrow/expand indicators
            $('.control-section-newsum-notify-section .accordion-section-title:after').remove();
            $('#accordion-section-newsum-customizer-notify-section .accordion-section-title:after').remove();

            // Hide with CSS as backup
            var customCSS = `
                <style id="newsum-notify-arrow-fix">
                #accordion-section-newsum-customizer-notify-section .accordion-section-title:after,
                .control-section-newsum-notify-section .accordion-section-title:after {
                    display: none !important;
                    content: none !important;
                }
                </style>
            `;

            if (!$('#newsum-notify-arrow-fix').length) {
                $('head').append(customCSS);
            }
        }

        // Remove arrows on load and periodically
        removeArrowIcons();
        setTimeout(removeArrowIcons, 500);
        setTimeout(removeArrowIcons, 1000);

    });

})(jQuery);

// Minimal additional CSS
jQuery(document).ready(function($) {
    // Ensure section is visible
    $('.control-section-newsum-notify-section').css({
        'display': 'block',
        'visibility': 'visible',
        'opacity': '1'
    });
});
