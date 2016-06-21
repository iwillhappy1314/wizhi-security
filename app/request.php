<?php
//ajax init

require_once( WIZHI_SECURITY . 'vendor/autoload.php' );

use TheFold\WordPress\Dispatch;

// 社交登录请求
new Dispatch( [

	'oauth/request/([a-z]+)' => function ( $request, $provider ) {

		$socialite = get_oauth_services( $provider );

		$response = $socialite->driver( $provider )
		                      ->redirect();

		$response->send();

	},

] );


// 获取登录 Modal 模板
new Dispatch( [

	'security-([a-z]+)' => function ( $request, $action ) {

		ob_start();
		wizhi_get_template_part( 'security', 'login' );
		$var = ob_get_contents();
		ob_end_clean();

		print_r( $var );

		echo $var;
		echo $action;

		die();

	},

] );