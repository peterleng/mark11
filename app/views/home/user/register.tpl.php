<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">
        用户注册
    </h4>
</div>
<div class="modal-body">
    <form id="registerForm" action="<?php echo route('home.user.do_register') ?>" class="form-horizontal modal-form"
          method="post">
        <div class="form-group">
            <label class="col-lg-3 control-label">
                <small class="text-danger m-r-xs">*</small>
                手机</label>
            <div class="col-lg-7">
                <input type="text" name="phone" placeholder="phone" class="input-s-sm form-control" maxlength="11"
                       minlength="11" required="" value="">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label">
                <small class="text-danger m-r-xs">*</small>
                密码</label>
            <div class="col-lg-7">
                <input type="password" name="passwd" placeholder="password" class="input-s-sm form-control"
                       minlength="6" maxlength="20" required="" value="">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label">
                <small class="text-danger m-r-xs">*</small>
                验证码</label>
            <div class="col-lg-4">
                <input type="text" name="imgcode" placeholder="code" class="input-s-sm form-control" minlength="4"
                       maxlength="4" required="" value="">
            </div>
            <div class="col-lg-3">
                <img src="<?php echo route('home.public.imgCode') ?>" title="点击刷新验证码"
                     onclick="this.src='<?php echo route('home.public.imgCode') ?>?'+Math.random();return false;"
                     style="cursor: pointer;">
            </div>
        </div>

        <div class="alert alert-success" style="display: none;"></div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭
    </button>
    <button type="submit" class="btn btn-success">
        注册新用户
    </button>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        MARK.formVerify.fields = {
            phone: {
                message: '手机验证失败',
                validators: {
                    notEmpty: {
                        message: '手机号不能为空'
                    },
                    regexp: {
                        regexp: /^1[3578]\d{9}$/,
                        message: '手机号格式不合法'
                    }
                }
            },
            passwd: {
                validators: {
                    notEmpty: {
                        message: '密码不能为空'
                    },
                    stringLength: {
                        min: 6,
                        max: 20,
                        message: '密码必须为6-20位字符'
                    }
                }
            },
            imgcode: {
                validators: {
                    notEmpty: {
                        message: '验证码不能为空'
                    },
                    stringLength: {
                        min: 4,
                        max: 4,
                        message: '验证码必须为4位'
                    }
                }
            }
        };

        MARK.formVerify.submitHandler = function (validator, form, submitButton) {
            $.post(form.attr('action'), form.serialize(), function (result) {
                if (result.status == 'success') {
                    MARK.toastr.success(result.info);
                } else {
                    MARK.toastr.error(result.info ? result.info : '程序错误');
                }
            }, 'json');
        };
        $('#registerForm').bootstrapValidator(MARK.formVerify);
    });

</script>