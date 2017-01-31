<?php
/**
 * 重置密码模板
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package gene
 */

?>


<div id="reset-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">找回密码</h4>
            </div>
            <div class="modal-body">

                <form id="modal-reset-pass" class="modal-resetpass-form" action="" method="post">

                    <p class="alert alert-info" data-alert="alert">请输入你的用户名或电子邮件地址, 你将获得一个密码重置链接.</p>

                    <div class="status"></div>

                    <div class="form-group">
                        <label for="user_login">用户名</label>
                        <input type="text" class="form-control" name="lost_pass" id="lost_pass" placeholder="用户名或电子邮件地址">
                    </div>

					<?php wp_nonce_field( 'ajax-form-nonce', 'security-reset' ); ?>
                    <input type="hidden" name="forgotten" value="true" />

                    <div class="form-controls">
                        <button type="submit" name="user-sumbit" id="user-submit" class="btn btn-primary">找回密码</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->