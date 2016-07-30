<?php

if ( ! function_exists( 'security_get_template_part' ) ) {
	/**
	 * 自定义模板加载器, 优先加载主题中的模板, 如果主题中的模板不存在, 就加载插件中的
	 *
	 * @param mixed  $slug 模板名称的前缀, 模板名称的后缀
	 * @param string $name (default: '')
	 *
	 * @package template
	 */

	function security_get_template_part( $slug, $name = '' ) {
		$template = '';

		// 查找主题中定义的插件模板
		if ( $name ) {
			$template = locate_template( [ "{$slug}-{$name}.php", "wizhi/{$slug}-{$name}.php" ] );
		}

		// 加载插件中定义的模板
		if ( ! $template && $name && file_exists( WIZHI_SECURITY . "templates/{$slug}-{$name}.php" ) ) {
			$template = WIZHI_SECURITY . "templates/{$slug}-{$name}.php";
		}

		// 加载主题中的默认模板
		if ( ! $template ) {
			$template = locate_template( [ "{$slug}.php", "wizhi/{$slug}.php" ] );
		}

		// 允许第三方插件过滤模板文件
		$template = apply_filters( 'wizhi_get_template_part', $template, $slug, $name );

		if ( $template ) {
			load_template( $template, false );
		}
	}
}