<?php
//ajax init

require_once( WIZHI_SECURITY . 'vendor/autoload.php' );

use Gregwar\Captcha\CaptchaBuilder;
use TheFold\WordPress\Dispatch;

// 生成验证码并写入 PHP 会话
new Dispatch( [

	'captcha/([a-z0-9]+)' => function ( $request, $type ) {

		header( 'Content-type: image/jpeg' );

		$builder = new CaptchaBuilder;

		update_option( $type . '_captcha', $builder->getPhrase());

		$builder->build()
		        ->output();

	},

] );