function CheckSubmit(){
    if($('#live_name').val().length<1){
        $('#live_name').focus();
        layer.msg('请输入您的名字或者商家名字', { icon:5,time:2000})
        return false;
    }
    if($('#live_address').val().length<1){
        $('#live_address').focus();
        layer.msg('请输入您的具体地址', { icon:5,time:2000})
        return false;
    }
    if($('#live_username').val().length<1){
        $('#live_username').focus();
        layer.msg('请输入联系人姓名', { icon:5,time:2000})
        return false;
    }
    if($('#live_phone').val().length<1){
        $('#live_name').focus();
        layer.msg('请输入您的手机号', { icon:5,time:2000})
        return false;
    }
    if($('#live_time').val().length<1){
        $('#live_time').focus();
        layer.msg('请输入您的服务时间', { icon:5,time:2000})
        return false;
    }
}


function UserTest() {
    if($('#live_username').val().length<1){
        $('#live_username').focus();
        layer.msg('请输入您的名字', { icon:5,time:2000})
        return false;
    }
    if($('#live_age').val().length<1){
        $('#live_age').focus();
        layer.msg('请输入您的年龄', { icon:5,time:2000})
        return false;
    }
    if($('#live_email').val().length<1){
        $('#live_email').focus();
        layer.msg('请输入您的邮箱', { icon:5,time:2000})
        return false;
    }
    if($('#live_job').val().length<1){
        $('#live_job').focus();
        layer.msg('请输入您的职业信息', { icon:5,time:2000})
        return false;
    }
    if($('#live_remark').val().length<1){
        $('#live_remark').focus();
        layer.msg('请输入您的阐述', { icon:5,time:2000})
        return false;
    }

}