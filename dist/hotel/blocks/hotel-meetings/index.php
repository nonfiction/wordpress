<?php
use nf\BlockType;
use function nf\import;
use function nf\merge;
use function nf\css;

BlockType::register_block_type( __DIR__ . '/block.json', [

  // Server-side block rendering
  'render' => function( &$context ) { 

    // $context['event_rooms_count'] ??= 0;
    // $context['event_space_size'] ??= 0;
    // $context['event_space_capacity'] ??= 0;
    // $context['max_breakout_rooms'] ??= 0;

    // // $context['meetings_image'] ??= 'https://unsplash.it/600/300';

    global $post;
    $context['hotel'] = nf\Hotel::get_post($post->ID);


    return (<<<END

      <div class="nf-hotel-meetings">

        <a id="meetings"></a>
        <h3>Meetings</h3>

        <div class="container">

          <div class="nf-hotel-meetings__main">
            {{ inner }}
            <a class="nf-hotel-meetings__button" href="{{ link }}">Learn More</a>
          </div>

          <div class="nf-hotel-meetings__aside">

            <header class="nf-hotel-meetings__aside-header">At a Glance...</header>

            <div class="nf-hotel-meetings__aside-content">

              {% if hotel.meta('event_rooms_count') != '' %}
              <div class="nf-hotel-meetings__event_rooms_count">{{ hotel.meta('event_rooms_count') }}</div>
              <label>Event Room{% if hotel.meta('event_rooms_count') != '1'%}s{% endif %}</label>
              <hr>
              {% endif %}

              {% if hotel.meta('event_space_size') != '' %}
              <div class="nf-hotel-meetings__event_space_size">{{ hotel.meta('event_space_size') }}</div>
              <label>Sq Ft of Largest Event Space</label>
              <hr>
              {% endif %}

              {% if hotel.meta('event_space_capacity') != '' %}
              <div class="nf-hotel-meetings__event_space_capacity">{{ hotel.meta('event_space_capacity') }}</div>
              <label>Capacity of Largest Event Space</label>
              <hr>
              {% endif %}

              {% if hotel.meta('max_breakout_rooms') != '' %}
              <div class="nf-hotel-meetings__max_breakout_rooms">{{ hotel.meta('max_breakout_rooms') }}</div>
              <label>Maximum Breakout Room{% if hotel.meta('max_breakout_rooms') != '1'%}s{% endif %}</label>
              <hr>
              {% endif %}

              <a class="nf-hotel-meetings__button aside" href="/contact">Contact Events</a>

            </div>

          </div>

        </div>
      </div>


    END);

  },

]);
