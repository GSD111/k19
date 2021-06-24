
var check_val;   //当前选项的值

var count = 0;   //总分值初始化

//获取当前点击选项的分值实时计算
$('.cks').click(function () {
    check_val = $(this).val();
    // console.log(check_val)
    if ($(this).is(':checked')) {
        count += parseInt(check_val);
    } else {
        count -= parseInt(check_val);
    }
    console.log(parseInt(count))
})

//提交后所有选项分值根据需求判断呈现
function sub() {

    if(count<=0){

        layer.msg('请依次进行选择',{ icon:5,time:2000})
        return false;
    }
    layer.msg(count, { icon:1,time:2000})


}
