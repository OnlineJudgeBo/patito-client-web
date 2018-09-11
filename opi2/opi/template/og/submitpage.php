<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<!--Let browser know website is optimized for mobile-->
		<!--<meta name="viewport" content="width=device-width, initial-scale=1.0"/>-->
		<link rel="stylesheet" href="./materialize/materialize.min.css">
		<script src="./materialize/materialize.min.js"></script>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="./react/react.development.js"></script>
		<script src="./react/react-dom.development.js"></script>
		<script src="./react/babel.min.js"></script>
		<script type="text/babel" src="./react/app.js"></script>
		<script type="text/babel">
		 <?php require_once("./init.php");?>
		 dat.getId="<?php echo getId();?>";
		 dat.getPid="<?php echo getPid();?>";
		 dat.getCid="<?php echo getCid();?>";
		 dat.getLangmask="<?php echo getLangmask();?>";
		 dat.cookieLastlang="<?php echo cookieLastlang();?>";
		 dat.viewsrc=<?php echo json_encode($view_src);?>;
		 dat.languageName=["<?php echo implode("\",\"",$language_name);?>"];
		 dat.PID=["<?php echo implode("\",\"",$PID);?>"];
		 function loadPag(){
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 ReactDOM.render(<Submit dat={dat} msg={msg} />, document.getElementById("submit"));
		 }
		 loadPag();
		</script>
		<title><?php echo $view_title?></title>
		<link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg"/>
		<style type="text/css" media="screen">
		 .ace_editor {
			 border: 1px solid lightgray;
			 margin: auto;
			 height: 200px;
			 width: 80%;
		 }
		 .scrollmargin {
			 height: 8px;
			 text-align: center;
		 }
		</style>
		<script src="./ace/src-noconflict/ace.js" type="text/javascript" charset="utf-8">
		</script>	
	</head>
	<body>
		<div id="submit">
			<div class="preloader-wrapper active">
				<div class="spinner-layer spinner-red-only">
					<div class="circle-clipper left">
						<div class="circle"></div>
					</div><div class="gap-patch">
						<div class="circle"></div>
					</div><div class="circle-clipper right">
						<div class="circle"></div>
					</div>
				</div>
			</div>
		</div>
		
	
				<script>
				 var sid=0;
				 var i=0;
				 var judge_result=[<?php
								   foreach($judge_result as $result){
									   echo "'$result',";
								   }
								   ?>''];

				 function print_result(solution_id){
					 sid=solution_id;
					 $("#out").load("status-ajax.php?tr=1&solution_id="+solution_id);

				 }

				 function fresh_result(solution_id){
					 sid=solution_id;
					 var xmlhttp;
					 if (window.XMLHttpRequest)
						 {// code for IE7+, Firefox, Chrome, Opera, Safari
							 xmlhttp=new XMLHttpRequest();
						 }
					 else
						 {// code for IE6, IE5
							 xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
						 }
					 xmlhttp.onreadystatechange=function()
					 {
						 if (xmlhttp.readyState==4 && xmlhttp.status==200)
							 {
								 var tb=window.document.getElementById('result');
								 var r=xmlhttp.responseText;
								 var ra=r.split(",");
								 //     alert(r);
								 //     alert(judge_result[r]);
								 var loader="<img width=18 src=image/loader.gif>";  
								 var tag="span";
								 if(ra[0]<4) tag="span disabled=true";
								 else tag="a";
								 tb.innerHTML="<"+tag+" href='reinfo.php?sid="+solution_id+"' class='badge badge-info' target=_blank>"+judge_result[ra[0]]+"</"+tag+">";
								 if(ra[0]<4)tb.innerHTML+=loader;
								 tb.innerHTML+="Memory:"+ra[1]+"kb&nbsp;&nbsp;";
								 tb.innerHTML+="Time:"+ra[2]+"ms";
								 if(ra[0]<4)
									 window.setTimeout("fresh_result("+solution_id+")",2000);
								 else
									 window.setTimeout("print_result("+solution_id+")",2000);
							 }
					 }
					 xmlhttp.open("GET","status-ajax.php?solution_id="+solution_id,true);
					 xmlhttp.send();
				 }


				 function getSID(){
					 var ofrm1 = document.getElementById("testRun").document;  
					 var ret="0";
					 if (ofrm1==undefined){
						 ofrm1 = document.getElementById("testRun").contentWindow.document;
						 var ff = ofrm1;
						 ret=ff.innerHTML;
					 }else{
						 var ie = document.frames["frame1"].document;
						 ret=ie.innerText;
					 }
					 return ret+"";
				 }

				 var count=0;
				 function do_submit(editor){

					 if(typeof(eAL) != "undefined"){   eAL.toggle("source");eAL.toggle("source");}

					 var mark="<?php echo isset($id)?'problem_id':'cid';?>";
					 var problem_id=document.getElementById(mark);

					 if(mark=="problem_id")
						 problem_id.value="<?php echo (isset($id)?$id:'');?>";
					 else    
						 problem_id.value='<?php if(isset($cid)) echo $cid?>';
					 document.getElementById("source").value=editor.getValue();
					 document.getElementById("frmSolution").target="_self";
					 document.getElementById("frmSolution").submit();
				 }

				 var  handler_interval;

				 function do_test_run(){ 
					 if( handler_interval) window.clearInterval( handler_interval);
					 var loader="<img width=18 src=image/loader.gif>";
					 var tb=window.document.getElementById('result');
					 tb.innerHTML=loader;
					 if(typeof(eAL) != "undefined"){   eAL.toggle("source");eAL.toggle("source");}


					 var mark="<?php echo isset($id)?'problem_id':'cid';?>";
					 var problem_id=document.getElementById(mark);
					 problem_id.value=0;
					 document.getElementById("frmSolution").target="testRun";
					 document.getElementById("frmSolution").submit();
					 document.getElementById("TestRun").disabled=true;
					 document.getElementById("Submit").disabled=true;
					 count=20;
					 handler_interval= window.setTimeout("resume();",1000);

				 }

				 function resume(){
					 count--;
					 var s=document.getElementById('Submit');
					 var t=document.getElementById('TestRun');
					 if(count<0){
						 s.disabled=false;
						 t.disabled=false; 
						 s.value="<?php echo $MSG_SUBMIT?>";
						 t.value="<?php echo $MSG_TR?>";
						 if( handler_interval) window.clearInterval( handler_interval);
					 }else{
						 s.value="<?php echo $MSG_SUBMIT?>("+count+")";
						 t.value="<?php echo $MSG_TR?>("+count+")";
						 window.setTimeout("resume();",1000);

					 }
				 }
				</script>		
	</body>
</html>
