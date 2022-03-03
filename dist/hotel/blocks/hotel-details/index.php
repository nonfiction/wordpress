<?php
use nf\BlockType;
use function nf\import;
use function nf\merge;
use function nf\css;

BlockType::register_block_type( __DIR__ . '/block.json', [

  // Server-side block rendering
  'render' => function( &$context ) { 

    $context['check_in'] ??= '3:00 PM';
    $context['check_out'] ??= '11:00 AM';
    // $context['meetings_image'] ??= 'https://unsplash.it/600/300';

    global $post;
    $context['hotel'] = nf\Hotel::get_post($post->ID);


    return (<<<END

      <div class="nf-hotel-details">
        <div class="container">

          <div class="nf-hotel-details__main">
            {{ inner }}
          </div>

          <div class="nf-hotel-details__aside">

            <header class="nf-hotel-details__aside-header">{{ hotel.title }}</header>

            <div class="nf-hotel-details__aside-content">
              <div class="nf-hotel-details__address">{{ address }}</div>
              <div class="nf-hotel-details__phone">{{ phone }}</div>
              <hr>
              <div class="nf-hotel-details__times">
                <div class="nf-hotel-details__check-in">
                  <label>CHECK-IN</label>
                  <time>{{ check_in }}</time>
                </div>
                <div class="nf-hotel-details__check-out">
                  <label>CHECK-OUT</label>
                  <time>{{ check_out }}</time>
                </div>
              </div>
              <hr>
              {% if hotel.meta('trustyou_id') is defined %}
                <div class="nf-hotel-details__reviews">
                <label>Reviews</label>
                <iframe src="//api.trustyou.com/hotels/{{ hotel.meta('trustyou_id') }}/seal.html?iframe_resizer=true&amp;size=m" 
                        id="trust-you-iframe-details" scrolling="no" frameborder="0" style="overflow: hidden;"></iframe>
                </div>
                <a class="nf-hotel-details__button" href="#reviews">Read Reviews</a>
              {% endif %}
            </div>

          </div>

        </div>
      </div>


    END);

  },

]);


add_action( 'init', function() {
	register_post_meta( 'hotel', 'my_custom_bool', [
		'show_in_rest' => true,
		'single' => true,
		'type' => 'boolean',
	] );
 
	register_post_meta( 'hotel', 'my_custom_text', [
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
	] );
} );
