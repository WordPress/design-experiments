(function() {
    function initMenu() {
        // Hide All Submenus except the current one
        jQuery('#adminmenu ul').hide();
        jQuery( '#adminmenu li.wp-has-current-submenu ul').show();
        jQuery( '#adminmenu li.wp-has-current-submenu ul').addClass( 'is-visible' );

        jQuery( '#adminmenu li a > .wp-menu-arrow' ).click( function( event ) {
            event.preventDefault();

            var submenu = jQuery(this).parents('li').find( 'ul' );

            if ( submenu.hasClass( 'is-visible' ) ) {
                submenu.hide();
                submenu.removeClass( 'is-visible' );
            } else {
                submenu.show();
                submenu.addClass( 'is-visible' );
            }
        } );
    }

    jQuery(document).ready(initMenu);
}());