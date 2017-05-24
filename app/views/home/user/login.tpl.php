<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="myModalLabel">
        登录
    </h4>
</div>
<div class="modal-body">
    <form action="" class="form-horizontal modal-form" method="post">
        <div class="form-group">
            <label class="col-lg-3 control-label"><small class="text-danger m-r-xs">*</small>手机</label>
            <div class="col-lg-7">
                <input type="text" name="phone" placeholder="phone" class="input-s-sm form-control" maxlength="11" minlength="11" required="" value="">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label"><small class="text-danger m-r-xs">*</small>密码</label>
            <div class="col-lg-7">
                <input type="password" name="password" placeholder="password" class="input-s-sm form-control" minlength="6" maxlength="24" required="" value="">
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭
    </button>
    <button type="button" class="btn btn-success" onclick="BC.submitForm();">
        提交更改
    </button>
</div>