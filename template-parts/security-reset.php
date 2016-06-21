<?php
/**
 * 重置密码模板
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package gene
 */

?>

<form id="modal-reset-pass" action="" method="post" style="display: none">

	<div class="modal-header">
		<a href="#close" class="close" rel="modal:close">x</a>
		<h4 class="modal-title">找回密码</h4>
	</div>

	<div class="modal-body">

		<p class="alert alert-info" data-alert="alert">请输入你的用户名或电子邮件地址, 你将获得一个密码重置链接.</p>

		<div class="status"></div>

		<div class="form-group">
			<label for="user_login">用户名</label>
			<input type="text" class="form-control" name="lost_pass" id="lost_pass" placeholder="用户名或电子邮件地址">
		</div>

		<?php wp_nonce_field( 'ajax-form-nonce', 'security-reset' ); ?>
		<input type="hidden" name="forgotten" value="true"/>

		<button type="submit" name="user-sumbit" id="user-submit" class="btn btn-primary">找回密码</button>
		<a class="btn btn-default" href="#modal-login" rel="modal:open">登录</a>

	</div>

</form>