$('.close').click(function(){
    $('.fftc').css('display','none');
});


$('.price').hide();
$('.price').eq(0).show();

//点击倾述时长显示对应价格
$('.time').click(function(){
    var indexVal=$(this).attr('index');
    var val = $(this).attr('value')
    // console.log(val);
    times = val;
    // doctor_price_id = indexVal;
    $('.time').removeAttr('style','');
    $(this).css('background','#ef8d6f','color','#fff');
    $('.price').hide();
    $('.price[index-item="'+indexVal+'"]').show();
    price = $('.price[index-item="'+indexVal+'"]').attr('index-val')
    //
    // console.log(price)
})

//选择对应的倾诉方向
$('.specialty').click(function(){
    var val = $(this).attr('index');
    specialty = val;
    // console.log(val)
    $('.specialty').removeClass('chk');
    $(this).addClass('chk');
    // console.log(val)
})




//获取对应的支付类别的属性值
$('#weixin').click(function(){
    pay_way = $(this).attr('index-pay')
    // console.log(pay_way)
})
$('#zhifubao').click(function(){
    pay_way = $(this).attr('index-pay')
    // console.log(pay_way)
})


var times;        //选择的时长
var price;        //对应时长的价格
var specialty;    //倾述的方向
// var doctor_id     //医生的id
var doctor_name;  //医生的名字
var pay_way;      //支付的类别


function pay(id) {
    // console.log(id)
    var username = $('#username').val();
    var phone_number = $('#phone_number').val();
    // console.log(username,phone_number)
    if($('#username').val().length < 1){
        $('#username').focus();
        layer.msg('请输入您的姓名',{icon: 5, time: 2000})
        return false;
    }
    if($('#phone_number').val().length < 11){
        $('#phone_number').focus();
        layer.msg('输入的电话格式不正确',{icon: 5, time: 2000})
        return false;
    }
    if(typeof(doctor_price_id) == 'undefined' && typeof(specialty) == 'undefined'){
        layer.msg('请选择您要倾述的套餐及方向', {icon: 5, time: 2000})
        return false;
    }

    $.ajax({
        url:'/home/UserTalkOrder',
        data:JSON.stringify({
            // 'doctor_price_id':doctor_price_id,
            'times':times,
            'price':price,
            "pay_way":pay_way,
            'doctor_id':id,
            'specialty':specialty,
            'doctor_name':doctor_name,
            'username':username,
            'phone_number':phone_number
        }),
        type:'POST',
        dataType:'JSON',
        success:function (data) {
            console.log(data)
        }

    })
}