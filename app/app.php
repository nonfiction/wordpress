<?php
namespace nf;

App::init( ROOT_DIR );

// Import all post/block types
App::import([ 
    'config/wp/*.php',           // wp tweaks
    'app/*/index.php',           // post types
    'app/blocks/*/index.php',    // block types
    'app/*/blocks/*/index.php',  // block types nested under post types
]);

// Set directories where twig views are found
// "front.twig"
// "page/page-about.twig"
// "story/tease.twig"
// "story/blocks/story-heading/single.twig"
App::views([ 
    'app/views',  
    'app' 
]);

// Enqueue assets from webpack manifest
App::enqueue( "web/assets/dist/".WP_ENV.".json" );
