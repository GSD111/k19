
//题目的切换
$(function () {
    var current_question = 0;  //当前题号
    var total_num = $('.wt').length - 1; //总题量

    $('.p2-num').html('1/' + $('.wt').length);

    $('.wt').hide();
    $('.wt').eq(0).show();

    $('.p3').click(function () {
        if (current_question >= total_num) {
            alert('已经是最后一题！');
            return false;
        }
        current_question++;
        $('.wt').hide();
        $('.wt').eq(current_question).show();

        $('.p2-num').html((current_question * 1 + 1) + '/' + $('.wt').length);

        if (current_question == total_num) {
            $('.p4').show();
            $('.p3').hide();
        }


    })


    $('.p2').click(function () {
        if (current_question <= 0) {
            // alert('已经是第一题！');
            layer.msg('已经是第一题',{ icon:5,time:2000})
            return false;
        }
        current_question--;

        if (current_question != total_num) {

            $('.p4').hide();
            $('.p3').show();
        }
        $('.wt').hide();
        $('.wt').eq(current_question).show();

        $('.p2-num').html((current_question * 1 + 1) + '/' + $('.wt').length);
    })
})