<?php
namespace nf;
use \Timber;

class Hotel extends PostType {

  public function province() {
    $slugs = wp_list_pluck( $this->terms("province"), 'slug' );
    return 'data-province="' . implode( " ", $slugs ) .'"';
  }

  public function brand() {
    $slugs = wp_list_pluck( $this->terms("brand"), 'slug' );
    return 'data-brand="' . implode( " ", $slugs ) .'"';
  }

  public function is_external() {
    return ( $this->meta("external_url") ) ? "is-external" : "";
  }

  public function correct_link() {
    return ( $this->meta("external_url") ) ? $this->meta("external_url") : $this->link();
  }

  public function gallery_images() {

    $images = [];

    if ( $this->meta("gallery") ) {
      foreach( $this->meta("gallery") as $id => $url ){
        $image = new \Timber\Image($id);
        $images[$url] = $image->caption;
      }
    
    } else {
      $images = [ $this->thumbnail()->src() => '' ];
    }

    return $images;

  }

  protected static function before_register_post_type() {
    // echo "<pre>";
    // var_dump(static::$args);
    // echo "</pre>";
  }

  protected static function after_register_post_type() {
    // echo "<pre>";
    // var_dump(static::$args);
    // echo "</pre>";
  }

}


// Register Custom Post Type
add_action( 'init', function() {

  Hotel::register_post_type([

    "menu_icon" => "dashicons-awards",

    // Enable archive pages
    // "has_archive" => true,
    //
    // "archive" => [
    //   "orderby" => "date",
    //   "order" => "DESC",
    //   "nopaging" => true,
    //   "posts_per_page" => 10,
    // ],

    // "unsupports" => [ "page-attributes" ],

    "taxonomies" => [
      "brand"    => [ "meta_box" => "simple", "exclusive" => true ],
      "province" => [ "meta_box" => "simple", "exclusive" => true ],
      "amenity"  => [ "meta_box" => "simple" ],
    ],

    "admin_cols" => [
      "title" => [ "title" => "Title" ],
      "brand" => [ "title" => "Brand", "taxonomy" => "brand"],
      "province" => [ "title" => "Province", "taxonomy" => "province" ],
      "date" => [ "title" => "Date", "default" => "DESC" ],
    ],

    "template" => [
      [ "nf/hotel-gallery", [], [] ],
      [ "nf/booking-bar", [], [] ],
      [ "nf/hotel-details", [], [] ],
      [ "core/paragraph", [[ "placeholder" => "Add more details" ]], [] ],
      [ "nf/hotel-amenities", [], [] ],
      [ "nf/hotel-map", [], [] ],
      [ "nf/hotel-meetings", [], [] ],
    ],

    // "template_lock" => "all",

    "metaboxes" => [[
      "title" => "Hotel Properties",
      "context" => "side",
      "fields" => [[
        "id"           => "property_id",
        "name"         => "Property ID",
        "type"         => "text",
        "desc"         => "HotelKey/SynXis"
      ],[
        "id"           => "property_code",
        "name"         => "Property Code",
        "type"         => "text",
        "desc"         => "HotelKey"
      ],[
        "id"           => "external_url",
        "name"         => "External URL",
        "type"         => "text_url",
        "desc"         => ""
      ],[
        "id"           => "icon",
        "name"         => "Icon Replacement",
        "desc"         => "",
        "type"         => "file",
        "preview_size" => [50,50]
      ],[
        "id"           => "gallery",
        "name"         => "Image Gallery",
        "desc"         => "",
        "type"         => "file_list",
        "preview_size" => [50,50]
      ]]
    ]],
  
  ]);


  // Register block pattern
  register_block_pattern( 'nf/hotel', [

    'title'       => 'Hotel',
    'categories' => ['text'],
    'keywords' => ['nf'],
    'content'     => (<<<END

        <!-- wp:nf/hotel-gallery /-->

        <!-- wp:nf/booking-bar {"className":"is-style-hotel"} /-->

        <!-- wp:nf/hotel-details -->
        <!-- wp:heading -->
        <h2>Heading goes here.</h2>
        <!-- /wp:heading -->

        <!-- wp:paragraph -->
        <p>Paragraph goes here.</p>
        <!-- /wp:paragraph -->
        <!-- /wp:nf/hotel-details -->

        <!-- wp:nf/hotel-amenities /-->

        <!-- wp:nf/hotel-map /-->

        <!-- wp:nf/hotel-reviews /-->

        <!-- wp:nf/hotel-meetings -->
        <!-- wp:image -->
        <figure class="wp-block-image"><img alt=""/></figure>
        <!-- /wp:image -->

        <!-- wp:heading {"level":4} -->
        <h4>Heading</h4>
        <!-- /wp:heading -->

        <!-- wp:paragraph -->
        <p>Paragraph</p>
        <!-- /wp:paragraph -->
        <!-- /wp:nf/hotel-meetings -->

    END),

  ] );


}, 18);
