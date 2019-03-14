<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
	    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
		<script src='http://www.google.com/recaptcha/api.js'></script>
	</head>
	<body>
		<div id="wrapper">
			<?php require_once("oj-header.php");?>
			<section id="main">
				<div id="registro">
					<legend><?php echo $MSG_REG_INFO?></legend>
					<form method="post" action="register.php" id="formulario" onsubmit="return captcha();">
					</p>
					<label><?php echo $MSG_USER_ID?>:</label>
					<input name="user_id" type="text" size="20"><br>

					<label><?php echo $MSG_NICK?>:</label>
					<input name="name" type="text" size="20"><br>

					<label><?php echo $MSG_LASTNAME?>:</label>
					<input name="lastname" type="text"><br>

					<label><?php echo $MSG_EMAIL?>:</label>
					<input name="email" size="30" type="email" required>*<br>

					<label><?php echo $MSG_COUNTRY?>:</label>
					<select id="pais" name="pais">
						<?php include "pais.php"; ?>
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
					<input name="password" size="20" type="password" required>*<br>

					<label><?php echo $MSG_REPEAT_PASSWORD?>:</label>
					<input name="rptpassword" size=20 type="password" required>*<br>
					<?php $ip =  $_SERVER['REMOTE_ADDR'];
					 $v_ip =explode(".",$ip);

 $OJ_VCODE=0;
						?>
												<?php if($OJ_VCODE){?>
																	<div id="csscapt" >	  
																							<div class="g-recaptcha" data-sitekey="<?php echo $publickey?>"</div>
																												</div>
																																	<?php  }?>	
																																						<input type="submit" name="submit" id="submit" value="Crear">
																																											<input type="reset"  name="reset" id="reset" value="Reset">
																																															</form>
																																																		</div>
																																																				</section>
																																																					</div>

	<section id="foot">
		<?php require_once("oj-footer.php");?>
	</section>
</body>
</html>

	<script type="text/javascript">
		function captcha(){
					var formulario=document.getElementById("formulario");
							if(document.getElementById("recaptcha_response_field").value==""){
											alert("Escriba el texto debajo la imagen");
														return false;
													}else{
																	formulario.submit();
																				return true;
																			}
						}
			
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
									});

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

