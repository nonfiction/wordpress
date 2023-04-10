<?php

namespace nf;

// Load app directory (post types, block types, twig views, assets)
require_once( ROOT_DIR . '/app/app.php' );

// Theme options
add_theme_support( 'menus' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'align-wide' );
remove_theme_support( 'core-block-patterns' );

add_filter('wp_enqueue_scripts', function() {
  wp_deregister_style('classic-theme-styles');
  wp_dequeue_style('classic-theme-styles');
}, 100);

// Set values used in most templates
add_filter( 'timber/context', function($context) {

  $context['site'] = new \Timber\Site();
  $context['menu_primary'] = Menu::get_menu( 'primary' );
  // $context['menu_footer'] = Menu::get_menu( 'footer' );
  // $context['menu_brand'] = Menu::get_menu( 'brand' );

  $context['img'] = '/assets/img';
  $context['s'] = get_search_query();

  $context['post'] = \Timber::get_post();
  $context['posts'] = \Timber::get_posts();

  return $context;

});


// Add some additional twig functions
add_filter( 'timber/twig', function( $twig ) {

  // Adding a function.
  $twig->addFunction( new \Twig\TwigFunction( 'edit_post_link', 'edit_post_link' ) );

  // Query posts from twig
  $twig->addFunction( new \Twig\TwigFunction( 'PostQuery', function( $args ) {
     return nf\PostType::get_posts( $args );
  }));

  // Adding functions as filters.
  $twig->addFunction( new \Twig\TwigFunction( 'debug', function( $args ) {
    return '<pre>' . print_r($args, true) . '</pre>';
  }));

  $twig->addFilter( new \Twig\TwigFilter( 'titleize', function( $input ) {
    return (is_empty($input)) ? '' : titleize($input);
  }));

  $twig->addFilter( new \Twig\TwigFilter( 'humanize', function( $input ) {
    return (is_empty($input)) ? '' : humanize($input);
  }));

  $twig->addFilter( new \Twig\TwigFilter( 'decode', function( $input ) {
    return (is_empty($input)) ? '' : base64_decode($input);
  }));

  $twig->addFilter( new \Twig\TwigFilter( 'padded', function( $input ) {
    return (is_empty($input)) ? '' : str_pad($input, 2, '0', STR_PAD_LEFT);
  }));

  $twig->addFilter( new \Twig\TwigFilter( 'currency', function( $input ) {
    return (is_empty($input)) ? '' : number_format((float)$input, 2, '.', '');
  }));

  $twig->addFilter( new \Twig\TwigFilter( 'strtotime', function( $input ) {
    return (is_empty($input)) ? '' : strtotime($input);
  }));

  $twig->addFilter( new \Twig\TwigFilter( 'extract_image', function( $input ) {
    if ( preg_match("%(?<=src=\")([^\"])+(png|jpg|jpeg|gif|svg)%i",$input,$result)) {
      return $result[0];
    }
  }));

  return $twig;

});


add_action('admin_bar_menu', function($admin_bar){

  $admin_bar->add_menu([
    'id'     => 'flush',
    'href'   => '/wp/wp-admin/options-general.php?page=flush',
    'parent' => 'top-secondary',
    'title'  => 'Flush',
  ]);

}, 100);
