<?php
use nf\BlockType;
use function nf\import;
use function nf\merge;
use function nf\css;

BlockType::register_block_type( __DIR__ . '/block.json', [

  // Server-side block rendering
  
  'render' => function( &$context ) { 

    return (<<<END

      <table class="nf-hotel-meeting-table">
        <tr>
          <th>Hotel</th>
          <th>Meeting Room Size (sq ft)</th>
          <th>Max Capacity</th>
          <th class="nf-table-end">Details</th>
        </tr>
        {% for hotel in PostQuery({ post_type: 'hotel', orderby: 'title', order: 'ASC', posts_per_page: -1, }) %}
        {% if hotel.event_space_size %}
          <tr>
            <td>{{ hotel.title }}</td>
            <td>{{ hotel.event_space_size }} sq ft</td>
            <td>{{ hotel.event_space_capacity }} people</td>
            <td><a href="/hotels/{{ hotel.correct_link }}"><b> More</b> </a></td>
          </tr>
        {% endif %}
        {% endfor %}
      </table> 

    END);

  },

]);
