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

      <div class="nf-hotel-reviews">
        <a id="reviews"></a>
        <h3>Reviews</h3>

        {% if hotel.meta('trustyou_id') is defined %}
          <iframe src="//api.trustyou.com/hotels/{{ hotel.meta('trustyou_id') }}/tops_flops.html?iframe_resizer=true" 
                  id="trust-you-iframe" scrolling="no" frameborder="0" ></iframe>
        {% endif %}

      </div>


    END);

  },

]);
