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