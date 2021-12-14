$(document).ready(function () {
    $('.graph-yesterday').hide();
});

$('#switch-day-today').click(function (){
    $('.graph-today').hide();
    $('.graph-yesterday').show();
});

$('#switch-day-yesterday').click(function (){
    $('.graph-yesterday').hide();
    $('.graph-today').show();
});

