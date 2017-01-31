<?php
/**
 * 用户注册模板
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package gene
 */

?>

<script>
    function RefreshCode(obj) {
        obj.src = obj.src + "?code=" + Math.random();
    }
</script>


<div id="register-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">注册</h4>
            </div>
            <div class="modal-body">
                <form id="modal-register" class="modal-rigister-form" action="" method="post">

                    <div class="status"></div>

                    <div class="form-group">
                        <label for="user_login">用户名</label>
                        <input type="text" class="form-control" name="user_login" id="user_login" placeholder="用户名">
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
                    <div class="form-group">
                        <label for="re_password">验证码</label> <span class="text-msg"></span>
                        <img alt="captcha" onclick="RefreshCode(this)" id="captcha_img" data-toggle="tooltip" title="<?php _e( '点击刷新验证码', 'wizhi' ); ?>"
                             src="<?= home_url( 'captcha/account' ); ?>" />
                        <input type="text" class="form-control" name="captcha" id="captcha" value="" placeholder="输入右图中的验证码, 点击图片刷新" />
                    </div>

					<?php wp_nonce_field( 'ajax-form-nonce', 'security-register' ); ?>

                    <div class="form-controls">
                        <button type="submit" name="pass-sumbit" id="pass-submit" class="btn btn-primary">注册</button>
                        <a id="open-login" class="btn btn-default" href="#">登录</a>
                    </div>

                </form>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->