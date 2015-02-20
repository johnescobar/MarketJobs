/**
*Procedimientos y funciones de uso general
*
*/
var nav4 = window.event ? true : false;
jQuery( document ).ready(function(){

	$( "form.formvalida" ).submit(function(){
		
		$(".money").each(function(){
		  $(this).val( getNum( $( this ).val() ) );
		});

		return EvaluaReg( this );
	});

	$(".btnEnviar").click(function(){
		var form = $(this).attr("rel");
		$( "#" + form ).submit();
	});

	$("#btnSkip").click(function(){
		var page = $(this).attr("rel");
		location.href = page;
	});

	$(".btnLoginBox").colorbox({iframe:true, width:"490", height:"292"});

	
	$("select#dataBeneficiario").change(function(){
		$( "#frmSelectBeneficiario" ).submit();
	});

	//Carro de Compras
	preparacarro();


});

//Comportamientos para el carro de compras de la vaina
function preparacarro()
{
	var cantidad = 0;
	var meses = 0;
	var producto = 0;

	$(".btnComprar").click( function(){
		cart_agregar( $(this) );
		return false;
	} );

	cart_show();

	$(".btn-cerrar").click( function(){
		$( ".carrobox" ).hide();
		return false;
	} );

	$(".btn_showCart").click( function(){
		$( ".carrobox" ).toggle(300);
		return false;
	} );

	$(".btnEliminaCart").click( function(){
		cart_eliminar( $(this) );
		return false;
	} );

	$(".selectUnidades").change(function(){
		producto = $(this).attr("rel");
		cantidad = $("#cantidad" + producto).val();
		meses = $("#meses" + producto).val();

		if( !meses )
			meses = 1;


		cart_update( producto, cantidad, meses );

	});

	$(".btnTransporte").click(function(){
		var checked = $('input[name=Transporte]:checked', '#frmCheckout').val();
		
		if( checked === "S" )
		{
			console.log( "si entro" );
			$("#detVTotal").html( $("#VTotalTransporte").val() );
			$("#contentTransporte").show();
		}//end if
		else
		{
			console.log( "no entro" );
			$("#detVTotal").html( $("#VTotal").val() );
			$("#contentTransporte").hide();


		}//end else

		//VTotalTransporte

	});



}//end function

//agregar producto al carro
function cart_agregar( obj )
{
	var params = {};
	params["oper"] = "add";
	params["IDProducto"] = obj.attr( "rel" );
	params["Tipo"] = obj.attr( "rev" );
	params["Cantidad"] = 1;
	params["Meses"] = 1;

	$.ajax( {
		type : "POST",
		data : params,
		dataType : "json",
		url : "async/cart.async.php" ,
		
		beforeSend : function(){
			
			//$( ".a-growl .a-social-post p" ).html( "Cargando Insumo" );
			//$('html,body').animate({scrollTop: 0 }, 500);
			//addNotify();
		},
		 
		success : function( data ){
			$( ".agregart" ).html( "Acabas de agregar el artículo <span> " + data.Nombre + "</span>" );
			cart_show();
			$( ".carrobox" ).show( 300 );

			$("#btnCarro").addClass("btn_showCart");
			preparacarro();
			
			
		},
		error: function(jqXHR, textStatus, errorThrown){
		},
		complete: function(jqXHR, textStatus){
		}
	});
}//end function

//actualizar el carro
function cart_show( )
{
	var params = {};
	var html = "";
	var popCart = $(".contentPopCart");
	params["oper"] = "show";

	$.ajax( {
		type : "POST",
		data : params,
		dataType : "json",
		url : "async/cart.async.php" ,
		
		beforeSend : function(){
			



			//$( ".a-growl .a-social-post p" ).html( "Cargando Insumo" );
			//$('html,body').animate({scrollTop: 0 }, 500);
			//addNotify();
		},
		 
		success : function( data ){
			$.each(data.productos, function( index, value ) {
		
					html += "<li>";
	                html += "<div class=\"boxprodimg\"><img src=\"" + value.FotoCart + "\"></div>";
	                html += "<div class=\"boxproddet\">";
	                html += "<span>" + value.Nombre + " <br></span>";
	                html += value.Cantidad + " " + value.Unidad;
	                html += "</div>";
	                html += "<div class=\"boxprodval pull-right\">$" + value.Total + "</div>";
	            	html += "</li>";

			});

			popCart.html( html );

			$(".itenum").html( "<span>" + data.Items + "  " + data.strItems + " </span>en el carrito" );
			$(".itemsubt").html( "<span>Total</span> $ " + data.VTotal );
			$(".contentNumCart").html("(" + data.Items + ")");
			
		},
		error: function(jqXHR, textStatus, errorThrown){
		},
		complete: function(jqXHR, textStatus){
		}
	});
}//end function


//eliminar producto al carro
function cart_eliminar( obj )
{
	var params = {};
	var row = "";
	params["oper"] = "del";
	params["IDProducto"] = obj.attr( "rev" );

	row = $( "#" + obj.attr( "rel" ) )

	$.ajax( {
		type : "POST",
		data : params,
		dataType : "json",
		url : "async/cart.async.php" ,
		
		beforeSend : function(){
			
			//$( ".a-growl .a-social-post p" ).html( "Cargando Insumo" );
			//$('html,body').animate({scrollTop: 0 }, 500);
			//addNotify();
		},
		 
		success : function( data ){
			$( ".agregart" ).html( "Acabas de eliminar el artículo <span> " + data.Nombre + "</span>" );
			cart_show();
			actualizar_viewcart();
			preparacarro();

			row.remove();
			
			
		},
		error: function(jqXHR, textStatus, errorThrown){
		},
		complete: function(jqXHR, textStatus){
		}
	});
}//end function

function actualizar_viewcart(){

    var params = {};
    var html = "";
    params["oper"] = "show";

    $.ajax( {
        type : "POST",
        data : params,
        dataType : "json",
        url : "async/cart.async.php" ,
        
        beforeSend : function(){
            



            //$( ".a-growl .a-social-post p" ).html( "Cargando Insumo" );
            //$('html,body').animate({scrollTop: 0 }, 500);
            //addNotify();
        },
         
        success : function( data ){

            $("#detSubTotal").html( "$" + data.SubTotal  );
            $("#detTransporte").html( "$" + data.Transporte  );
            $("#detTotalIVA").html( "$" + data.TotalIVA  );

            //verificar si el cliente está incluyendo el transporte
            var checked = $('input[name=Transporte]:checked', '#frmCheckout').val();
            if( checked === "S" )
            {
            	$("#detVTotal").html( "$" + data.VTotalTransporte  );
            }//end if
            else
            {
            	$("#detVTotal").html( "$" + data.VTotal  );
            }//end if

            $("#detTotalAbajo").html( "TOTAL $" + data.VTotal  );

            //$("#rowCart" + data.IDProducto + " .priceval").html( "TOTAL $" + data.VTotal  );

            //los hiden de total con y sin transporte
            $("#VTotalTransporte").val( data.VTotalTransporte  );
            $("#VTotal").val( data.VTotal  );


            if( data.Items == 0 )
            	location.href = "productos.php";
            
        },
        error: function(jqXHR, textStatus, errorThrown){
        },
        complete: function(jqXHR, textStatus){
        }
    });

}//end function

//agregar producto al carro
function cart_update( producto, cantidad, meses )
{
	var params = {};
	params["oper"] = "update";
	params["IDProducto"] = producto;
	params["Cantidad"] = cantidad;
	params["Meses"] = meses;

	$.ajax( {
		type : "POST",
		data : params,
		dataType : "json",
		url : "async/cart.async.php" ,
		
		beforeSend : function(){
			
			//$( ".a-growl .a-social-post p" ).html( "Cargando Insumo" );
			//$('html,body').animate({scrollTop: 0 }, 500);
			//addNotify();
		},
		 
		success : function( data ){
			$( ".agregart" ).html( "Acabas de actualizar el artículo <span> " + data.Nombre + "</span>" );
			
			$("#rowCart" + data.IDProducto + " .priceval").html( "$" + data.ValorTotalProducto );


			cart_show();
			preparacarro();
			actualizar_viewcart();
			
		},
		error: function(jqXHR, textStatus, errorThrown){
		},
		complete: function(jqXHR, textStatus){
		}
	});
}//end function


