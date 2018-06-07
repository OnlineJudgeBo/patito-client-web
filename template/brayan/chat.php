<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	
	<script src="js/jquery-1.7.min.js"></script>
			<script src="js/ajax.js"></script>
		 <script type="text/javascript">
			 function mostrar(){
			 	var dataString = 'ajax=vacio';
	             $.ajax({
	                type: 'POST',
	                url: 'chat.php',
	                data: dataString,
	                success: function( data ) {                    
	                    $('#historial').html( data );
	                }
	             });//fin ajax	*/  
			 }
			 //window.load(mostra());
			// function obtener(){
			 	 setInterval(mostrar,100);
			// }

		 </script>
		<script type="text/javascript">
            $(window).load(function(){
                $("#historial").animate({ scrollTop: $(document).height() }, "fast");
                     return false;
           		 });
		</script>
</head>
<body>

			<div id="lado" >
					<div id="cont">
					<b>Bienvenido al chat patito</b>
					</div>
					
					<a href="#" id="icono">Chat</a>
					<div id="historial">
					</div>
					<fieldset>
				
					<textarea  id="contenido" placeholder="mensaje"></textarea><br />
					<button  id="bot">Enviar</button>
					</fieldset>
				
			</div>
		
	</body>
</html>