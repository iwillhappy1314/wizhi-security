<?php
/**
 * 用户登录模板
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package gene
 */

?>

<form id="modal-login" action="" method="post" class="pure-form pure-form-stacked" style="display: none">

	<div class="modal-header">
		<a href="#close" class="close" rel="modal:close">x</a>
		<h4 class="modal-title">登录</h4>
	</div>

	<div class="modal-content">

		<div class="status"></div>

		<div class="pure-control-group">
			<label for="username">用户名</label>
			<input type="text" class="form-control" name="username" id="username" placeholder="用户名">
		</div>
		<div class="pure-control-group">
			<label for="password">密码</label>
			<input type="password" class="form-control" name="password" id="password" placeholder="密码">
		</div>

		<?php wp_nonce_field( 'ajax-login-nonce', 'security-login' ); ?>

		<div class="pure-controls">
			<label class="pure-checkbox" >
				<input name="rememberme" type="checkbox" id="rememberme" value="forever"> 记住我
			</label>
			<button type="submit" name="submit" id="wp-submit" class="pure-button button-primary">登录</button>
			<a class="pure-button button-default" href="#modal-register" rel="modal:open">注册</a>
			<a href="#modal-reset-pass" rel="modal:open">找回密码</a>
		</div>

	</div>

</form>