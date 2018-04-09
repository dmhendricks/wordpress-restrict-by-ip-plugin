<?php
namespace CloudVerve\RestrictByIP\Widgets;
use CloudVerve\RestrictByIP\Plugin;

class Widget_Loader extends Plugin {

  public function __construct() {

    // Register widgets
    add_action( 'widgets_init', function () {

      register_widget( new Example_Widget() );

    });

  }

}
