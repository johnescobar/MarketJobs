/**
*Procedimientos y funciones de uso del cotizador
*
*/

jQuery( document ).ready(function(){
	
	preparamarket();
	
});


function preparamarket()
{
	
	$( "#NombrePregrado" ).autocomplete({
		
		source: function( request, response ) {
				
			$.ajax({
				url: "includes/async/pregrado.async.php",
				dataType: "json",
				type : "POST",
				data: {
					name_startsWith: request.term
				},
				success: function( data ) {
					
					if( jQuery.isEmptyObject( data.column ) )
					{
						$("#IDPregrado").val("");
						//$(".formPregrado input#Pregrado").val( $("input#NombrePregrado").val() );
						
					}
					else
					{
						$(this).val("");
						$(".formPregrado").hide();
						response( $.map( data.column, function( item ) {
							return {
								label: item.Nombre ,
								value: item.Nombre,
								IDPregrado: item.IDPregrado,
								Nombre: item.Nombre,
							}
						}));
						
						
						
						
					}//end else
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert("error");
				}
			});
		},
		select: function( event, ui ) {
			//agregar_pregrado_oferta( ui.item.IDPregrado );
			$("#NombrePregrado").val(  ui.item.Nombre );
			$("#IDPregrado").val(  ui.item.IDPregrado );
			$("#FechaFinPregrado").val(  "" );
			$("#Pregrado").val(  "" );


		}

	});
	

	$('.addPregrado').unbind('click').on('click', function(){
		//Verificar #Pregrado
		var action = "ofertapregrado";
		var idpregrado = $("#IDPregrado").val();
		var pregrado = $("#NombrePregrado").val();
		if( idpregrado === "" )
		{
			idpregrado = agregar_pregrado( pregrado );
			//console.log("ya pase" + idpregrado);
			//$("#IDPregrado").val( idpregrado );
		}//end if
		else
		{
			//agregar validacion de campos
			agregar_pregrado_oferta(idpregrado, $("#FechaFinPregrado").val() );
			$(".inputpregrado").val("");
		}//end else
		

		return false;

	});

	$('.btndeletePregrado').unbind('click').on('click', function(){
		//Verificar #Pregrado
		var idpregrado = $(this).attr("rel");
		idpregrado = eliminar_pregrado_oferta( idpregrado );
		

		return false;

	});


	
	
	//POSGRADO
	$( "#NombrePosgrado" ).autocomplete({
		
		source: function( request, response ) {
				
			$.ajax({
				url: "includes/async/posgrado.async.php",
				dataType: "json",
				type : "POST",
				data: {
					name_startsWith: request.term
				},
				success: function( data ) {
					
					if( jQuery.isEmptyObject( data.column ) )
					{
						$("#IDPosgrado").val("");
						//$(".formPregrado input#Pregrado").val( $("input#NombrePregrado").val() );
						
					}
					else
					{
						$(this).val("");
						$(".formPosgrado").hide();
						response( $.map( data.column, function( item ) {
							return {
								label: item.Nombre ,
								value: item.Nombre,
								IDPosgrado: item.IDPosgrado,
								Nombre: item.Nombre,
							}
						}));
						
						
						
						
					}//end else
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert("error");
				}
			});
		},
		select: function( event, ui ) {
			//agregar_pregrado_oferta( ui.item.IDPregrado );
			$("#NombrePosgrado").val(  ui.item.Nombre );
			console.log(ui.item);
			$("#IDPosgrado").val(  ui.item.IDPosgrado );
			$("#FechaFinPosgrado").val(  "" );
			$("#Posgrado").val(  "" );


		}

	});
	

	$('.addPosgrado').unbind('click').on('click', function(){
		//Verificar #Pregrado
		var action = "ofertaposgrado";
		var idposgrado = $("#IDPosgrado").val();
		var posgrado = $("#NombrePosgrado").val();
		if( idposgrado === "" )
		{
			idposgrado = agregar_posgrado( posgrado );
			//console.log("ya pase" + idpregrado);
			//$("#IDPregrado").val( idpregrado );
		}//end if
		else
		{
			//agregar validacion de campos
			agregar_posgrado_oferta(idposgrado, $("#FechaFinPosgrado").val() );
			$(".inputposgrado").val("");
		}//end else
		

		return false;

	});

	$('.btndeletePosgrado').unbind('click').on('click', function(){
		//Verificar #Pregrado
		var idposgrado = $(this).attr("rel");
		idposgrado = eliminar_posgrado_oferta( idposgrado );
		

		return false;

	});

	
	
}//end function

//funcion para agregar una carrera de pregrado que no exista
function agregar_pregrado(strNombre)
{
	var idpregrado;

	jQuery.ajax( {
		"type" : "POST",
		"data" : { "pregrado" : strNombre  , "action" : "insert_pregrado" },
		"dataType" : "json",
		"url" : "includes/async/pregrado.async.php" ,
		
		"beforeSend" : function(){
			
			$( "#volatilnotif" ).html( "Obteniendo Informaci&oacute;n ..." );
			
			$('html,body').animate({scrollTop: 0 }, 500);
			
			addNotify();
		},


		
		"success" : function( data ){
			
			data = data.column; 
			idpregrado = data.IDPregrado;
			//console.log("success" + idpregrado);
			$("#IDPregrado").val( idpregrado );
			//agregar validacion de campos
			agregar_pregrado_oferta(idpregrado, $("#FechaFinPregrado").val() );
			$(".inputpregrado").val("");

			//alert(idpregrado);

			
		},

		"error" : function( data ){
			alert("error");
		}

	});

	
}

//funcion para agregar una carrera de pregrado que no exista
function agregar_posgrado(strNombre)
{
	var idpregrado;

	jQuery.ajax( {
		"type" : "POST",
		"data" : { "posgrado" : strNombre  , "action" : "insert_posgrado" },
		"dataType" : "json",
		"url" : "includes/async/posgrado.async.php" ,
		
		"beforeSend" : function(){
			
			$( "#volatilnotif" ).html( "Obteniendo Informaci&oacute;n ..." );
			
			$('html,body').animate({scrollTop: 0 }, 500);
			
			addNotify();
		},


		
		"success" : function( data ){
			
			data = data.column; 
			idposgrado = data.IDPosgrado;
			//console.log("success" + idpregrado);
			$("#IDPosgrado").val( idposgrado );
			//agregar validacion de campos
			agregar_posgrado_oferta(idposgrado, $("#FechaFinPosgrado").val() );
			$(".inputposgrado").val("");

			//alert(idpregrado);

			
		},

		"error" : function( data ){
			alert("error");
		}

	});

	
}


//funciona para agregar un pregrado a una oferta
function agregar_pregrado_oferta(pregrado, fechafin)
{
	jQuery.ajax( {
		"type" : "POST",
		"data" : { "IDPregrado" : pregrado, "FechaFin" : fechafin , "action" : "insert_pregrado" },
		"dataType" : "json",
		"url" : "includes/async/oferta.async.php" ,
		
		"beforeSend" : function(){
			
			$( "#volatilnotif" ).html( "Obteniendo Informaci&oacute;n ..." );
			
			$('html,body').animate({scrollTop: 0 }, 500);
			
			addNotify();
		},


		"error" : function( data ){
			alert("error");
		},
		"success" : function( data ){
			
			data = data.column; 
			if( data.insertok === "true" )
			{
			
				set_row_pregrado( data );
				
			}//end if
			else
			{
				alert("Error");
			}//end else
			
		}
	});
	
	return false;
}//end fucntion



//funciona para agregar un pregrado a una oferta
function eliminar_pregrado_oferta(pregrado)
{
	jQuery.ajax( {
		"type" : "POST",
		"data" : { "IDPregrado" : pregrado,  "action" : "eliminar_pregrado" },
		"dataType" : "json",
		"url" : "includes/async/oferta.async.php" ,
		
		"beforeSend" : function(){
			
			$( "#volatilnotif" ).html( "Obteniendo Informaci&oacute;n ..." );
			
			$('html,body').animate({scrollTop: 0 }, 500);
			
			addNotify();
		},


		"error" : function( data ){
			alert("error");
		},
		"success" : function( data ){
			
			data = data.column; 
			//quitar fila
			$( ".pre" + pregrado ).remove();
			
		}
	});
	
	return false;
}//end fucntion





//funciona para agregar un posgrado a una oferta
function agregar_posgrado_oferta(posgrado, fechafin)
{
	jQuery.ajax( {
		"type" : "POST",
		"data" : { "IDPosgrado" : posgrado, "FechaFin" : fechafin , "action" : "insert_posgrado" },
		"dataType" : "json",
		"url" : "includes/async/oferta.async.php" ,
		
		"beforeSend" : function(){
			
			$( "#volatilnotif" ).html( "Obteniendo Informaci&oacute;n ..." );
			
			$('html,body').animate({scrollTop: 0 }, 500);
			
			addNotify();
		},


		"error" : function( data ){
			alert("error");
		},
		"success" : function( data ){
			
			data = data.column; 
			if( data.insertok === "true" )
			{
			
				set_row_posgrado( data );
				
			}//end if
			else
			{
				alert("Error");
			}//end else
			
		}
	});
	
	return false;
}//end fucntion



//funciona para agregar un posgrado a una oferta
function eliminar_posgrado_oferta(posgrado)
{
	jQuery.ajax( {
		"type" : "POST",
		"data" : { "IDPosgrado" : posgrado,  "action" : "eliminar_posgrado" },
		"dataType" : "json",
		"url" : "includes/async/oferta.async.php" ,
		
		"beforeSend" : function(){
			
			$( "#volatilnotif" ).html( "Obteniendo Informaci&oacute;n ..." );
			
			$('html,body').animate({scrollTop: 0 }, 500);
			
			addNotify();
		},


		"error" : function( data ){
			alert("error");
		},
		"success" : function( data ){
			
			data = data.column; 
			//quitar fila
			$( ".pre" + pregrado ).remove();
			
		}
	});
	
	return false;
}//end fucntion



function guardaEvento(  )
{
	var myforminputs = $( "#frmevento input, #frmevento select, #frmevento textarea" ).get();
	var params = {};
	var tmpname = "";
	var myfield;

	for( var i = 0 ; i < myforminputs.length ; i++ )
	{
		myfield = $( myforminputs[i] );

		if( myfield.attr( "type" ) != "submit" )
		{
			params[ myfield.attr( "name" ) ] = myfield.val();
			
		}
	}	
	
	params[ "action" ] = "insert";
	
	//return false;
	jQuery.ajax( {
		"type" : "POST",
		"data" : params,
		"dataType" : "json",
		"url" : "includes/evento/evento.async.php" ,
		
		"beforeSend" : function(){
			addNotify();
		},
		 
		"success" : function( data ){
			data = data.column; 
			
			$( "#volatilnotif" ).html( "Evento Guardado!!" );
	
			setTimeout( function(){
				$( "#volatilnotif" ).fadeOut( "slow" );
			} , 700);
			
			location.href="?mod=CrearEvento&step=cotizacion&m=eventocreado";
			
			
			
		}
	});
	return false;
}

function guardaClienteEvento(  )
{
	var myforminputs = $( ".formCliente input, .formCliente select" ).get();
	var params = {};
	var tmpname = "";
	var myfield;

	for( var i = 0 ; i < myforminputs.length ; i++ )
	{
		myfield = $( myforminputs[i] );

		if( myfield.attr( "type" ) != "submit" )
		{
			params[ myfield.attr( "name" ) ] = myfield.val();
			
		}
	}	
	
	params[ "action" ] = "insert";
	
	//return false;
	jQuery.ajax( {
		"type" : "POST",
		"data" : params,
		"dataType" : "json",
		"url" : "includes/cliente/cliente.async.php" ,
		
		"beforeSend" : function(){
			addNotify();
		},
		 
		"success" : function( data ){
			data = data.column; 
			
			set_row_cliente( data );
			
			preparaContactoAnunciante();
			preparaforms();
			
			
			
		}
	});
	return false;
}

function set_row_pregrado( data )
{
	var html = '';
	html += '<li class="pre' + data.IDPregrado + '"><i class="fa fa-plus-circle"></i>' + data.Nombre + '<a href="#" class="btndeletePregrado" rel="' + data.IDPregrado + '">eliminar</a></li>';
	
					
	$( ".contentPregrado" ).append( html );
	
	
	preparamarket();
	
	$( "#volatilnotif" ).html( "Carrera de Pregrado Agregado!!" );
	
	setTimeout( function(){
		$( "#volatilnotif" ).fadeOut( "slow" );
	} , 700);
}//end function

function set_row_posgrado( data )
{
	var html = '';
	html += '<li class="pre' + data.IDPosgrado + '"><i class="fa fa-plus-circle"></i>' + data.Nombre + '<a href="#" class="btndeletePosgrado" rel="' + data.IDPosgrado + '">eliminar</a></li>';
	
					
	$( ".contentPosgrado" ).append( html );
	
	
	preparamarket();
	
	$( "#volatilnotif" ).html( "Carrera de Posgrado Agregado!!" );
	
	setTimeout( function(){
		$( "#volatilnotif" ).fadeOut( "slow" );
	} , 700);
}//end function
