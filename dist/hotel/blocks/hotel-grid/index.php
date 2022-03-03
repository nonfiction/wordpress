<?php
use nf\BlockType;
use function nf\import;
use function nf\merge;
use function nf\css;

BlockType::register_block_type( __DIR__ . '/block.json', [

  // Server-side block rendering
  
  'render' => function( &$context ) { 
    // Get hotel posts
    $args = array(
      'post_type' => 'hotel',
      'posts_per_page' => -1,
      'post_status' => 'publish'
    );
    $hotel_post = query_posts($args);

    // Get the brands
    $brand_args = array (
      'taxonomy' => 'brand',
      'hide_empty'      => false,
      );
    $hotel_brands = get_categories( $brand_args );
    
    // Get the provinces
    $province_args = array (
      'taxonomy' => 'province',
      'hide_empty'      => false,
      );
    $hotel_provinces = get_categories($province_args);
    
    // Pass to context for twig 
    $context['categoryBrands'] = $hotel_brands;
    $context['categoryProvinces'] = $hotel_provinces;
    $context['totalNumber'] = count($hotel_post);

    return (<<<END

        <div class="nf-hotel-grid-wrap {{ className }}"> 
          <div class="nf-hotel-grid-filter">
            <div class="nf-filter-option-group">
              <h3 class="nf-filter-head-title">Filter by Province</h3>
              <div class="nf-filter-province">
                <label>
                  <input type="radio" value="" checked="true" name="nf-province-radio" data-name="all provinces">
                  <span>All</span>
                </label>
                {% for province in categoryProvinces %}
                <label>
                  <input type="radio" value="{{ province.slug }}" name="nf-province-radio" data-name="{{ province.name }}">
                  <span>{{ province.name }}</span>
                </label>
                {% endfor %}
              </div>
            </div>
            <div class="nf-filter-option-group">
              <h3 class="nf-filter-head-title">Filter by Brand</h3>
              <div class="nf-filter-brand">
                <label>
                  <input type="radio" value="" name="nf-brand-radio" checked="true">
                  <span>All</span>
                </label>
                {% for brand in categoryBrands %}
                  <label>
                  <input type="radio" value="{{ brand.slug }}" name="nf-brand-radio">
                  <span>{{ brand.name }}</span>
                  </label>
                {% endfor %}
              </div>
            </div>
          </div>

          <div class="nf-hotel-info">
            <div>
              <p><b><span class="nf-hotel-number">{{ totalNumber }}</span></b> hotels in <span class="nf-hotel-province">all provinces</span></p>
            </div>
            <div class="view-btn">
              <span id="nf-grid-view"><img src="{{ img }}/grid.png"><p>Grid View</p></span>
              <span id="nf-map-view"><img src="{{ img }}/map-pin.png"><p>Map View</p></span>
            </div>


          </div>

          <div class="nf-hotel-grid">
            {% for hotel in PostQuery({ post_type: 'hotel', orderby: 'title', order: 'ASC', posts_per_page: -1, }) %}
            <figure class="nf-hotel {{ hotel.is_external }} {{ hotel.province|replace({'data-province=':'', '"':''}) }} {{ hotel.brand|replace({'data-brand=':'', '"':''}) }}" {{ hotel.province }} {{ hotel.brand }} data-latitude="{{ hotel.latitude }}" data-longitude="{{ hotel.longitude }}">
              <a href="{{ hotel.correct_link }}">
                <img src="{{ hotel.thumbnail.src }}" />
                <figcaption>{{ hotel.title }}</figcaption>
              </a>
            </figure>
            {% endfor %}
          </div>

          <div class="hotel-map-view">
            <div class="map-wrap" id="listingsMap"></div>
          </div>

        </div>

        <script>
        var listing_icon_path = '/map/';
        function __googleMapsApiOnLoadCallback() {
          window.canaltaMap.init('listingsMap', '', listing_icon_path);
        }
        </script>
                
    END);

  },

]);
