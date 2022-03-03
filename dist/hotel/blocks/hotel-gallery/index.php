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
      <div class="nf-hotel-gallery">

        <ul class="nf-hotel-gallery__images">
          {% for image,caption in hotel.gallery_images %}
            <li class="kenburns-top" data-caption="{{ caption | base64 }}" data-keyframe="ff1" data-direction="fff3"> <img src="{{ image }}" alt="{{ caption }}"></li>
          {% endfor %}
        </ul>

        <div class="gallery-prev-next-container">
          <div class="gallery-prev-button gallery-step-button">
          </div>
          <div class="gallery-next-button gallery-step-button">
          </div>
          <div class="gallery-grid-select">
          <img src="/assets/img/grid-white.png" />
          </div>
        </div>

        <div class="grid-gallery-items">
            {% for image,caption in hotel.gallery_images %}
                <div class="grid-owl-item" data-id="caption">
                  <img src="{{ image }}" />
                  <p class="grid-gallery-caption">{{caption}}</p>
                </div>
            {% endfor %}
        </div>

        <div class="container">
          <h1 class="hotel-title">{{ hotel.title }}</h1>
        </div>

        {% for image,caption in hotel.gallery_images %}
          {% if caption %}<div class="nf-hotel-gallery__caption" data-caption="{{ caption | base64 }}">{{ caption }}</div>{% endif %}
        {% endfor %}


      </div>


    END);

  },

]);
