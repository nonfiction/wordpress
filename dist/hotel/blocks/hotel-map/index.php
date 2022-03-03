<?php
use nf\BlockType;
use function nf\import;
use function nf\merge;
use function nf\css;

BlockType::register_block_type( __DIR__ . '/block.json', [

  // Server-side block rendering
  'render' => function( &$context ) { 

    global $post;
    $context['hotel'] = nf\Hotel::get_post($post->ID);


    return (<<<END

      <div class="nf-hotel-map">
        <a id="directions"></a>
        <h3>Maps & Directions</h3>

        <div class="container">

          <div id="hotel-map" 
          data-latitude="{{ hotel.meta('latitude') }}" 
          data-longitude="{{ hotel.meta('longitude') }}"
          ></div>

        </div>

        <a class="nf-hotel-map__button" href="{{ directions }}" target="_blank">Get Directions to Hotel</a>
      </div>

    END);

  },

]);
