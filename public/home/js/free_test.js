
var check_val;   //当前选项的值

var count = 0;   //存储最后的总计算分值

var count_arr=[]; //存储当前选择的分值
//获取当前点击选项的分值实时计算
$('.cks').click(function () {
    $(this).parent('ul').find('li').removeClass('active');
    $(this).addClass('active');
    check_val = $(this).find('input').val();
    var i=$(this).attr('i');
    if ($(this).find('.rad').attr('checked')=='checked') {
        $(this).find('.rad').attr('checked','');
        // count -= parseInt(check_val);
    } else {
        $(this).find('.rad').attr('checked','checked');
        // count += parseInt(check_val);
    }
    count_arr[i]=parseInt(check_val);
    // console.log(count_arr);
    count = eval(count_arr.join("+"));
    // console.log(count)
})



//提交后所有选项分值根据需求判断呈现
function sub() {

    if(count<=0){
        layer.msg('请依次进行选择',{ icon:5,time:2000})
        return false;
    }
    if(count<= 20){
        layer.msg('放心吧您现在还不需要担心哦！', { icon:1,time:4000})
        return false
    }
    if(count<= 50){
        layer.msg('您可能正处于边缘状态，但是也不需要太担心', { icon:1,time:4000})
        return false
    }
    if(count<=70){
        layer.msg('您需要警惕您现在的情况，必要的时候您可以与我们取得联系，我们会根据您的具体情况给出建议或者方法', { icon:5,time:4000})
        return false
    }
    if(count > 70){
        layer.msg('您现在的情况不是特别的乐观，请您与我们取得联系，我们会给出建议或者办法。', { icon:5,time:4000})
        return false
    }

    // layer.msg(count, { icon:1,time:4000})


}
