<?php

namespace nf;
use \Timber;

class Page extends PostType {}

// Register Custom Post Type
add_action( 'init', function() {

  Page::register_post_type( __DIR__ . '/post.json' );


  // Register block pattern
  register_block_pattern( 'nf/internal-page', [

    'title'       => 'Internal Page',
    'description' => 'Banner with an image sidebar',
    'categories' => ['text'],
    'keywords' => ['nf'],
    'content'     => (<<<END

      <!-- wp:nf/banner {"heading":"Page Title","background_url":"/app/site/assets/img/banner.jpg"} /-->

      <!-- wp:media-text {"mediaType":"image","mediaWidth":30,"verticalAlignment":"top"} -->
      <div class="wp-block-media-text alignwide is-stacked-on-mobile is-vertically-aligned-top" style="grid-template-columns:30% auto"><figure class="wp-block-media-text__media"><img src="/app/site/assets/img/side.jpg" alt=""/></figure><div class="wp-block-media-text__content"><!-- wp:heading {"level":4} -->
      <h4>My heading goes here</h4>
      <!-- /wp:heading -->

      <!-- wp:paragraph -->
      <p>My paragraph goes here!</p>
      <!-- /wp:paragraph --></div></div>
      <!-- /wp:media-text -->

    END),

  ] );


  register_block_pattern( 'nf/info-page', [

    'title'       => 'Info Page',
    'description' => 'Banner with a text sidebar',
    'categories' => ['text'],
    'keywords' => ['nf'],
    'content'     => (<<<END

    <!-- wp:nf/banner {"heading":"Page Title","background_url":"/app/site/assets/img/banner.jpg"} /-->

    <!-- wp:columns -->
    <div class="wp-block-columns"><!-- wp:column {"width":"66.66%","className":"is-style-default"} -->
    <div class="wp-block-column is-style-default" style="flex-basis:66.66%"><!-- wp:heading -->
    <h2>This is my heading</h2>
    <!-- /wp:heading -->

    <!-- wp:paragraph -->
    <p>This is my text</p>
    <!-- /wp:paragraph --></div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column"><!-- wp:nf/aside -->
    <div class="wp-block-nf-aside"><header class="nf-aside__header"></header><div class="nf-aside__content"><!-- wp:paragraph -->
    <p>Here is more text.</p>
    <!-- /wp:paragraph --></div></div>
    <!-- /wp:nf/aside --></div>
    <!-- /wp:column --></div>
    <!-- /wp:columns -->

    END),

  ] );

}, 18);
