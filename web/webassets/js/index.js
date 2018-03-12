$(document).ready(function(){
    $(".panel.card").on("click",function(){
        esconderTipoDonativo();
    });

    $("div.panel.card").on("click",function(){
        $("#js_susbcripcion").prop("checked", false);
    });

    $(".js-back").on("click", function(e){
        e.preventDefault();
        esconderSlider();
    });

    $("#js_susbcripcion").on("change", function(){
        var elemento = $(this);
        if(elemento.prop("checked")){
            $(".js-apadrinar").show();
        }else{
            $(".js-apadrinar").hide();
        }
    })
});

function esconderTipoDonativo(){
    $(".js-tipo-donativo").fadeOut("slow", function(){
        mostrarSlider();
    });

    
}

function mostrarTipoDonativo(){
    $(".js-tipo-donativo").fadeIn();
}

function esconderSlider(){
    $(".js-slider").fadeOut("slow", function(){
        mostrarTipoDonativo();
    });
}

function mostrarSlider(){
    $(".js-slider").fadeIn();
}

 //changed. now with parameter
 function update(slider,val) {
    //changed. Now, directly take value from ui.value. if not set (initial, will use current value.)
    var $amount = val;   
    $("#amount_plan").val($amount);
    $(".js-amount").html($amount);
    $('#slider a').html('<label><span class="glyphicon glyphicon-chevron-left"></span> '+$amount+' <span class="glyphicon glyphicon-chevron-right"></span></label>');
}

