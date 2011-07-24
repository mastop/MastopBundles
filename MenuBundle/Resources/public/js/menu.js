$(function() {
    $(".newItem").click(function () {
        $(this).next().fadeToggle("slow", "linear");
    });
    $("#newMenu").click(function () {
        $("#newMenuForm").fadeToggle("slow", "linear");
        if ( $('#newMenuForm').is(':visible')){
            $('html,body').animate({
                scrollTop: $("#newMenuForm").offset().top
            },'slow');
        }
    });
    $("#newMenuAdd").click(function (e) {
        var total = $('.newMenuItem').length;
        var clone = $('#newMenuItemCopy').prev().clone();
        clone.children('h2').html('Link '+(total+1));
        // Alterações para o select funcionar com o plyugin uniform
        clone.find('.selector span').remove();
        clone.find('.selector').removeAttr('class');
        clone.find('select').uniform();
        clone.appendTo('#newMenuItemCopy');
        e.preventDefault();
        return false;
    });
    $("#newMenuDel").click(function (e) {
        var total = $('.newMenuItem').length;
        if(total > 1){
            $('#newMenuItemCopy').children().last().remove();
        }
        e.preventDefault();
        return false;
    });
});