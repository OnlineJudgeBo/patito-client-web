function importarScript(src, type) {
    var s = document.createElement("script");
    s.src = src;
	s.type = type;
    document.querySelector("head").appendChild(s);
}
function importarLink(rel, href) {
    var s = document.createElement("link");
    s.rel=rel;
	s.href=href;
    document.querySelector("head").appendChild(s);
}

function load(){
	for (i = 0; i < arguments.length; i++) {
		switch (arguments[i]) {
		case "materialize":	
			importarScript("./util/materialize/materialize.min.js", "");
			importarLink("stylesheet",
						 "./util/materialize/materialize.min.css");
			importarLink("stylesheet",
						 "https://fonts.googleapis.com/icon?family=Material+Icons");
			break;
		case "react":
			document.write("<script src='./util/react/react.development.js'></script>");
			document.write("<script src='./util/react/react-dom.development.js'></script>");
			document.write("<script src='./util/react/babel.min.js'></script>");
			//importarScript("./util/react/react.development.js", "", callback);
			//importarScript("./util/react/react-dom.development.js", "", callback);
			//importarScript("./util/react/babel.min.js", "", callback);
			break;
		case "app":
			importarScript("./js/app.js", "text/babel");
			break;
		case "showdown":
			document.write("<script src='./util/showdown/showdown.min.js'></script>");
			document.write("<script>showdown.setOption('tables', 1);"+
						   "showdown.setOption('headerLevelStart', 3);"+
						   //"showdown.setOption('simpleLineBreaks', 1);"+
						   "showdown.setOption('emoji',1);"+
						   "showdown.setOption('literalMidWordUnderscores',0);"+
						   "showdown.setOption('literalMidWordAsterisks',0);</script>"+
						   "<style type='text/css'>strong,em{ font-weight: bold;}</style>");			
			break;
		case "mathjax":
			document.write("<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-MML-AM_CHTML' async></script>"+
						   "<script type='text/x-mathjax-config'>"+
						   "MathJax.Hub.Config({"+
						   "tex2jax: {inlineMath: [['$','$']]}"+
						   "});</script>");
			break;
		case "mathjs":
			document.write("<script language=javascript src='./util/mathjs/LaTeX_asc.js'></script>"+
						   "<script language=javascript src='./util/mathjs/LaTeX_acc.js'></script>"+
						   "<script language=javascript src='./util/mathjs/LaTeX_tok.js'></script>"+
						   "<script language=javascript src='./util/mathjs/LaTeX_dfa_comp.js'></script>"+
						   "<script language=javascript src='./util/mathjs/LaTeX_dfa_gen.js'></script>"+
						   "<script language=javascript src='./util/mathjs/LaTeX_symbols.js'></script>"+
						   "<script language=javascript src='./util/mathjs/LaTeX_functions.js'></script>"+
						   "<script language=javascript src='./util/mathjs/LaTeX_aliases.js'></script>"+
						   "<script language=javascript src='./util/mathjs/main.js'></script>");
			break;
		case "prism":
			document.write("<link href='./util/prism/prism.css' rel='stylesheet'/>"+
						   "<script src='./util/prism/prism.js'></script>");
			break;
		case "ace":
			document.write("<style type='text/css' media='screen'>"+
						   ".ace_editor { border: 1px solid lightgray;"+
						   "margin: auto; height: 200px; width: 80%; }"+
						   ".scrollmargin { height: 8px; text-align: center; }"+
						   "</style><script src='./util/ace/src-noconflict/ace.js'"+
						   "type='text/javascript' charset='utf-8'></script>");
			break;
		case "chartjs":
			document.write("<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js'></script>");
			document.write("<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js'></script>");
			break;
		}
	}
}


