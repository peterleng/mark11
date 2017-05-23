<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">
        注册
    </h4>
</div>
<div class="modal-body">
    <form action="" class="form-horizontal modal-form" method="post">
        <div class="form-group">
            <label class="col-lg-3 control-label"><small class="text-danger m-r-xs">*</small>手机号</label>
            <div class="col-lg-4">
                <input type="text" name="phone" placeholder="Phone" class="input-s-sm form-control" size="11" required="" value="">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label"><small class="text-danger m-r-xs">*</small>密码</label>
            <div class="col-lg-4">
                <input type="password" name="password" placeholder="Password" class="input-s-sm form-control" minlength="6" maxlength="20" required="" value="">
            </div>
        </div>

    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭
    </button>
    <button type="button" class="btn btn-primary" onclick="BC.submitForm();">
        提交更改
    </button>
</div>