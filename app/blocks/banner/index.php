<?php
use nf\BlockType;
use function nf\import;
use function nf\css;

use Timber\Timber;
use Timber\Post;
use Timber\PostQuery;

// BlockType::register_block_type( 'nf/banner', array_merge([
BlockType::register_block_type( __DIR__ . '/block.json', [

  // Server-side block rendering
  'render' => function( &$context ) { 

    // Optionally modify the context
    $context['foo'] = 'bar';
    $context['button_text'] ??= "See More";

    // Extract values from context
    foreach(['background_url','background_id'] as $key) { $context[$key] ??= ''; }
    ['background_url' => $img_url, 'background_id' => $img_id] = $context;


    // Add new values to context
    $context['style'] = css([ 'background-image' => "url($img_url)" ]);
    $context['color'] ??= 'dark';
    // <h3 class="nf-banner__subheading">Proudly<br>family-owned &amp;<br>operated for over<br><strong>40 years.</strong></h3>

    // Return a twig template for Timber to render
    return (<<<END

      <div class="nf-banner {{ className }} is-color-{{ color }}" style="{{ style }}"> 
        <div class="nf-banner__main">
          <div class="nf-banner__inner">
            <h1 class="nf-banner__heading">{{ heading }}</h1>
            <p class="nf-banner__content">{{ content }}</p>
          </div>
        </div>
        <div class="nf-banner__aside">
          <h3 class="nf-banner__subheading">{{ subheading }}</h3>
          <a class="nf-banner__button" href="{{ button_link }}">{{ button_text }}</a>
        </div>
      </div>

    END);
  },

]);
