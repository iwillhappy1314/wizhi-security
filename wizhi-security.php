<?php
/*
Plugin Name:        Wizhi Security
Plugin URI:         http://www.wpzhiku.com/wordpress-cms-plugin-wizhi-cms/
Description:        添加一些实用的功能，增加了一些效果类似dedecms(织梦)模板标签的一些简码。
Version:            1.0
Author:             Amos Lee
Author URI:         http://www.wpzhiku.com/
License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

define( 'WIZHI_SECURITY', plugin_dir_path( __FILE__ ) );

// 加载辅助功能
require_once( WIZHI_SECURITY . 'inc/template-loader.php' );
require_once( WIZHI_SECURITY . 'inc/settings-api.php' );
require_once( WIZHI_SECURITY . 'inc/avatar.php' );

// 加载应用组件, request 为功能请求, access 为执行操作后的响应
require_once( WIZHI_SECURITY . 'app/request.php' );
require_once( WIZHI_SECURITY . 'app/access.php' );
require_once( WIZHI_SECURITY . 'app/captcha.php' );
require_once( WIZHI_SECURITY . 'app/services.php' );
require_once( WIZHI_SECURITY . 'app/template-tags.php' );


// 加载并初始化插件设置
require_once( WIZHI_SECURITY . 'settings.php' );
new Wizhi_Security_Setting();

// 获取插件设置值
// $wizhi_use_cms_front = get_option( 'wizhi_use_cms_front' );
$wizhi_use_cms_front = true;

//加载 CSS 和 JS
if ( $wizhi_use_cms_front ) {
	add_action( 'wp_enqueue_scripts', 'wizhi_security_scripts' );
	add_action( 'wp_enqueue_scripts', 'wizhi_security_style' );
}

/**
 * 加载CSS
 *
 * @package front
 */
function wizhi_security_style() {
	wp_register_style( 'wizhi-security-style', plugins_url( '/assets/styles/main.css', __FILE__ ) );
	wp_enqueue_style( 'wizhi-security-style' );
}

/**
 * 加载 JavaScript
 *
 * @package front
 */
function wizhi_security_scripts() {
	wp_register_script( 'wizhi-security-script', plugins_url( '/assets/scripts/main.js', __FILE__ ), [ 'jquery' ], '1.1', true );
	wp_enqueue_script( 'wizhi-security-script' );
}

