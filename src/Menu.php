<?php
namespace nf;

class Menu extends \Timber\Menu {

  private static $add_filters = false;
  private static $cache = [];
  
  // Add timber filters for menu and menuitems (only once)
  public static function init_filters() {

    if ( ! static::$add_filters ) {

      add_filter( 'timber/menu/class', function( $class, $term, $args ) {
        return \nf\Menu::class;
      }, 10, 3);

      add_filter( 'timber/menuitem/class', function( $class, $term, $args ) {
        return \nf\MenuItem::class;
      }, 10, 3);

    }
    static::$add_filters = true;

  }

  // Retrieve a menu by slug, optionally rooted to a menu item slug
  public static function get_menu( $menu_slug, $menu_item_slug = false ) {

    static::init_filters();

    $cache_key = $menu_slug . (($menu_item_slug) ? $menu_item_slug : '');
    if ( ! array_key_exists($cache_key, static::$cache) ) {

      // Retreive the menu and set the items
      $menu = \Timber::get_menu( $menu_slug );
      $menu->items = $menu->get_items();

      // If provided a menu item slug, return this menu item as 
      // if it were the top-level menu
      if ( $menu_item_slug ) {
        foreach( $menu->items as $menu_item ) {
          if ( $menu_item_slug === $menu_item->slug() ) {
            $menu = $menu_item;
            break;
          }
        }
      }

      static::$cache[$cache_key] = $menu;

    }

    return static::$cache[$cache_key];

  }


  // Alias to get_items method
  public function items() {
    return $this->get_items();
  }

  // Return normal get_items, after cleaning up the classnames
  public function get_items() {
    return static::clean_items( parent::get_items() );
  }

  // Recursive function for tweaking menu classnames
  private static function clean_items( $items ) {
    foreach( $items as $item ) {
      $item->classes = $item->get_classes();
      $item->class = implode( ' ', $item->classes );
      if ( $item->children ) {
        static::clean_items( $item->children );
      }
    }
    return $items;
  }

}
