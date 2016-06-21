<?php
/**
 * 用户注册模板
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package gene
 */

?>

<form id="modal-register" action="" method="post" style="display: none">

	<div class="modal-header">
		<a href="#close" class="close" rel="modal:close">x</a>
		<h4 class="modal-title">注册</h4>
	</div>

	<div class="modal-body">

		<div class="status"></div>

		<div class="form-group">
			<label for="user_login">用户名</label>
			<input type="text" class="form-control"  name="user_login" id="user_login" placeholder="用户名">
		</div>

		<div class="form-group">
			<label for="user_email">电子邮件</label>
			<input type="email" class="form-control" name="user_email" id="user_email" placeholder="电子邮件">
		</div>

		<div class="form-group">
			<label for="password">密码</label>
			<input type="password" class="form-control" name="password" id="password" placeholder="密码">
		</div>
		<div class="form-group">
			<label for="re_password">密码</label>
			<input type="password" class="form-control" name="re_password" id="re_password" placeholder="重复密码">
		</div>

		<?php wp_nonce_field( 'ajax-form-nonce', 'security-register' ); ?>

		<button type="submit" name="pass-sumbit" id="pass-submit" class="btn btn-primary">注册</button>
		<a class="btn btn-default" href="#modal-login" rel="modal:open">登录</a>

	</div>

</form>