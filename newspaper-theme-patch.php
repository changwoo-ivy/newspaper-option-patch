<?php
/**
 * Plugin Name: Newspaper Theme Patch
 * Description: 뉴스페이퍼 보안 이슈 대응 (2017. 08. 24.) 패치 플러그인입니다.
 * Author:      남창우
 * Version:     1.0.0
 * Author URI:  mailto://changwoo@ivynet.co.kr
 * Plugin URI:  https://github.com/changwoo-ivy/newspaper-theme-patch
 */

add_action( 'init', 'ntp_theme_patch' );

function ntp_theme_patch() {

	/** @var WP_Theme $my_theme */
	$my_theme = wp_get_theme();

	/** @var NULL|WP_Theme $parent */
	$parent = $my_theme->parent();

	if ( 'Newsmag' === $my_theme->get( 'Name' ) || ( $parent && 'Newsmag' === $parent->get( 'Name' ) ) ) {
		do_action( 'ntp_patches' );
	}
}

add_action( 'ntp_patches', 'ntp_fix_update_option' );

function ntp_fix_update_option() {
	// https://www.exploit-db.com/exploits/39894/
	add_filter( 'pre_update_option_td_010', 'ntp_option_requires_capabilities', 1 );
	add_filter( 'pre_update_option_default_role', 'ntp_option_requires_capabilities', 1 );
	add_filter( 'pre_update_option_users_can_register', 'ntp_option_requires_capabilities', 1 );
}


function ntp_option_requires_capabilities( $value ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You are not authorized.', 'Unauthorized' );
	}

	return $value;
}
