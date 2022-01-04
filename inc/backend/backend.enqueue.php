<?php

/*
 * Note: admin_enqueue_scripts
 *
 * admin_enqueue_scripts is the first action hooked into the admin scripts actions.
 * It provides a single parameter, the $hook_suffix for the current admin page.
 * Despite the name, it is used for enqueuing both scripts and styles.
 */


function register_admin_style() {
  wp_enqueue_style('admin-styles', get_path('css').'/admin.min.css');
  wp_enqueue_style('admin-font', 'https://fonts.googleapis.com/css?family=Dancing+Script|Encode+Sans:400,700');

}
add_action('admin_enqueue_scripts' ,'register_admin_style');