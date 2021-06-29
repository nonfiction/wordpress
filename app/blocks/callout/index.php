<?php
use nf\BlockType;
use function nf\import;
use function nf\css;

BlockType::register_block_type( __DIR__ . '/block.json', [

  // Server-side block rendering
  'render' => function( &$context ) { 

    // Extract values from context
    $img_url = $context['photo_url'] ?? '';

    $context['color'] ??= 'dark';

    $context['className'] ??= 'is-style-default';

    // $context['is_default'] = ( str_contains($context['className'], 'is-style-default') ) ? true : false;
    // $context['is_home'] = ( str_contains($context['className'], 'is-style-home') ) ? true : false;
    // $context['is_hotel'] = ( str_contains($context['className'], 'is-style-hotel') ) ? true : false;

    // Return a twig template for Timber to render
    return (<<<END

      <div class="nf-callout {{ className }} is-color-{{ color }}">
        <div class="nf-callout__image" style="background-image:url({{ photo_url }});"></div>
        <div class="nf-callout__content">
          <div class="nf-callout__inner">
            {% if icon_url %}<img class="nf-callout__icon" src="{{ icon_url }}">{% endif %}
            <h3 class="nf-callout__title">{{ title }}</h3>
            <div class="nf-callout__description">{{ description }}</div>
          </div>
          <div class="nf-callout__link">
            <a class="nf-callout__button" href="{{ button_link }}">{{ button_text }}</a>
          </div>
        </div>
      </div>

    END);
  },

]);
