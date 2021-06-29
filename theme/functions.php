<?php

// Load app directory (post types, block types, twig views, assets)
require_once( ROOT_DIR . '/app/app.php' );

// Theme options
add_theme_support( 'menus' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'align-wide' );
remove_theme_support( 'core-block-patterns' );

// Set values used in most templates
add_filter( 'timber/context', function($context) {

  $context['site'] = new Timber\Site();
  $context['menu_brand'] = new nf\Menu( 'Brand' );
  $context['menu_primary'] = new nf\Menu( 'Primary' );
  $context['menu_footer'] = new nf\Menu( 'Footer' );

  $context['img'] = home_url() . '/assets/img';
  $context['s'] = get_search_query();

  $context['post'] = nf\PostType::get_post();
  $context['posts'] = nf\PostType::get_posts();

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
  $twig->addFilter( new \Twig\TwigFilter( 'extract_image', function( $input ) {
    if ( preg_match("%(?<=src=\")([^\"])+(png|jpg|jpeg|gif|svg)%i",$input,$result)) {
      return $result[0];
    }
  }));

  return $twig;

});
