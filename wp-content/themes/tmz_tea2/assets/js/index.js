$(function(){
    $('#header_mobile_menu').on('click', function(){
        $("#mobile_bg").show(0);
        $("#mobile_menu").css({'margin-right':'0px'});
    });

    $('#mobile_bg').on('click',function(){
        $("#mobile_bg").slideUp(300);
        $("#mobile_menu").css({'margin-right':'-300px'});
    });

});