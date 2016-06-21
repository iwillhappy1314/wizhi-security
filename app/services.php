<?php

require_once( WIZHI_SECURITY . 'vendor/autoload.php' );

use Overtrue\Socialite\SocialiteManager;


function get_oauth_services( $provider ) {

	$keys = get_option( 'wizhi_security_oauth' );

	$config = [
		'weibo' => [
			'client_id'     => $keys[ 'weibo_api' ],
			'client_secret' => $keys[ 'weibo_key' ],
			'redirect'      => home_url( 'oauth/access/' . $provider ),
		],
		'qq'    => [
			'client_id'     => $keys[ 'qq_api' ],
			'client_secret' => $keys[ 'qq_key' ],
			'redirect'      => home_url( 'oauth/access/' . $provider ),
		],
		'wechat'    => [
			'client_id'     => $keys[ 'wechat_api' ],
			'client_secret' => $keys[ 'wechat_key' ],
			'redirect'      => home_url( 'oauth/access/' . $provider ),
		],
	];

	$socialite = new SocialiteManager( $config );

	return $socialite;

}