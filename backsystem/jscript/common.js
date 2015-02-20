/**
*Procedimientos y funciones de uso general
*
*/
var nav4 = window.event ? true : false;
var exprcontacto = /\[contacto\]\[(.*)\]/;
var exprmarca = /\[anunciante\]\[(.*)\]/;

$( document ).ready(function(){


	
	$( "#menu" ).menu({
	  position: { my: "bottom", at: " top+5" }
	});

	
	$(".adminheading th").append("<hr />");	



	 $( ".calendar" ).datepicker({
	 	changeMonth: true,
      	changeYear: true,
      	dateFormat: "yy-mm-dd",
      	dayNames: [ "Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado" ],
      	dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
      	dayNamesShort: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"  ],
      	monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
      	monthNamesShort: [ "En", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
      	yearRange: "1960:2015"
	 });
	


	/*
	 * Arbol Secciones
	 */
	$("#ArbolSecciones").treeview();
	
	$("#IDDepartamento").change(function () {
		var IDDepartamento = $(this).val();
		$.ajax({
			   type: "POST",
			   url: "ajax/CargaCiudad.php",
			   data: "IDDepartamento="+IDDepartamento,
			   dataType: "json",
			   success: function(msg){
					$("#IDCiudad").removeOption(/./);
					$("#IDCiudad").addOption(msg, true);
					$("#IDCiudad").selectOptions("");						
			   }
		});
	});

	$("#IDPais").change(function () {
		var IDPais = $(this).val();
		$.ajax({
			   type: "POST",
			   url: "ajax/CargaDepartamentos.php",
			   data: "IDPais="+IDPais,
			   dataType: "json",
			   success: function(msg){
					$("#IDDepartamento").removeOption(/./);
					$("#IDDepartamento").addOption(msg, true);
					$("#IDDepartamento").selectOptions("");
			   }
		});
	});
	
	
	
	/*
	 * Toggle de los shortcuts
	 */
	$( "#hidemenuleft" ).click(function(){
	    $("#shortcuts").toggle();
		$(".shortcuts").width( "10px" );
	});
	/*
	 * denegacion de campos
	 */	 
	 //solo numeros
	$( "input.onlynumber" ).keypress(function( evt ){
		var key = nav4 ? evt.keyCode : evt.which;
		return /[\d]/.test( String.fromCharCode( key ) );
	});
	
	//monetario
	$( "input.money" ).keyup(function(){
		this.value = number_format( this.value );
	});
	
	//solo letras
	$( "input.onlyword" ).keypress(function(){
		var key = nav4 ? evt.keyCode : evt.which;
		return /[\w]/.test( String.fromCharCode( key ) );
	});
	
	$( "form.formvalida" ).submit(function(){ return EvaluaReg( this ) });
	
	
	$( "#tabsform" ).tabs();


	$(".btnEnviar").click(function(){
		$(this).parents("form").submit();
		return false;
	});
		


	
	$(".btnShow").click(function(){
		var content = $(this).attr("rel");
		$( "#" + content ).toggle();
		return false;
	});


	$(".colorbox").colorbox({iframe:true, width:"80%", height:"80%"});

	
	/*** WIZARD ***/
	var navListItems = $('ul.setup-panel li a'),
   	allWells = $('.setup-content');

    allWells.hide();

    navListItems.click(function(e)
    {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this).closest('li');
        
        if (!$item.hasClass('disabled')) {
            navListItems.closest('li').removeClass('active');
            $item.addClass('active');
            allWells.hide();
            $target.show();
        }
    });
    
    $('ul.setup-panel li.active a').trigger('click');
    
    // DEMO ONLY //
    $('#activate-step-2').on('click', function(e) {
        $('ul.setup-panel li:eq(1)').removeClass('disabled');
        $('ul.setup-panel li a[href="#step-2"]').trigger('click');
        $(this).remove();
    });

     $('.btnWizard').on('click', function(e) {
        var target = $(this).attr("href");
        $('ul.setup-panel li a[href="' + target + '"]').trigger('click');
        //$taget.trigger('click');
        //$(this).trigger('click');
        //$(this).remove();
   		return false;
    })

    

    /***** FIN WIZARD ****/

	
});






function addNotify()
{
	if( !window.contactonotify )
	{
		window.contactonotify = $( '<div id="volatilnotif" class="mensaje info">Obteniendo datos...</div>' )
					.css({ 
							"position":"absolute",
							"top" : "300px",
							"left" : "450px",
							"width" : "300px"
				}).get();
				
		$( document.body ).append( window.contactonotify );
	}
	else
		$( window.contactonotify ).show();
	
	setTimeout( function(){$( "#volatilnotif" ).fadeOut( "slow" )} , 600 );
		
}



function checkall( obj , selector )
{
	var checked_status = obj.checked;
	$( selector ).each(function(){
		this.checked = checked_status;
	});
}

function number_format( num )
{
	var tmpnum = [], 
	cont = 1 , 
	charlen = 0;
	
	var fparts = [];
	
	num = getNum( num ).toString();
	
	if( num.indexOf(".") != -1 )
	{
		fparts = num.split(".");
		num = fparts[0];	
	}

	fparts = fparts[1] || fparts[1] == "" ? "." + fparts[1] : "";
		
	charlen = num.length - 1;
	
	for( var i = charlen ; i >= 0 ; i-- )
	{
		tmpnum.unshift( num.charAt( i ) );
			
		if( cont == 3 && i != 0 )
		{
			cont = 1;
			tmpnum.unshift( "," );
			continue;
		}
		cont++;
	}
	
	tmpnum.push( fparts );		
	return tmpnum.join("");
}



function acceptNum(evt){ 
	var key = !nav4 ? evt.which : evt.keyCode; 
	return (key <= 13 || (key >= 46 && key <= 57));
}

function getNum(strNum)
{
	//console.log(strNum);
	num = strNum.toString().replace(/\$|\,/g,'');
	return isNaN( num ) ? 0 : num;
}