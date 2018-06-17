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
		 <?php require_once("./init.php");
		 if(isset($_SESSION['administrator']) ||isset($_SESSION['problem_master_editor'])){
			 require_once("include/set_get_key.php");
			 echo "dat.getKey=\"".$_SESSION['getkey']."\";";
		 }
		 ?>
		 dat.getId="<?php echo getId();?>";
		 dat.getPid="<?php echo getPid();?>";
		 dat.langmask="<?php if(isset($langmask))echo $langmask; else echo getLangmask();?>";
		 dat.getCid="<?php echo getCid();?>";
		 dat.probPid="<?php echo $row->problem_id;?>";
		 dat.probTitle="<?php echo $row->title;?>";
		 dat.probTime="<?php echo $row->time_limit;?>";
		 dat.probMem="<?php echo $row->memory_limit;?>";
		 dat.probSubmit="<?php echo $row->submit?>";
		 dat.probAc="<?php echo $row->accepted;?>";
		 dat.probSpj="<?php echo $row->spj;?>";
		 dat.probDes=<?php echo json_encode($row->description);?>;                 
         dat.probInput=<?php echo json_encode($row->input);?>;                     
         dat.probOutput=<?php echo json_encode($row->output);?>;                   
         dat.probSinput=<?php echo json_encode($row->sample_input);?>;                        
         dat.probSoutput=<?php echo json_encode($row->sample_output);?>;                      
         dat.probHint=<?php echo json_encode($row->hint);?>;   
		 dat.probSource="<?php echo $row->source;?>";
		 dat.PID=["<?php echo implode("\",\"",$PID);?>"];		 
		 function loadPag(){
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 ReactDOM.render(<Problem dat={dat} msg={msg} />,
							 document.getElementById("problem"));
		 }
		 loadPag();
		</script>
		<title><?php echo $view_title?></title>
	    <link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg"/>
        <script type="text/javascript" async
					  src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-MML-AM_CHTML" async>
		</script>
		<script type="text/x-mathjax-config">
		 MathJax.Hub.Config({
			 tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
		 });
		</script>
		<script src="./showdown/showdown.min.js"></script>
		<link rel="next" href="submitpage.php?
				   <?php
				   if ($pr_flag){echo "id=$id";
				   }else{echo "cid=$cid&pid=$pid&langmask=$langmask";}
				   ?>">
		<?php 
		if (isset($id)) echo "<title>$MSG_PROBLEM $row->problem_id. -- $row->title</title>";
		else echo "<title>$MSG_PROBLEM :$PID[$pid]: $row->title </title>";
		?>
	</head>
	<body>
		<div id="problem">
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
	</body>
</html>
