<?php 

// Remove WP Emoji JS from <head> ----------------------------------------------
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Remove link to wlwmanifest from <head> --------------------------------------
remove_action( 'wp_head', 'wlwmanifest_link');


// Remove menu-items in WP-admin ------------------------------------
function etypes_remove_admin_page() {
  // remove_menu_page( 'edit.php' );           // Posts
  remove_menu_page('edit-comments.php');    // Comments
  // remove_menu_page('link-manager.php');  // Links
  // remove_menu_page('tools.php');         // Tools
  // remove_menu_page('users.php');         // Users

	// get the the role object
	$role_object = get_role( 'editor' );
  
	// editor role access plugins + appearance
  if (is_object($role_object)) {
      $role_object->add_cap( 'edit_theme_options' );
      $role_object->add_cap( 'manage_options' );
      $role_object->add_cap('edit_users');
      $role_object->add_cap('list_users');
      $role_object->add_cap('promote_users');
      $role_object->add_cap('create_users');
      $role_object->add_cap('add_users');
      $role_object->add_cap('delete_users');
  }

	// get the the role object
  
        $role_object = get_role( 'author' );
        if (is_object($role_object)) {
        $role_object->add_cap( 'edit_pages' );
        $role_object->add_cap( 'edit_published_pages' );
        $role_object->add_cap( 'edit_posts' );
        $role_object->add_cap( 'edit_others_posts' );
        $role_object->add_cap( 'publish_posts' );
        $role_object->add_cap( 'publish_pages' );
        $role_object->add_cap( 'edit_published_posts' );
        $role_object->add_cap( 'edit_published_pages' );
        $role_object->add_cap( 'edit_others_pages' );
        }


}
add_action( 'admin_menu', 'etypes_remove_admin_page' );


// Change position of Apperence -> Menus ---------------------------------------
// Move it to main menu & change position
function etypes_change_menus_position() {
  global $submenu;
  // Remove old menu
  remove_submenu_page( 'themes.php', 'nav-menus.php' );
  remove_submenu_page( 'edit.php?post_type=news', 'edit-tags.php?taxonomy=tag&amp;post_type=news' );
  remove_submenu_page( 'edit.php?post_type=gallery', 'edit-tags.php?taxonomy=tag&amp;post_type=gallery' );
  remove_submenu_page( 'edit.php?post_type=interview', 'edit-tags.php?taxonomy=tag&amp;post_type=interview' );
  remove_submenu_page( 'edit.php?post_type=review', 'edit-tags.php?taxonomy=tag&amp;post_type=review' );
  remove_submenu_page( 'edit.php?post_type=video', 'edit-tags.php?taxonomy=tag&amp;post_type=video' );
  remove_submenu_page( 'edit.php?post_type=podcast', 'edit-tags.php?taxonomy=tag&amp;post_type=podcast' );

  //Add new menu page
  add_menu_page(
    'Menus',
    'Menus',
    'edit_theme_options',
    'nav-menus.php',
    '',
    'dashicons-list-view',
    50
  );
  add_menu_page(
    'Tags',
    'Tags',
    'edit_theme_options',
    'edit-tags.php?taxonomy=tag',
    '',
    'dashicons-list-view',
    56
  );
  
}
add_action('admin_menu', 'etypes_change_menus_position');

// Set correct classes for 'Apperence' menu ------------------------------------
function etypes_set_menu_classes() {
  // If 'Menus' is selected then remove classes from 'Apperence' menu
  echo "
    <script type='text/javascript'>
      jQuery(document).ready( function($) {
        if ( $('#toplevel_page_nav-menus').hasClass('current') ) {
          if ( $('#menu-appearance').hasClass('wp-has-current-submenu') ) {
            $('#menu-appearance').removeClass('wp-has-current-submenu').addClass('wp-not-current-submenu');
            $('#menu-appearance > a').removeClass('wp-has-current-submenu').addClass('wp-not-current-submenu');
          }
        };
      });     
    </script>
  ";
};
add_action( "admin_footer", 'etypes_set_menu_classes' );


function custom_remove() {
	remove_meta_box('add-post-type-post', 'nav-menus', 'side');
	remove_meta_box('add-post-type-employee', 'nav-menus', 'side');
	remove_meta_box('add-article_category', 'nav-menus', 'side');
	remove_meta_box('add-inspiration_category', 'nav-menus', 'side');
	remove_meta_box('add-inspiration_category', 'nav-menus', 'side');
	remove_meta_box('add-category', 'nav-menus', 'side');
}
add_action('admin_head-nav-menus.php', 'custom_remove');

function gutenberg_width_block_setup() {
//    add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'gutenberg_width_block_setup' );

add_action('admin_head', 'editor_full_width_gutenberg');

function editor_full_width_gutenberg() {
    echo '<style>
    body.gutenberg-editor-page .editor-post-title__block, body.gutenberg-editor-page .editor-default-block-appender, body.gutenberg-editor-page .editor-block-list__block {
		max-width: 1200px;
	}
    .block-editor__container .wp-block {
        max-width: 1200px;
    }
  </style>';
}



add_action('admin_head', 'remove_button_add_category'); // admin_head is a hook my_custom_fonts is a function we are adding it to the hook

function remove_button_add_category() {
  echo '<style>
    .editor-post-taxonomies__hierarchical-terms-add{
        display: none;
    }
  </style>';
}
