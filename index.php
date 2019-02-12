<?php
/**
 * Plugin Name: Notifier
 * Description: Notifier segnala a Fermibot quando è stato pubblicato un nuovo post.
 * Version: 0.0.1
 * Author: Riccardo Sangiorgio
 * Author URI: https://riccardosangiorgio.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.html
 *
 * Notifier is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.

 * Notifier is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with Notifier. If not, see https://www.gnu.org/licenses/gpl.html.
 */

include(plugin_dir_path( __FILE__ ) . 'options.php');

add_action('save_post', function ($post_id, $post, $update) {

    if ( wp_is_post_autosave( $post_id ) ) {
        return $post_id;
    }
   
    if ( wp_is_post_revision( $post_id ) ) {
        return $post_id;
    }

    if ($post->post_modified != $post->post_date) {
        return $post_id;
    }

    if ($post->post_status != "publish" || $post->post_type != "post") {
        return $post_id;
    }

    // Impostazioni del plugin
    $options = get_option('my_option_name');

    if(!isset($options['url'])) return;

    $args = array(
        'body' => json_encode(array('id' => $post_id, 'post' => $post, 'update' => $update)),
        'httpversion' => '1.1',
        'data_format' => 'body',
        'headers'     => array(
            'Content-Type' => 'application/json; charset=utf-8',
            'Authorization' => 'Bearer ' . $options['token']),
        'cookies' => array()
    );

    return wp_remote_post($options['url'], $args);
}, 10, 3);