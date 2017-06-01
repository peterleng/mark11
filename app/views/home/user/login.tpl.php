<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">
        登录
    </h4>
</div>
<div class="modal-body">
    <form id="loginForm" action="<?php echo route('home.user.do_login') ?>" class="form-horizontal modal-form" method="post">
        <div class="form-group">
            <label class="col-lg-3 control-label"><small class="text-danger m-r-xs">*</small>手机</label>
            <div class="col-lg-7">
                <input type="text" name="phone" placeholder="phone" class="input-s-sm form-control" maxlength="11" minlength="11" required="" value="">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label"><small class="text-danger m-r-xs">*</small>密码</label>
            <div class="col-lg-7">
                <input type="password" name="passwd" placeholder="password" class="input-s-sm form-control" minlength="6" maxlength="20" required="" value="">
            </div>
        </div>

        <div class="form-group hidden" id="imgCodeDiv">
            <label class="col-lg-3 control-label"><small class="text-danger m-r-xs">*</small>验证码</label>
            <div class="col-lg-4">
                <input type="text" name="imgcode" placeholder="code" class="input-s-sm form-control" disabled minlength="4" maxlength="4" required="" value="">
            </div>
            <div class="col-lg-3">
                <img src="<?php echo route('home.public.imgCode') ?>" title="点击刷新验证码" onclick="this.src='<?php echo route('home.public.imgCode') ?>?'+Math.random();return false;" style="cursor: pointer;">
            </div>
        </div>
        <input type="hidden" name="_token" value="<?php echo session_get('_token') ?>">
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭
    </button>
    <button type="submit" class="btn btn-success">
        登录 Mark11
    </button>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        
        MARK.showLoginImgCode = function () {
            var login_times = parseInt($.cookie('login_times'));
            console.log(login_times);
            if(login_times >= 3){
                $('#imgCodeDiv').removeClass('hidden').find('input').attr('disabled',false);
            }
        };
        MARK.showLoginImgCode();
        
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
                    $.cookie('login_times',null);
                } else {
                    MARK.toastr.error(result.info ? result.info : '程序错误');

                    if(result.data.code && result.data.code > 500){
                        var login_times = $.cookie('login_times');

                        login_times = parseInt(login_times) || 1;
                        $.cookie('login_times',login_times + 1);
                        MARK.showLoginImgCode();
                    }
                }
            }, 'json');
        };
        $('#loginForm').bootstrapValidator(MARK.formVerify);
    });

</script>