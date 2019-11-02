<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
<title><?php echo $view_title?></title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo "boostrap.css" ?>' type='text/css'>

</head>
<body>
	<?php require_once("oj-header.php");?>
<div class="signup-form">
    <form action="register.php" method="post">
		<h2>Regístrate</h2>
        <div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-user"></i></span>
				<input type="text" class="form-control" name="user_id" placeholder="Usuario" required="required">
			</div>
		</div>

		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-user"></i></span>
				<input type="text" class="form-control" name="name" placeholder="Nombre(s)" required="required">
			</div>
		</div>

		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-user"></i></span>
				<input type="text" class="form-control" name="lastname" placeholder="Apellidos" required="required">
			</div>
		</div>
		
        <div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-paper-plane"></i></span>
				<input type="email" class="form-control" name="email" placeholder="Correo electrónico" required="required">
			</div>
        </div>

        <div class="form-group">
        	<div class="input-group">
        		<label><?php echo $MSG_COUNTRY?>:</label>
        		<select id="pais" name="pais">
        			<?php include "pais.php"; ?>
        		</select>
        	</div>
        </div>

        <div class="form-group">
        	<div class="input-group">
        		<label>Estudias en:</label>
        		<div id='obi-option'>
        			<input type="radio" class="form-check-input" name="obi" id="uni" value= "0" required> Universidad<br>
        			<input type="radio" class="form-check-input" name="obi" id="obi" value= "1"> Colegio<br>
        		</div>
        	</div>
        </div>

        <div class="form-group">
        	<div class="input-group">
        		<label><?php echo $MSG_INSTITUTE?>:</label>
        		<div id="institution-div"  style="display: inline;">
        			<input id="institution" name="institution" type="text" autocomplete="off" placeholder="Escriba el nombre de su colegio"required>*<br>
        		</div>
        	</div>
        </div>

        <div class="form-group">
        	<div class="input-group">
        		<input id="institution_id" name="institution_id" type="hidden" placeholder="Escriba el nombre de su colegio">
        		<div id='master-institution'>
        			<div id="sug-institution"></div>
        		</div>
        	</div>
        </div>

		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-lock"></i></span>
				<input type="password" class="form-control" name="password" placeholder="Contraseña" required="required">
			</div>
        </div>
		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon">
					<i class="fa fa-lock"></i>
					<i class="fa fa-check"></i>
				</span>
				<input type="password" class="form-control" name="rptpassword" placeholder="Repite tu Contraseña" required="required">
			</div>
        </div>        
		<div class="form-group">
            <button type="submit" class="btn btn-block btn-lg">Registrar</button>
        </div>
    </form>
	<div class="text-center">Tienes cuenta? <a href="/loginpage.php">Ingresar</a>.</div>
</div>
</body>
<script type="text/javascript">
	$(document).ready(function(){
		$("#pais").val("0").change();
		$("#institution-div").empty();
		$("#obi-option").hide();
		$("#pais").change(function(){
			var pais = $("#pais").find("option:selected").val();

			var parametros = {
				"pais" : pais
			};

			if(pais == '26'){
				$("#obi-option").show();

				$("#obi").click(function(){
					$("#institution-div").empty();
					$("#institucion_uni").remove();
					$("#institution-div").html("<input id=institution name=institution size=30 type=text required autocomplete=off placeholder='Escriba el nombre de su colegio'>*<br>");
				});
				$("#uni").click(function(){
					$("#institucion_uni").remove();
					$("#institution-div").empty();
					cambio_uni();
				});
			}else{
				$("#obi-option").hide();
				$("#institution-div").empty();
				cambio_uni();
			}
		});
	});


	function cambio_uni(){
		$.ajax({
			type : "POST",
			url  : "institucion_uni.php",
			data : 'key='+$("#pais").find("option:selected").val(),
			beforeSend: function(){
				$("#institution_uni").css("background","#FFF url(LoaderIcon.gif) no-repeat 117px");
			},
			success: function(data){
				$("#institution-div").html(data);
			}
		});
	}

	function selectCountry(val,id) {
		$("#institution").val(val);
		$("#sug-institution").hide();
		$("#institution_id").val(id);
	}
</script>
</html>                            