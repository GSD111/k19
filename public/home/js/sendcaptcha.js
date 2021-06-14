function duanxin() {
//获取手机
    var phone = $("#phone").val();
    // console.log(phone);
    if (!(/^1[3-9|4|5|7|8][0-9]\d{8,11}$/.test(phone))) {
        // alert('请输入正确的手机号');
        layer.msg('请输入正确的手机号', {icon: 5, time: 2000})
        return false;
    } else {

        var count = 60;
        var countdown = setInterval(CountDown, 1000);

        function CountDown() {
            $("#btn").attr("disabled", true);
            $("#btn").val("已发送(" + count + ")");
            if (count == 0) {
                $("#btn").val("获取验证码").removeAttr("disabled");
                clearInterval(countdown);
            }
            count--;
        }
    }
    $.ajax({
        url: '/home/SendCaptcha?phone=' + phone,
        data: {},
        type: "POST",
        dataType: "Json",
        success: function (data) {
            if (data['status'] == 200) {
                // console.log(data);
                layer.msg(data.msg, {icon: 1, time: 1500});
            } else {
                layer.msg(data.msg, {icon: 5, time: 2000});
            }
        }
    });

}

function IsCheckPhone() {
    if($('#phone').val().length<1){
        $('#phone').focus();
        layer.msg('请输入手机号', { icon:5,time:2000})
        return false;
    }
    if($('#live_captcha').val().length<1){
        $('#live_captcha').focus();
        layer.msg('请输入验证码', { icon:5,time:2000})
        return false;
    }
}