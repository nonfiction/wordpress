<?php
namespace nf;
use \Timber;

class Product extends PostType {

    public function province() {
        $slugs = wp_list_pluck( $this->terms("province"), 'slug' );
        return 'data-province="' . implode( " ", $slugs ) .'"';
    }

    public function brand() {
        $slugs = wp_list_pluck( $this->terms("brand"), 'slug' );
        return 'data-brand="' . implode( " ", $slugs ) .'"';
    }

    protected static function before_register_post_type() {}
}


add_action( 'init', function() {

    Product::register_post_type([

        "menu_icon" => "dashicons-products",
        "unsupports" => [ "page-attributes" ],
        "archive" => false,

        "taxonomies" => [
            "category" => true,            
            "amenity"  => [ "meta_box" => "simple" ],
        ],

        "admin_cols" => [
            "title" => [ "title" => "Title" ],
            "inventory" => [ "title" => "Inventory", "meta_key" => "inventory"],
            "category" => [ "title" => "Category", "taxonomy" => "category"],
            "date" => [ "title" => "Date", "default" => "DESC" ],
        ],
  
        "metaboxes" => [[
            "title" => "Properties",
            "context" => "side",
            "fields" => [[
                "id"           => "inventory",
                "name"         => "Inventory",
                "type"         => "text_small",
                "default"      => "0"
            ]]
        ]],
    ]);



}, 10);
