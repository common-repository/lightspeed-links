<?php
/*
Plugin Name: Lightspeed Links
Plugin URI: http://www.becomeyourfursona.com/2010/06/lightspeed-links/
Description: Lets you link to any post or page by putting [[brackets]] around its name! Based on code from WP-Wiki.
Version: 0.2
Author: Tachyon Feathertail
Author URI: http://feathertail.dreamwidth.org
*/

/*  Copyright 2010  Jared Spurbeck (email: jspurbeck@gmail.com)

    Based on WP-Wiki code, copyrighted by Instinct Entertainment.
    See http://wp-wiki.org/?page_id=10#License%20Information for
    WP-Wiki's GPL notice.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/



/**
 * Build links from shortcodes
 * @param <type> $content
 * @return <type> modified content
 */
function lightspeed_links($content) {
    global $post;

    $pattern = '/(\[\[([^\]]*)\]\])/i';
    return preg_replace_callback($pattern, "ll_callback_func", $content);
}

/**
 * Call back function for regex
 * @param <type> $m
 * @return <type> link
 */
function ll_callback_func($m) {
    global $post;

    $splited = explode("|", $m[2]);
    if (count($splited) == 2) {
        $link_text = trim($splited[1]);
        $link_slug = trim($splited[0]);
    } else {
        $link_slug = $link_text = $m[2];
    }

    $link = get_permalink_by_title($link_slug);
    if (!$link) {
        // If there is no post with that title

        if ($post->post_type == "page") {
            $link = get_bloginfo("wpurl") . "/wp-admin/page-new.php" ;
        } else {
            $link = get_bloginfo("wpurl") . "/wp-admin/post-new.php" ;
        }
    }
    return "<a href = '" . $link . "' >" . $link_text . "</a>";
}

/**
 * Get Permalink by post title
 * @global <type> $wpdb
 * @param <type> $page_title
 * @return <type> permalink
 */
function get_permalink_by_title($page_title) {
      global $wpdb;
      $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type in ('post','page')", $page_title ));
      if ( $post )
          return get_permalink($post);

      return NULL;
}

add_filter("the_content", "lightspeed_links", 999);
?>
