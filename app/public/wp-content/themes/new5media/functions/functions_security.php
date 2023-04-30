<?php



function disable_rest_endpoints ( $endpoints ) {
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
}
add_filter( 'rest_endpoints', 'disable_rest_endpoints');


function redirect_to_home_if_author_parameter() {

	$is_author_set = get_query_var( 'author', '' );
	if ( $is_author_set != '' && !is_admin()) {
		wp_redirect( home_url(), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'redirect_to_home_if_author_parameter' );



if (!current_user_can('activate_plugins')) {
    function remove_core_updates() {
        global $wp_version; return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
    }
    add_filter('pre_site_transient_update_core','remove_core_updates');
    add_filter('pre_site_transient_update_plugins','remove_core_updates');
    add_filter('pre_site_transient_update_themes','remove_core_updates');
}

