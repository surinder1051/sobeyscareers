<?php
/*
Plugin Name: Sobeys Custom User Role
Plugin URI: http://www.flowpress.com/
Description: Creates a new Sobeys Staff user
Version: 1.0
Author: FlowPress Inc.
Author URI: http://www.flowpress.com/
*/

add_role(
    'sobeys_staff', //  System name of the role.
    __( 'Sobeys Staff'  ), // Display name of the role.
    array(
        'activate_plugins' => false,
        'delete_others_pages' => true,
        'delete_others_posts' => true,
        'delete_pages' => true,
        'delete_posts' => true,
        'delete_private_pages' => true,
        'delete_private_posts' => true,
        'delete_published_pages' => true,
        'delete_published_posts' => true,
        'edit_dashboard' => true,
        'edit_others_pages' => true,
        'edit_others_posts' => true,
        'edit_pages' => true,
        'edit_posts' => true,
        'edit_private_pages' => true,
        'edit_private_posts' => true,
        'edit_published_pages' => true,
        'edit_published_posts' => true,
        'edit_theme_options' => true,
        'export' => true,
        'import' => true,
        'list_users' => true,
        'manage_categories' => true,
        'manage_links' => true,
        'manage_options' => true,
        'moderate_comments' => true,
        'promote_users' => false,
        'publish_pages' => true,
        'publish_posts' => true,
        'read_private_pages' => true,
        'read_private_posts' => true,
        'read' => true,
        'remove_users' => false,
        'switch_themes' => false,
        'upload_files' => true,
        'customize' => true,
        'delete_site' => false,
    )
);