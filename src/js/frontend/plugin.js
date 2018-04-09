/*
 * @preserve: Custom JavaScript Logic - Frontend
 */

;var RBIP_NS = RBIP_NS || {};

(function($, undefined) {

  RBIP_NS.Site = {

    sampleFunction: function( name ) {

      name = name || 'world';
      console.log( 'Hello ' + name + '!' )

    }

  }

  // Write a message to the debugger console
  //RBIP_NS.Site.sampleFunction( 'Darlene' );

})( window.jQuery );
