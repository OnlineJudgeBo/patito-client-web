<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

	</head>
	<body>
		<div id="wrapper">
			<section id="main">
			</p>
			<label>Actualizar Informacion:</label><br>
			<div id="registro">
				<form action="modify.php" method="post">
<?php require_once("init.php");require_once('./include/set_post_key.php');?>
					<label><?php echo $MSG_USER_ID; ?>:</label>
					<input name="user_id" type="text" size="20" value="<?php echo $_SESSION['user_id']?>" disabled="disabled"><br>

					<label><?php echo $MSG_NICK?>:</label>
					<input name="name" type="text" size="20" value="<?php echo htmlspecialchars($row->nick)?>"><br>

					<label><?php echo $MSG_LASTNAME?>:</label>
					<input name="lastname" type="text" value="<?php echo htmlspecialchars($row->lastname)?>"><br>

					<label><?php echo $MSG_EMAIL?>:</label>
					<input name="email" size="30" type="email" required value="<?php echo htmlspecialchars($row->email)?>">*<br>

					<label><?php echo $MSG_COUNTRY?>:</label>
					<div id = "pais_container"></div>
					<select id="pais" name="pais">
						<?php echo $pais; ?>
					</select>*<br>
					<div id='obi-option'>
						<input type="radio" name="obi" id="uni" value="0"> Universidad<br>
						<input type="radio" name="obi" id="obi" value="1"> Colegio<br>
					</div>

					<label><?php echo $MSG_INSTITUTE?>:</label>
					<div id="institution-div"  style="display: inline;">
						<input id="institution" name="institution" type="text" required autocomplete="off">*<br>
					</div>
					<input id="institution_id" name="institution_id" type="hidden">
					<div id='master-institution'>
						<div id="sug-institution"></div>
					</div>

					<label><?php echo $MSG_PASSWORD?>:</label>
					<input name="opassword" size="20" type="password" required>*<br>

					<label>Nueva Contraseña :</label>
					<input name="npassword" size=20 type="password" ><br>

					<label>Repita Nueva Contraseña:</label>
					<input name="rptpassword" size=20 type="password" ><br>

					<?php if($OJ_VCODE){?>
					<div id="csscapt" >	  
						<div class="g-recaptcha" data-sitekey="<?php echo $publickey?>"</div>
					</div>
					<?php  }?>	
					<input type="submit" name="submit" id="submit" value="Crear">
					<input type="reset"  name="reset" id="reset" value="Reset">
				</form>
				<br>
				<a href=export_ac_code.php>Download All AC Source</a>
			</div>
		</section>
	</div>
</body>
</html>

<script type="text/javascript">
	$(document).ready(function(){
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
					$("#institution-div").html("<input id=institution name=institution size=30 type=text required autocomplete=off>*<br>");
					cambio_obi();
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

		var pais = '<?php echo $row->pais_id;?>';
		$("#pais option[value="+pais+"]").attr('selected', 'selected').parent().focus();
		$("#pais option").parent().change();
		
		var obi = '<?php echo $row->obi;?>';
		if(pais == 26 && obi == 1){
			$("input:radio[name=obi][value='"+obi+"']").click();
			var id     = '<?php echo $row_institucion->id_colegio; ?>';
			var nombre = "<?php echo $row_institucion->nombre ;?>";
			$("#institution").val(nombre);
			$("#institution_id").val("<?php echo $row_institucion->id_colegio ;?>");
		}else{
			if(pais == 26 && obi == 0){
				$("input:radio[name=obi][value='"+obi+"']").click();
			}
			$.when(cambio_uni()).done(function(a){
				var id   = '<?php echo $row_institucion->id_institucion; ?>';
				$("#institucion_uni option[value="+id+"]").attr('selected', 'selected').parent().focus();
				$("#institucion_uni option").parent().change();
			});
			
		}
	});
function cargar_paises(){
	$.ajax({
		type : "POST",
		url  : "institucion_obi.php",
		data : 'key='+$(this).val(),
		beforeSend: function(){
			$("#institution").css("background","#FFF url(LoaderIcon.gif) no-repeat 117px");
		},
		success: function(data){
			$("#sug-institution").show();
			$("#sug-institution").html(data);
			$("#institution").css("background","#FFF");
		}
	});
}

function cambio_obi(){
	$("#institution").keyup(function(){
		$.ajax({
			type : "POST",
			url  : "institucion_obi.php",
			data : 'key='+$(this).val(),
			beforeSend: function(){
				$("#institution").css("background","#FFF url(LoaderIcon.gif) no-repeat 117px");
			},
			success: function(data){
				$("#sug-institution").show();
				$("#sug-institution").html(data);
				$("#institution").css("background","#FFF");
			}
		});
	});
}
function cambio_uni(){
	return $.ajax({
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
