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

      <div class="nf-hotel-amenities">

        <a id="amenities"></a>
        <h3>Amenities</h3>

        <ul>
          {% for term in hotel.get_terms('amenity') %}
            <li class="nf-hotel-amenity" data-amenity="{{ term.slug}}">{{ term.title}}</li>
          {% endfor %}
        </ul>


      </div>


    END);

  },

]);
