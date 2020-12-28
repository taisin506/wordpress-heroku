/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
    var gridhub_secondary_container, gridhub_secondary_button, gridhub_secondary_menu, gridhub_secondary_links, gridhub_secondary_i, gridhub_secondary_len;

    gridhub_secondary_container = document.getElementById( 'gridhub-secondary-navigation' );
    if ( ! gridhub_secondary_container ) {
        return;
    }

    gridhub_secondary_button = gridhub_secondary_container.getElementsByTagName( 'button' )[0];
    if ( 'undefined' === typeof gridhub_secondary_button ) {
        return;
    }

    gridhub_secondary_menu = gridhub_secondary_container.getElementsByTagName( 'ul' )[0];

    // Hide menu toggle button if menu is empty and return early.
    if ( 'undefined' === typeof gridhub_secondary_menu ) {
        gridhub_secondary_button.style.display = 'none';
        return;
    }

    gridhub_secondary_menu.setAttribute( 'aria-expanded', 'false' );
    if ( -1 === gridhub_secondary_menu.className.indexOf( 'nav-menu' ) ) {
        gridhub_secondary_menu.className += ' nav-menu';
    }

    gridhub_secondary_button.onclick = function() {
        if ( -1 !== gridhub_secondary_container.className.indexOf( 'gridhub-toggled' ) ) {
            gridhub_secondary_container.className = gridhub_secondary_container.className.replace( ' gridhub-toggled', '' );
            gridhub_secondary_button.setAttribute( 'aria-expanded', 'false' );
            gridhub_secondary_menu.setAttribute( 'aria-expanded', 'false' );
        } else {
            gridhub_secondary_container.className += ' gridhub-toggled';
            gridhub_secondary_button.setAttribute( 'aria-expanded', 'true' );
            gridhub_secondary_menu.setAttribute( 'aria-expanded', 'true' );
        }
    };

    // Get all the link elements within the menu.
    gridhub_secondary_links    = gridhub_secondary_menu.getElementsByTagName( 'a' );

    // Each time a menu link is focused or blurred, toggle focus.
    for ( gridhub_secondary_i = 0, gridhub_secondary_len = gridhub_secondary_links.length; gridhub_secondary_i < gridhub_secondary_len; gridhub_secondary_i++ ) {
        gridhub_secondary_links[gridhub_secondary_i].addEventListener( 'focus', gridhub_secondary_toggleFocus, true );
        gridhub_secondary_links[gridhub_secondary_i].addEventListener( 'blur', gridhub_secondary_toggleFocus, true );
    }

    /**
     * Sets or removes .focus class on an element.
     */
    function gridhub_secondary_toggleFocus() {
        var self = this;

        // Move up through the ancestors of the current link until we hit .nav-menu.
        while ( -1 === self.className.indexOf( 'nav-menu' ) ) {

            // On li elements toggle the class .focus.
            if ( 'li' === self.tagName.toLowerCase() ) {
                if ( -1 !== self.className.indexOf( 'gridhub-focus' ) ) {
                    self.className = self.className.replace( ' gridhub-focus', '' );
                } else {
                    self.className += ' gridhub-focus';
                }
            }

            self = self.parentElement;
        }
    }

    /**
     * Toggles `focus` class to allow submenu access on tablets.
     */
    ( function( gridhub_secondary_container ) {
        var touchStartFn, gridhub_secondary_i,
            parentLink = gridhub_secondary_container.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

        if ( 'ontouchstart' in window ) {
            touchStartFn = function( e ) {
                var menuItem = this.parentNode, gridhub_secondary_i;

                if ( ! menuItem.classList.contains( 'gridhub-focus' ) ) {
                    e.preventDefault();
                    for ( gridhub_secondary_i = 0; gridhub_secondary_i < menuItem.parentNode.children.length; ++gridhub_secondary_i ) {
                        if ( menuItem === menuItem.parentNode.children[gridhub_secondary_i] ) {
                            continue;
                        }
                        menuItem.parentNode.children[gridhub_secondary_i].classList.remove( 'gridhub-focus' );
                    }
                    menuItem.classList.add( 'gridhub-focus' );
                } else {
                    menuItem.classList.remove( 'gridhub-focus' );
                }
            };

            for ( gridhub_secondary_i = 0; gridhub_secondary_i < parentLink.length; ++gridhub_secondary_i ) {
                parentLink[gridhub_secondary_i].addEventListener( 'touchstart', touchStartFn, false );
            }
        }
    }( gridhub_secondary_container ) );
} )();


( function() {
    var gridhub_primary_container, gridhub_primary_button, gridhub_primary_menu, gridhub_primary_links, gridhub_primary_i, gridhub_primary_len;

    gridhub_primary_container = document.getElementById( 'gridhub-primary-navigation' );
    if ( ! gridhub_primary_container ) {
        return;
    }

    gridhub_primary_button = gridhub_primary_container.getElementsByTagName( 'button' )[0];
    if ( 'undefined' === typeof gridhub_primary_button ) {
        return;
    }

    gridhub_primary_menu = gridhub_primary_container.getElementsByTagName( 'ul' )[0];

    // Hide menu toggle button if menu is empty and return early.
    if ( 'undefined' === typeof gridhub_primary_menu ) {
        gridhub_primary_button.style.display = 'none';
        return;
    }

    gridhub_primary_menu.setAttribute( 'aria-expanded', 'false' );
    if ( -1 === gridhub_primary_menu.className.indexOf( 'nav-menu' ) ) {
        gridhub_primary_menu.className += ' nav-menu';
    }

    gridhub_primary_button.onclick = function() {
        if ( -1 !== gridhub_primary_container.className.indexOf( 'gridhub-toggled' ) ) {
            gridhub_primary_container.className = gridhub_primary_container.className.replace( ' gridhub-toggled', '' );
            gridhub_primary_button.setAttribute( 'aria-expanded', 'false' );
            gridhub_primary_menu.setAttribute( 'aria-expanded', 'false' );
        } else {
            gridhub_primary_container.className += ' gridhub-toggled';
            gridhub_primary_button.setAttribute( 'aria-expanded', 'true' );
            gridhub_primary_menu.setAttribute( 'aria-expanded', 'true' );
        }
    };

    // Get all the link elements within the menu.
    gridhub_primary_links    = gridhub_primary_menu.getElementsByTagName( 'a' );

    // Each time a menu link is focused or blurred, toggle focus.
    for ( gridhub_primary_i = 0, gridhub_primary_len = gridhub_primary_links.length; gridhub_primary_i < gridhub_primary_len; gridhub_primary_i++ ) {
        gridhub_primary_links[gridhub_primary_i].addEventListener( 'focus', gridhub_primary_toggleFocus, true );
        gridhub_primary_links[gridhub_primary_i].addEventListener( 'blur', gridhub_primary_toggleFocus, true );
    }

    /**
     * Sets or removes .focus class on an element.
     */
    function gridhub_primary_toggleFocus() {
        var self = this;

        // Move up through the ancestors of the current link until we hit .nav-menu.
        while ( -1 === self.className.indexOf( 'nav-menu' ) ) {

            // On li elements toggle the class .focus.
            if ( 'li' === self.tagName.toLowerCase() ) {
                if ( -1 !== self.className.indexOf( 'gridhub-focus' ) ) {
                    self.className = self.className.replace( ' gridhub-focus', '' );
                } else {
                    self.className += ' gridhub-focus';
                }
            }

            self = self.parentElement;
        }
    }

    /**
     * Toggles `focus` class to allow submenu access on tablets.
     */
    ( function( gridhub_primary_container ) {
        var touchStartFn, gridhub_primary_i,
            parentLink = gridhub_primary_container.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

        if ( 'ontouchstart' in window ) {
            touchStartFn = function( e ) {
                var menuItem = this.parentNode, gridhub_primary_i;

                if ( ! menuItem.classList.contains( 'gridhub-focus' ) ) {
                    e.preventDefault();
                    for ( gridhub_primary_i = 0; gridhub_primary_i < menuItem.parentNode.children.length; ++gridhub_primary_i ) {
                        if ( menuItem === menuItem.parentNode.children[gridhub_primary_i] ) {
                            continue;
                        }
                        menuItem.parentNode.children[gridhub_primary_i].classList.remove( 'gridhub-focus' );
                    }
                    menuItem.classList.add( 'gridhub-focus' );
                } else {
                    menuItem.classList.remove( 'gridhub-focus' );
                }
            };

            for ( gridhub_primary_i = 0; gridhub_primary_i < parentLink.length; ++gridhub_primary_i ) {
                parentLink[gridhub_primary_i].addEventListener( 'touchstart', touchStartFn, false );
            }
        }
    }( gridhub_primary_container ) );
} )();