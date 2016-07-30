<?php

/**
 * 显示注册登录链接
 */
function wizhi_security_links() {

	$settings = get_option( 'wizhi_security_basics' );

	if ( is_user_logged_in() ) {

		echo '<a href="' . wp_logout_url( get_permalink() ) . '" title="Logout">注销</a>';
		echo '<a href="' . $settings[ 'ucenter_link' ] . '" title="Logout">用户中心</a>';

	} else {

		echo '<a href="#modal-register" rel="modal:open">注册</a>';
		echo '<a href="#modal-login" rel="modal:open">登录</a>';

		security_get_template_part( 'security', 'login' );
		security_get_template_part( 'security', 'register' );
		security_get_template_part( 'security', 'reset' );
	}

}


/**
 * 显示注册登录表单
 */
function wizhi_security_forms() {

	echo '<div class="security-forms">';

	security_get_template_part( 'security', 'login' );
	security_get_template_part( 'security', 'register' );
	security_get_template_part( 'security', 'reset' );

	echo '</div>';

	echo '<script>jQuery("#modal-login").show();</script>';

}

if ( ! function_exists( 'wizhi_shortcode_security_forms' ) ) {
	/**
	 * 显示几种不同类型的分割线
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [security_forms action="login"]
	 *
	 * @return string 经简码格式化后的 HTML 字符串
	 */
	function wizhi_shortcode_security_forms( $atts ) {
		$default = [
			'action' => 'login',
		];
		extract( shortcode_atts( $default, $atts ) );

		echo '<div class="security-forms">';
		echo wizhi_load_template_part( 'security', $action );
		echo '</div>';
		echo '<script>jQuery("#modal-login").show();</script>';

	}
}
add_shortcode( 'security_forms', 'wizhi_shortcode_security_forms' );


/**
 * 显示社交平台注册登录按钮
 */
function wizhi_oauth_buttons() {

	if ( ! is_user_logged_in() ) {

		echo '<span class="security-oauth_buttons">';
		echo '<a href="' . home_url( 'oauth/request/qq' ) . '">使用QQ登录</a>';
		echo '<a href="' . home_url( 'oauth/request/weibo' ) . '">使用微博登录</a>';
		echo '<a href="' . home_url( 'oauth/request/wechat' ) . '">使用微信登录</a>';
		echo '</span>';

	}

}