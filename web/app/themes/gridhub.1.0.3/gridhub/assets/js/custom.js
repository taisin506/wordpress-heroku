jQuery(document).ready(function($) {
    'use strict';

    if(gridhub_ajax_object.secondary_menu_active){

        $(".gridhub-nav-secondary .gridhub-secondary-nav-menu").addClass("gridhub-secondary-responsive-menu");

        $(".gridhub-secondary-responsive-menu-icon").click(function(){
            $(this).next(".gridhub-nav-secondary .gridhub-secondary-nav-menu").slideToggle();
        });

        $(window).resize(function(){
            if(window.innerWidth > 1112) {
                $(".gridhub-nav-secondary .gridhub-secondary-nav-menu, nav .sub-menu, nav .children").removeAttr("style");
                $(".gridhub-secondary-responsive-menu > li").removeClass("gridhub-secondary-menu-open");
            }
        });

        $(".gridhub-secondary-responsive-menu > li").click(function(event){
            if (event.target !== this)
            return;
            $(this).find(".sub-menu:first").toggleClass('gridhub-submenu-toggle').parent().toggleClass("gridhub-secondary-menu-open");
            $(this).find(".children:first").toggleClass('gridhub-submenu-toggle').parent().toggleClass("gridhub-secondary-menu-open");
        });

        $("div.gridhub-secondary-responsive-menu > ul > li").click(function(event) {
            if (event.target !== this)
                return;
            $(this).find("ul:first").toggleClass('gridhub-submenu-toggle').parent().toggleClass("gridhub-secondary-menu-open");
        });

    }

    if(gridhub_ajax_object.primary_menu_active){

        if(gridhub_ajax_object.sticky_menu_active){
        // grab the initial top offset of the navigation 
        var gridhubstickyNavTop = $('.gridhub-primary-menu-container').offset().top;
        
        // our function that decides weather the navigation bar should have "fixed" css position or not.
        var gridhubstickyNav = function(){
            var gridhubscrollTop = $(window).scrollTop(); // our current vertical position from the top
                 
            // if we've scrolled more than the navigation, change its position to fixed to stick to top,
            // otherwise change it back to relative

            if(gridhub_ajax_object.sticky_mobile_menu_active){
                if (gridhubscrollTop > gridhubstickyNavTop) {
                    $('.gridhub-primary-menu-container').addClass('gridhub-fixed');
                } else {
                    $('.gridhub-primary-menu-container').removeClass('gridhub-fixed');
                }
            } else {
                if(window.innerWidth > 1112) {
                    if (gridhubscrollTop > gridhubstickyNavTop) {
                        $('.gridhub-primary-menu-container').addClass('gridhub-fixed');
                    } else {
                        $('.gridhub-primary-menu-container').removeClass('gridhub-fixed'); 
                    }
                }
            }
        };

        gridhubstickyNav();
        // and run it again every time you scroll
        $(window).scroll(function() {
            gridhubstickyNav();
        });
        }

        //$(".gridhub-nav-primary .gridhub-primary-nav-menu").addClass("gridhub-primary-responsive-menu").before('<div class="gridhub-primary-responsive-menu-icon"></div>');
        $(".gridhub-nav-primary .gridhub-primary-nav-menu").addClass("gridhub-primary-responsive-menu");

        $(".gridhub-primary-responsive-menu-icon").click(function(){
            $(this).next(".gridhub-nav-primary .gridhub-primary-nav-menu").slideToggle();
        });

        $(window).resize(function(){
            if(window.innerWidth > 1112) {
                $(".gridhub-nav-primary .gridhub-primary-nav-menu, nav .sub-menu, nav .children").removeAttr("style");
                $(".gridhub-primary-responsive-menu > li").removeClass("gridhub-primary-menu-open");
            }
        });

        $(".gridhub-primary-responsive-menu > li").click(function(event){
            if (event.target !== this)
            return;
            $(this).find(".sub-menu:first").toggleClass('gridhub-submenu-toggle').parent().toggleClass("gridhub-primary-menu-open");
            $(this).find(".children:first").toggleClass('gridhub-submenu-toggle').parent().toggleClass("gridhub-primary-menu-open");
        });

        $("div.gridhub-primary-responsive-menu > ul > li").click(function(event) {
            if (event.target !== this)
                return;
            $(this).find("ul:first").toggleClass('gridhub-submenu-toggle').parent().toggleClass("gridhub-primary-menu-open");
        });

    }

    if($(".gridhub-social-icon-search").length){
        $(".gridhub-social-icon-search").on('click', function (e) {
            e.preventDefault();
            $('.gridhub-search-overlay').slideToggle();
            const gridhub_focusableelements = 'button, [href], input';
            const gridhub_search_modal = document.querySelector('#gridhub-search-overlay-wrap');
            const gridhub_firstfocusableelement = gridhub_search_modal.querySelectorAll(gridhub_focusableelements)[0];
            const gridhub_focusablecontent = gridhub_search_modal.querySelectorAll(gridhub_focusableelements);
            const gridhub_lastfocusableelement = gridhub_focusablecontent[gridhub_focusablecontent.length - 1];
            document.addEventListener('keydown', function(e) {
              let isTabPressed = e.key === 'Tab' || e.keyCode === 9;
              if (!isTabPressed) {
                return;
              }
              if (e.shiftKey) {
                if (document.activeElement === gridhub_firstfocusableelement) {
                  gridhub_lastfocusableelement.focus();
                  e.preventDefault();
                }
              } else {
                if (document.activeElement === gridhub_lastfocusableelement) {
                  gridhub_firstfocusableelement.focus();
                  e.preventDefault();
                }
              }
            });
            gridhub_firstfocusableelement.focus();
        });
    }

    if($(".gridhub-search-closebtn").length){
        $(".gridhub-search-closebtn").on('click', function (e) {
            e.preventDefault();
            $('.gridhub-search-overlay').slideToggle();
        });
    }

    $(".entry-content, .widget").fitVids();

    if($(".gridhub-scroll-top").length){
        var gridhub_scroll_button = $( '.gridhub-scroll-top' );
        gridhub_scroll_button.hide();

        $( window ).scroll( function () {
            if ( $( window ).scrollTop() < 20 ) {
                $( '.gridhub-scroll-top' ).fadeOut();
            } else {
                $( '.gridhub-scroll-top' ).fadeIn();
            }
        } );

        gridhub_scroll_button.click( function () {
            $( "html, body" ).animate( { scrollTop: 0 }, 300 );
            return false;
        } );
    }

    if(gridhub_ajax_object.sticky_sidebar_active){
        $('.gridhub-main-wrapper, .gridhub-sidebar-one-wrapper').theiaStickySidebar({
            containerSelector: ".gridhub-content-wrapper",
            additionalMarginTop: 0,
            additionalMarginBottom: 0,
            minWidth: 960,
        });

        $(window).resize(function(){
            $('.gridhub-main-wrapper, .gridhub-sidebar-one-wrapper').theiaStickySidebar({
                containerSelector: ".gridhub-content-wrapper",
                additionalMarginTop: 0,
                additionalMarginBottom: 0,
                minWidth: 960,
            });
        });
    }

});