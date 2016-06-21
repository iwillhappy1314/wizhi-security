<?php

require_once( WIZHI_SECURITY . 'vendor/autoload.php' );

use TheFold\WordPress\Dispatch;


/**
 *  登录成功后跳转
 */
function wizhi_security_oauth_redirect() {
	echo '<script>
			if( window.opener ) {
				window.opener.location.reload();
                window.close();
            }else{
                window.location.href = "' . home_url() . '";
            }
         </script>';
}


// 社交响应
new Dispatch( [

	'oauth/access/([a-z]+)' => function ( $request, $provider ) {

		$socialite = get_oauth_services();

		$user = $socialite->driver( $provider )
		                  ->user();

		$oauth_uid    = $user->getId();
		$oauth_nic    = $user->getNickname();
		$oauth_avatar = $user->getAvatar();

		$uid_key = 'oauth_' . $provider . '_uid';

		if ( is_user_logged_in() ) {

			// 如果用户已经登录, 绑定用户, 可以绑定多个平台
			$current_user = wp_get_current_user();

			update_user_meta( $current_user->ID, $uid_key, $oauth_uid );
			update_user_meta( $current_user->ID, $provider . "_avatar", $oauth_avatar );

			wizhi_security_oauth_redirect();

		} else {

			// 根据授权 ID 登录用户
			$oauth_user = get_users( [ "meta_key " => $uid_key, "meta_value" => $oauth_uid ] );

			// 如果登录失败, 说明该用户没有注册, 注册并绑定
			if ( is_wp_error( $oauth_user ) || ! count( $oauth_user ) ) {

				// 创建并登录用户
				$username        = $oauth_nic;
				$login_name      = wp_create_nonce( $oauth_uid );
				$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );

				$userdata = [
					'user_login'   => $login_name,
					'display_name' => $username,
					'user_pass'    => $random_password,
					'nick_name'    => $username,
				];

				$user_id = wp_insert_user( $userdata );
				wp_signon( [ "user_login" => $login_name, "user_password" => $random_password ], false );

				// 更新用户信息
				update_user_meta( $user_id, $uid_key, $oauth_uid );
				update_user_meta( $user_id, $provider . "_avatar", $oauth_avatar );

				wizhi_security_oauth_redirect();

				// 如果登录成功, 设置登录 cookie
			} else {

				wp_set_auth_cookie( $oauth_user[ 0 ]->ID );

				wizhi_security_oauth_redirect();
			}
		}

	},

] );


// 登录
new Dispatch( [

	'login' => function ( $request ) {

		check_ajax_referer( 'ajax-login-nonce', 'security-login' );

		$credentials                    = [ ];
		$credentials[ 'user_login' ]    = $_POST[ 'username' ];
		$credentials[ 'user_password' ] = $_POST[ 'password' ];
		$rememberme                     = $_POST[ 'rememberme' ];

		if ( $rememberme == "forever" ) {
			$credentials[ 'remember' ] = true;
		} else {
			$credentials[ 'remember' ] = false;
		}

		if ( $credentials[ 'user_login' ] == null || $credentials[ 'user_password' ] == null ) {

			$msg = [
				'loggedin' => false,
				'message'  => __( '<p class="alert alert-danger" data-alert="alert">请填写所有字段</p>', ' wizhi' ),
			];

		} else {

			if ( $credentials[ 'user_login' ] != null && $credentials[ 'user_password' ] != null ) {
				$errors = wp_signon( $credentials, false );
			}

			if ( is_wp_error( $errors ) ) {

				$display_errors = __( '<p class="alert alert-danger" data-alert="alert"><strong>错误</strong>: 用户名或密码错误!</p>', ' wizhi' );
				$msg            = [
					'loggedin' => false,
					'message'  => $display_errors,
				];

			} else {

				$msg = [
					'loggedin' => true,
					'message'  => __( '<p class="alert alert-success" data-alert="alert">登录成功, 正在跳转...</p>', ' wizhi' ),
				];

			}

		}

		wp_send_json( $msg );

	},

] );


// 验证用户名是否为唯一
new Dispatch( [

	'validate_uid' => function ( $request ) {

		check_ajax_referer( 'ajax-form-nonce', 'security-register' );

		$user_login = $_POST[ 'user_login' ];

		if ( ! $user_login ) {
			$msg = [
				'success' => false,
				'message' => __( '请输入用户名!', ' wizhi' ),
			];
		} else {
			if ( username_exists( $user_login ) ) {
				$msg = [
					'success' => false,
					'message' => __( '用户名已存在, 请更换!', ' wizhi' ),
				];
			} else {
				$msg = [
					'success' => true,
					'message' => __( '用户名可以注册!', ' wizhi' ),
				];
			}
		}

		wp_send_json( $msg );

	},

] );


// 验证电子邮件是否为唯一
new Dispatch( [

	'validate_email' => function ( $request ) {

		check_ajax_referer( 'ajax-form-nonce', 'security-register' );

		$user_email = trim( $_POST[ 'user_email' ] );

		if ( ! is_email( $user_email ) ) {
			$msg = [
				'success' => false,
				'message' => __( '请输入正确的电子邮件地址!', ' wizhi' ),
			];
		} else {
			if ( email_exists( $user_email ) ) {
				$msg = [
					'success' => false,
					'message' => __( '电子邮件已存在, 请更换!', ' wizhi' ),
				];
			} else {
				$msg = [
					'success' => true,
					'message' => __( '电子邮件可以使用!', ' wizhi' ),
				];
			}
		}

		wp_send_json( $msg );

	},

] );


// 验证密码是否符合要求
new Dispatch( [

	'validate_pass' => function ( $request ) {

		check_ajax_referer( 'ajax-form-nonce', 'security-register' );

		$password = trim( $_POST[ 'password' ] );

		if ( strlen( $password ) < 6 ) {
			$msg = [
				'success' => false,
				'message' => __( '密码至少为6位数!', ' wizhi' ),
			];
		} else {
			if ( preg_match( "/^(w){4,20}$/", $password ) ) {
				$msg = [
					'success' => false,
					'message' => __( '密码设置正确!', ' wizhi' ),
				];
			} else {
				$msg = [
					'success' => false,
					'message' => __( '密码必须为字母和数字的组合!', ' wizhi' ),
				];
			}
		}

		wp_send_json( $msg );

	},

] );


// 注册
new Dispatch( [

	'register' => function ( $request ) {

		check_ajax_referer( 'ajax-form-nonce', 'security-register' );

		$user_login      = $_POST[ 'user_login' ];
		$user_email      = $_POST[ 'user_email' ];
		$password        = $_POST[ 'password' ];
		$re_password     = $_POST[ 're_password' ];
		$account_captcha = $_POST[ 'captcha' ];

		if ( $user_login == null || $user_email == null || $password == null || $re_password == null ) {

			$msg = [
				'registered' => false,
				'message'    => __( '<p class="alert alert-danger" data-alert="alert">请填写所有字段!</p>', ' wizhi' ),
			];

			wp_send_json( $msg );

		} elseif ( $password != $re_password ) {

			$msg = [
				'registered' => false,
				'message'    => __( '<p class="alert alert-danger" data-alert="alert">两次输入的密码不一致, 请核对!</p>', ' wizhi' ),
			];

			wp_send_json( $msg );

		} elseif ( strlen( $password ) < 6 ) {

			$msg = [
				'registered' => false,
				'message'    => __( '<p class="alert alert-danger" data-alert="alert">密码至少为6位!</p>', ' wizhi' ),
			];

			wp_send_json( $msg );

		} else if ( ! strcasecmp( get_option( 'account_captcha' ), $account_captcha ) == 0 ) {

			$msg = [
				'registered' => false,
				'message'    => __( '<p class="alert alert-danger" data-alert="alert">验证码错误.</p>', ' wizhi' ),
			];

			wp_send_json( $msg );

		} else {

			$errors = wp_create_user( $user_login, $password, $user_email );

			if ( is_wp_error( $errors ) ) {

				$registration_error_messages = $errors->errors;
				$display_errors              = '<div class="alert alert-danger" data-alert="alert">';

				foreach ( $registration_error_messages as $error ) {
					$display_errors .= '<div>' . $error[ 0 ] . '</div>';
				}

				$display_errors .= '</div>';

				$msg = [
					'registered' => false,
					'message'    => $display_errors,
				];

			} else {

				// 生成激活码并添加到用户自定义字段中
				$activation_code = wp_generate_password( 10 );
				add_user_meta( $errors, $this->user_meta, $activation_code );

				$msg = [
					'registered' => true,
					'message'    => __( '<p class="alert alert-success" data-alert="alert">注册完成, 请查收电子邮件.</p>', ' wizhi' ),
				];

			}

			wp_send_json( $msg );
		}

	},

] );


// 找回密码
new Dispatch( [

	'reset-password' => function ( $request ) {

		check_ajax_referer( 'ajax-form-nonce', 'security-reset' );

		$lost_pass = $_POST[ 'lost_pass' ];

		if ( $lost_pass == null ) {

			$msg = [
				'reset'   => false,
				'message' => __( '<p class="alert alert-danger" data-alert="alert">请填写所有字段.</p>', ' wizhi' ),
			];

			wp_send_json( $msg );

		} else {

			if ( is_email( $lost_pass ) ) {
				$username = sanitize_email( $lost_pass );
			} else {
				$username = sanitize_user( $lost_pass );
			}

			$user_forgotten = wizhi_security_retrieve_password( $username );

			if ( is_wp_error( $user_forgotten ) ) {

				$lostpass_error_messages = $user_forgotten->errors;
				$display_errors          = '<div class="alert alert-danger" data-alert="alert">';

				foreach ( $lostpass_error_messages as $error ) {
					$display_errors .= '<div>' . $error[ 0 ] . '</div>';
				}

				$display_errors .= '</div>';

				$msg = [
					'reset'   => false,
					'message' => $display_errors,
				];

			} else {

				$msg = [
					'reset'   => true,
					'message' => __( '<p class="alert alert-success" data-alert="alert">密码重设邮件已发送至你的邮箱,请及时查收.</p>', ' wizhi' ),
				];

			}
		}

		wp_send_json( $msg );

	},

] );


/**
 * 获取密码重置邮件, 其实就是把 wp-login.php 里面的函数复制过来, 加了个参数, 其他的都不用变
 *
 * @param  string $username_or_email 用户名或邮箱
 *
 * @return bool
 */
function wizhi_security_retrieve_password( $username_or_email ) {
	global $wpdb, $wp_hasher;

	$errors = new WP_Error();

	if ( empty( $username_or_email ) ) {
		$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Enter a username or email address.' ) );
	} elseif ( strpos( $username_or_email, '@' ) ) {
		$user_data = get_user_by( 'email', trim( $username_or_email ) );
		if ( empty( $user_data ) ) {
			$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: There is no user registered with that email address.' ) );
		}
	} else {
		$login     = trim( $_POST[ 'user_login' ] );
		$user_data = get_user_by( 'login', $login );
	}

	/**
	 * Fires before errors are returned from a password reset request.
	 *
	 * @since 2.1.0
	 * @since 4.4.0 Added the `$errors` parameter.
	 *
	 * @param WP_Error $errors A WP_Error object containing any errors generated
	 *                         by using invalid credentials.
	 */
	do_action( 'lostpassword_post', $errors );

	if ( $errors->get_error_code() ) {
		return $errors;
	}

	if ( ! $user_data ) {
		$errors->add( 'invalidcombo', __( '<strong>ERROR</strong>: Invalid username or email.' ) );

		return $errors;
	}

	// Redefining user_login ensures we return the right case in the email.
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;
	$key        = get_password_reset_key( $user_data );

	if ( is_wp_error( $key ) ) {
		return $key;
	}

	$message = __( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
	$message .= network_home_url( '/' ) . "\r\n\r\n";
	$message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
	$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
	$message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
	$message .= network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' );

	if ( is_multisite() ) {
		$blogname = $GLOBALS[ 'current_site' ]->site_name;
	} else /*
		 * The blogname option is escaped with esc_html on the way into the database
		 * in sanitize_option we want to reverse this for the plain text arena of emails.
		 */ {
		$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}

	$title = sprintf( __( '[%s] Password Reset' ), $blogname );

	/**
	 * Filter the subject of the password reset email.
	 *
	 * @since 2.8.0
	 * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
	 *
	 * @param string  $title      Default email title.
	 * @param string  $user_login The username for the user.
	 * @param WP_User $user_data  WP_User object.
	 */
	$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

	/**
	 * Filter the message body of the password reset mail.
	 *
	 * @since 2.8.0
	 * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
	 *
	 * @param string  $message    Default mail message.
	 * @param string  $key        The activation key.
	 * @param string  $user_login The username for the user.
	 * @param WP_User $user_data  WP_User object.
	 */
	$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

	if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
		wp_die( __( 'The email could not be sent.' ) . "<br />\n" . __( 'Possible reason: your host may have disabled the mail() function.' ) );
	}

	return true;
}