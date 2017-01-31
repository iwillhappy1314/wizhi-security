<?php
/**
 * 用户登录模板
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package gene
 */

?>


<div id="login-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">登录</h4>
            </div>
            <div class="modal-body">
                <form id="modal-login" action="" method="post" class="modal-login-form">

                    <div class="status"></div>

                    <div class="form-group">
                        <label for="username">用户名</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="用户名">
                    </div>
                    <div class="form-group">
                        <label for="password">密码</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="密码">
                    </div>

					<?php wp_nonce_field( 'ajax-login-nonce', 'security-login' ); ?>

                    <div class="form-controls">
                        <label class="pure-checkbox">
                            <input name="rememberme" type="checkbox" id="rememberme" value="forever"> 记住我
                        </label>
                        <button type="submit" name="submit" id="wp-submit" class="btn btn-primary">登录</button>
                        <a id="open-register" class="btn btn-default" href="#">注册</a>
                        <a id="open-reset" href="#">找回密码</a>
                    </div>

                </form>

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->