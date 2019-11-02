$(function(){	
    
    	$(" #bot ").click(function() {

           // alert("adentro");
             //$('#result').html( '<img src="'+ global_data.theme +'images/load16.gif" alt="procesando" title="procesando" />' );
             var dataString = 'ajax=mandar&contenido=' + $('#contenido').val();
             $.ajax({
                type: 'POST',
                url: 'chat.php',
                data: dataString,
                success: function( data ) {                    
                    $('#historial').html( data );
                    $('#contenido').val('');
                }
             });//fin ajax	*/  
        });
});