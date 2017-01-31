<?php

if ( ! class_exists( 'Wizhi_Security_Setting' ) ):

	/**
	 * Wizhi CMS 插件设置
	 *
	 * @package settings
	 *
	 * @author  Amos Lee
	 */
	class Wizhi_Security_Setting {

		private $settings_api;

		/**
		 * 构造函数
		 */
		function __construct() {
			$this->settings_api = new WeDevs_Settings_API;

			add_action( 'admin_init', [ $this, 'admin_init' ] );
			add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		}

		/**
		 * 初始化
		 */
		function admin_init() {

			//set the settings
			$this->settings_api->set_sections( $this->get_settings_sections() );
			$this->settings_api->set_fields( $this->get_settings_fields() );

			//initialize settings
			$this->settings_api->admin_init();
		}

		/**
		 * 管理菜单
		 */
		function admin_menu() {
			add_options_page( '注册登录设置', '注册登录', 'manage_options', 'wizhi_security_settings', [ $this, 'plugin_page' ] );
		}

		/**
		 * 设置选项卡
		 *
		 * @return array 设置选项卡数组
		 */
		function get_settings_sections() {
			$sections = [
				[
					'id'    => 'wizhi_security_basics',
					'title' => __( '常规设置', 'wizhi' ),
				],
				[
					'id'    => 'wizhi_security_oauth',
					'title' => __( '社会化登录设置', 'wizhi' ),
				],
			];

			return $sections;
		}

		/**
		 * 返回所有插件设置字段
		 *
		 * @return array 设置字段
		 */
		function get_settings_fields() {
			$settings_fields = [
				'wizhi_security_basics'   => [
					[
						'name'              => 'is_show_modal',
						'label'             => __( '是否显示模态窗口', 'wizhi' ),
						'type'              => 'checkbox',
					],
					[
						'name'              => 'ucenter_link',
						'label'             => __( '用户中心链接', 'wizhi' ),
						'type'              => 'text',
					],
				],
				'wizhi_security_oauth' => [
					[
						'name'              => 'qq_api',
						'label'             => __( 'QQ API', 'wizhi' ),
						'type'              => 'text',
					],
					[
						'name'              => 'qq_key',
						'label'             => __( 'QQ Security', 'wizhi' ),
						'type'              => 'text',
					],
					[
						'name'              => 'weibo_api',
						'label'             => __( 'Weibo API', 'wizhi' ),
						'type'              => 'text',
					],
					[
						'name'              => 'weibo_key',
						'label'             => __( 'Weibo Security', 'wizhi' ),
						'type'              => 'text',
					],
					[
						'name'              => 'wechat_api',
						'label'             => __( 'WeChat API', 'wizhi' ),
						'type'              => 'text',
					],
					[
						'name'              => 'wechat_key',
						'label'             => __( 'WeChat Security', 'wizhi' ),
						'type'              => 'text',
					],
				],
			];

			return $settings_fields;
		}


		/**
		 * 插件页面
		 */
		function plugin_page() {
			echo '<div class="wrap">';

			$this->settings_api->show_navigation();
			$this->settings_api->show_forms();

			echo '</div>';
		}

		/**
		 * 获取所有页面
		 *
		 * @return array 页面“ID->名称”键值对
		 */
		function get_pages() {
			$pages         = get_pages();
			$pages_options = [ ];
			if ( $pages ) {
				foreach ( $pages as $page ) {
					$pages_options[ $page->ID ] = $page->post_title;
				}
			}

			return $pages_options;
		}

	}

endif;
