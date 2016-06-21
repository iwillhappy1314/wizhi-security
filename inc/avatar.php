<?php

add_filter( 'get_avatar', 'wizhi_security_avatar_hook', 1, 5 );
/**
 * 替换头像为社会化平台的头像
 *
 * @param object $avatar      原始的头像
 * @param string $id_or_email 用户 id 或邮箱
 * @param string $size        头像大小
 * @param string $default     获取不到头像时的默认头像
 * @param string $alt         头像的 alt 文本
 *
 * @return mixed|string
 */
function wizhi_security_avatar_hook( $avatar, $id_or_email, $size, $default, $alt ) {
	$user = false;

	if ( is_numeric( $id_or_email ) ) {
		$id   = (int) $id_or_email;
		$user = get_user_by( 'id', $id );
	} elseif ( is_object( $id_or_email ) ) {
		$user = $id_or_email;
	} else {
		$user = get_user_by( 'email', $id_or_email );
	}

	if ( $user && is_object( $user ) ) {

		$uid = $user->ID;

		if ( get_user_meta( $user->ID, 'weibo_avatar', true ) ) {

			$avatar = get_user_meta( $uid, 'weibo_avatar', true );
			$avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";

		} else if ( get_user_meta( $uid, 'qq_avatar', true ) ) {

			$avatar = get_user_meta( $uid, 'qq_avatar', true );
			$avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";

		}

	}

	return $avatar;
}

