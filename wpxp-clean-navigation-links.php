<?php
/*
 * Plugin Name: WPXP Clean Navigation Links
 * Version: 1.0.0
 * Description: Cleans the HTML of navigation menu links. Each list item will now only use its corresponding page's slug for its "class" attribute. Only "current-menu-item" and "current-menu-parent" are used to help you highlight the current page's link or its parent in your navigation menu. Just install and activate. No additonal settings.
* Author: Alex Diokou
* Author URI: https://wpxpertise.com
* Plugin URI: https://wpxpertise.com/resources/plugins/wpxp-clean-navigation-links/
 * Text Domain: wpxp-clean-navigation-links
 * License: GPLv2 or later
 */
/*
Copyright (C) 2019 Alex Diokou, https://wpxpertise.com

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; If not, see <http://www.gnu.org/licenses/>
*/


class wpxpNavigationMenu {
  //Convert sting into slug where all special characters are removed
  //and empty spaces replaced by a hyphen
  private function getStringSlug( $Str ) {
    $Str = preg_replace('/[^a-zA-Z0-9\s]/', '', $Str);
    $Str = str_replace(' ', '-', $Str);
    $Str = strtolower($Str);
    return $Str;
  }
  
  /**
   * Prevent id attribute from being displayed on list items
   */
  public function removeElementID($id, $item){
    return '';
  }
  
  /**
   * Custom attributes needed for AJAX requests
   */
  public function addElementAttributes($atts, $item, $args){
    $post_ID= get_post_meta( $item->ID, '_menu_item_object_id', true );
    $atts['data-id']=$post_ID;
    $atts['data-slug']=get_post_field('post_name', $post_ID);

    return $atts;
  }
  

  /**
   * 
   */
  public function limitNavigationClasses($oldclasses, $item ){
    $post_ID= get_post_meta( $item->ID, '_menu_item_object_id', true );
    $page_slug=get_post_field('post_name', $post_ID);

    $newclasses=array();
    $newclasses[]=$page_slug;
    
    foreach($oldclasses as $class) {
      if ($class=='current-menu-item' || $class=='current-menu-parent') {
        $newclasses[] = $class;
      }
    }
    
    return $newclasses;
  }

}//end class

$wpxpnav=new wpxpNavigationMenu();
//
add_filter( 'nav_menu_link_attributes', array($wpxpnav, 'addElementAttributes'), 10, 3 );
//
add_filter( 'nav_menu_item_id', array($wpxpnav,'removeElementID'), 10, 2 );
//
add_filter( 'page_css_class', array($wpxpnav, 'limitNavigationClasses'), 10, 2 );
// Add filter for custom menus
add_filter('nav_menu_css_class', array($wpxpnav, 'limitNavigationClasses'), 10, 2 );
