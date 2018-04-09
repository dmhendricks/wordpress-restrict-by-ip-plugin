/*
 * @preserve: Custom JavaScript Logic - WP Admin
 */

;var RBIP_NS = RBIP_NS || {};

(function($, undefined) {

  RBIP_NS.Admin = {

    exampleFunction: function( name ) {

      name = name || 'world';
      console.log( 'Hello ' + name );

    }

  }

  // Write a message to the debugger console
  RBIP_NS.Admin.exampleFunction( 'James' );

})( window.jQuery );
