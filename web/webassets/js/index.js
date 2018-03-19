var total =9999;
$(document).ready(function(){

    $(".ladda-button").on("click", function(){
        var l = Ladda.create(this);
         l.start();
         $("form").submit();
    });

    $(".panel.card").on("click",function(){
        esconderTipoDonativo();
    });

    $("div.panel.card").on("click",function(){
        var elemento = $("#js_susbcripcion");
        elemento.prop("checked", false);
        seleccionarTipoDonativo(elemento);
    });

    $(".js-back").on("click", function(e){
        e.preventDefault();
        esconderSlider();
    });

    $("#js_susbcripcion").on("change", function(){
        var elemento = $(this);
        seleccionarTipoDonativo(elemento);
    })
});

function seleccionarTipoDonativo(elemento){
    if(elemento.prop("checked")){
        $(".js-apadrinar").show();
    }else{
        $(".js-apadrinar").hide();
    }
}

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
    $(".js-amount").html(addCommas($amount));
    $('#slider span').html('<label><span class="glyphicon glyphicon-chevron-left"></span> '+$amount+' <span class="glyphicon glyphicon-chevron-right"></span></label>');

    var degradado = (val * 1) / total;
    console.log(degradado);
    degradado = (1 - degradado);

    if(degradado<0){
        degradado = degradado * -1;
    }
    $('.bkgd-gral-mask').css('background-color', 'rgba(0,0,0,' + degradado + ')');
}


function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
