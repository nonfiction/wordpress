<?php
namespace nf;

class MenuItem extends \Timber\MenuItem {

  private $_children = null;
  private $_current_item_parent = null;
  private $_current_item_ancestor = null;
  private $_post_type_children = null;

  // Return both menu item child and post type children merged
  public function children() {

    if ($this->_children===null) {

      // Standard menu item children and dynamic post type children
      $menu_item_children = parent::children() ?? [];
      $post_type_children = $this->post_type_children();

      // If there are post type children, merge these with the menu item children
      // and sort everything alphabetically
      if ( count($post_type_children) > 0 ) {
        $children = array_merge($post_type_children, $menu_item_children);
        usort($children, function($a, $b) {
          $a = strtolower( (is_array($a)) ? $a['title'] : $a->title );
          $b = strtolower( (is_array($b)) ? $b['title'] : $b->title );
          return ($a < $b) ? -1 : 1;
        });
        // return $children;
        $this->_children = $children;

      } else {
        // If there aren't post type children, just return menu item children alone
        // return $menu_item_children;
        $this->_children = $menu_item_children;
      }

    }
    return $this->_children;

  }


  public function post_type_children() {
    if ( $this->_post_type_children===null ) {
      $children = [];
      $obj = $this->master_object();
      if ( method_exists($obj, 'post_type_children') ) {
        $children = $obj->post_type_children() ?? [];
      }
      $this->_post_type_children = $children;
    }
    return $this->_post_type_children;
  }


  // On top of checking regular menu item parentage, also check if dynamic
  // post type children exist, as defined by the post type's 'post_type_children'
  // method (if it exists)
  public function current_item_parent() {

    if ( $this->_current_item_parent===null ) {

      // First check if this has menu item children
      if ( $this->current_item_parent ) {
        $this->_current_item_parent = true;

      // If not, check if this has post type children
      } else {
        foreach($this->post_type_children() as $child) {
          if ( $child['id'] == get_queried_object_id() ) {
            $this->_current_item_parent = true;
            break;
          }
        }
      }

      if ( $this->_current_item_parent===null ) {
        $this->_current_item_parent = false;
      }

    }
    return $this->_current_item_parent;
  }


  // On top of checking regular menu item ancestry, also check if dynamic
  // post type children exist, as defined by the post type's 'post_type_children'
  // method (if it exists). Only looks one level down.
  public function current_item_ancestor() {

    if ( $this->_current_item_ancestor===null ) {

      // First check if this has menu item children
      if ( $this->current_item_ancestor ) {
        $this->_current_item_ancestor = true;

      // If not, check if this menu item children with post type children
      } else {
        foreach( parent::children() as $child ) {
          foreach( $child->post_type_children() as $grandchild) {
            if ( $grandchild['id'] == get_queried_object_id() ) {
              $this->_current_item_ancestor = true;
              break 2;
            }
          }
        }
      }

      if ( $this->_current_item_ancestor===null ) {
        $this->_current_item_ancestor = false;
      }

    }
    return $this->_current_item_ancestor;
  }


  public function get_classes() {
    $classes = [ 'leaf' ];
    $classes[] = 'menu-' . $this->slug;
    if ( $this->current ) $classes[] = 'is-current';
    if ( $this->current_item_parent() ) $classes[] = 'is-parent';
    if ( $this->current_item_ancestor() ) $classes[] = 'is-ancestor';
    if ( count( array_intersect( ['is-current', 'is-parent', 'is-ancestor'], $classes ) ) > 0 ) {
      $classes[] = 'is-open';
    }
    return array_unique($classes);
  } 


  // static method to create an array resembling a MenuItem object, based on a post type
  public static function post_type_child( $post ) {

    $classes = [ 'leaf' ];
    $classes[] = 'post-' . $post->type;
    $classes[] = 'menu-' . $post->slug;

    if ( $post->id == get_queried_object_id() ) {
      $classes[] = 'is-current';
      $classes[] = 'is-open';
    }

    return [
      'id' => $post->id,
      'title' => $post->title(),
      'link' => $post->link(),
      'path' => $post->path(),
      'classes' => $classes,
      'class' => implode( ' ', $classes ),
      'object' => $post,
      'children' => [],
    ];

  }

  // Alias to children method
  public function get_items() {
    return $this->children();
  }

  // Alias to children method
  public function items() {
    return $this->children();
  }

  public function foo() {
    return "foobar!";
  }

}
