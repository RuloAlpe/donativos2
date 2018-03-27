var l;
$(document).ready(function(){
    $(".ladda-button").on("click", function(){
        
        var form = $("form");
        l = Ladda.create(this);
       // l.start();
        form.yiiActiveForm('validate', true);
        
    });

    $('form').on('afterValidate', function (e, attribute, message) {
        if(message.length>0){
            l.stop();
        }
    });
});