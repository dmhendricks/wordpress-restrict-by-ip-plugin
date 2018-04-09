<?php
namespace CloudVerve\RestrictByIP;
//use WordPress_ToolKit\ObjectCache;

class Core extends Plugin {

  function __construct() {

    // Example - Add page, post type and parent classes to <body> tag for selector targeting
    //add_filter( 'body_class', array( &$this, 'add_body_classes' ) );

  }

  /**
    * Returns string of addition CSS classes based on post type
    *
    * Returns CSS classes such as page-{slug}, parent-{slug}, post-type-{type} and
    *   category-{slug} for easier selector targeting
    *
    * @param array $classes An array of *current* body_class classes
    * @return array Modified array of body classes including new ones
    * @since 0.1.0
    */
  public function add_body_classes($classes) {
    $parent_slug = Helpers::get_parent_slug(true);
    $categories = is_single() ? Helpers::get_post_categories(true) : array();

    // Add page, parent and post-type classes, if available
    $classes[] = 'page-' . Helpers::get_page_slug();
    if( $parent_slug ) $classes[] = 'parent-' . $parent_slug;
    $classes[] = 'post-type-' . get_post_type();

    // Add category slugs
    foreach( $categories as $cat ) {
      $classes[] = 'category-' . $cat;
    }

    return $classes;
  }

}
