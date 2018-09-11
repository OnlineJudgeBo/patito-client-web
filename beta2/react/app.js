class Footer extends React.Component{
	render(){
		var a = new Date();
		var t = a.getHours()*3600+a.getMinutes()*60+a.getSeconds();
		var frm=[{s:1, t:""},{s:60, t:":"}, {s:3600, t:":"}];
		return (
			
			<div className={"footer-copyright z-depth-2"+this.props.dat.st3[localStorage.getItem("skin")]}
				 style={{padding:"25 0 25 0"}}>
			  <div className="container">
				Bienvenido {this.props.dat.user_id} al Juez Virtual de la Universidad Mayor de San Andrés
				<div className="right"><Timer start={t} frmt={frm} flag={1}/></div>
			  </div>
			</div>
		);
	}
}


class Menu extends React.Component {
	constructor(props){
		super(props);
		this.state={items:[]};
		for(var i=0; i<props.items.length; i++) this.state.items.push(props.items[i]);
		this.handleClick = this.handleClick.bind(this);
	}
	handleClick(e, href, icn){
		console.log("Click", e, this.props.items, this.props.itemsUser, this.state.items);
		if(icn=="brightness_4")
			localStorage.setItem("skin", 1-localStorage.getItem("skin"));			
		else if(icn=="details"){
			while(this.state.items.length>0) this.state.items.pop();
			for(var i=0; i<this.props.itemsUser.length; i++)
				this.state.items.push(this.props.itemsUser[i]);
		}else{
			while(this.state.items.length>0) this.state.items.pop();
			for(var i=0; i<this.props.items.length; i++)
				this.state.items.push(this.props.items[i]);
		}
		loadPag();
	}
	render() {
		return (
			<ul id="nav-mobile" className="right">
			  {this.state.items.map(item => (
				  <li key={item.href+item.iName} onClick={(e)=>this.handleClick(e,item.href, item.iName)}>
					<a href={item.href} className={this.props.dat.tx3[localStorage.getItem("skin")]}>{item.text}
					  <i className={"material-icons "+item.iPos} style={{fontSize:+this.props.dat.px}}>{item.iName}</i>
					</a>
				  </li>
			  ))}
			</ul>
		);
	}
}

class Header extends React.Component{
	constructor(props){
		super(props);
		this.state = {
			menu:[
				{href:"problemset.php", text:this.props.msg.problems,
				 iPos:"left", iName:"view_comfy"},
				{href:"status.php", text:this.props.msg.status,
				 iPos:"left", iName:"clear_all"},
				{href:"ranklist.php", text:this.props.msg.ranklist,
				 iPos:"left", iName:"equalizer"},
				{href:"contest.php", text:this.props.msg.contest,
				 iPos:"left", iName:"view_headline"},
				{href:"faqs.php", text:this.props.msg.faq,
				 iPos:"left", iName:"question_answer"}],
			menuUser:[
				{href: "./modifypage.php",
				 text: this.props.msg.userInfo,
				 iPos: "left", iName: "edit"},
				{href: "./userinfo.php?user="+this.props.dat.user_id,
				 text: this.props.dat.user_id,
				 iPos: "left", iName: "person"},
				{href: "./mail.php",
				 text: this.props.dat.mail,
				 iPos: "left", iName: "mail"},
				{href: "./status.php?user="+this.props.dat.user_id,
				 text: "Reciente",
				 iPos: "left", iName: "send"}],
			text:'',
			aux:[]
		};
		console.log(this.props.dat.user_id);
		if(this.props.dat.user_id!=""){
			this.state.menu.push({
				href: "./userinfo.php?user="+this.props.dat.user_id,
				text: this.props.dat.user_id,
				iPos: "left", iName: "account_box"
			});
			if(this.props.dat.mail>0)
				this.state.menu.push({
  					href: "./mail.php", text: this.props.dat.mail,
  					iPos: "left", iName: "mail"
  				});
			this.state.menu.push({href: "#", text: "",iPos: "", iName: "brightness_4"},
								 {href: "#", text: "",iPos: "", iName: "details"});
		}else{
			this.state.menu.push({href: "#", text: "",iPos: "", iName: "brightness_4"},
								 {href: "./loginpage.php", text: "Ingresar",
								  iPos: "left", iName: "transfer_within_a_station"});
		}
		if(this.props.dat.admin ||
		   this.props.dat.contestCreator ||
		   this.props.dat.problemEditor ||
		   this.props.dat.problemME)
			this.state.menuUser.push({
  				href: "./admin", text: this.props.msg.admin,
  				iPos: "left", iName: "build"
  			});
		this.state.menuUser.push({href: "./logout.php", text: this.props.msg.logout,
  								  iPos: "left", iName: "exit_to_app"},
								 {href: "#", text: "",iPos: "", iName: "arrow_upwards"});
		this.props.dat.px=24;
		/*if(this.props.dat.screenWidth==2){
		  for(var i=0; i<this.state.menu.length; i++)
		  this.state.menu[i].text="";					
		  for(var i=0; i<this.state.menu.length; i++)
		  this.state.menu[i].iPos="";
		  for(var i=0; i<this.state.menuUser.length; i++)
		  this.state.menuUser[i].text="";					
		  for(var i=0; i<this.state.menuUser.length; i++)
		  this.state.menuUser[i].iPos="";
		  this.props.dat.px=7;
		  }*/
	}
	render(){
		return (
			<div className="navbar-fixed">
			  <nav className={"nav-wrapper z-depth-2 "+this.props.dat.bg3[localStorage.getItem("skin")]}>
				<a href={this.props.dat.oj_home}>
				  <img src="template/og/image/juez-patito2.svg" style={{height:100/this.props.dat.screenWidth}}/>
				</a>
				<Menu dat={this.props.dat} msg={this.props.msg} items={this.state.menu} itemsUser={this.state.menuUser} aux={this.state.aux}/>
			  </nav>
			</div>
		);
	}
}

function format(sec, fun){
	var a="";
	for(var i=(fun.length)-1; i>=0; i--){				
		a=a+Math.trunc(sec/fun[i].s)+fun[i].t+" ";
		sec=(sec%fun[i].s);
	}
	return a;
}

class Timer extends React.Component {
	constructor(props) {
		super(props);
		this.state = { seconds: this.props.start,
					   timeFormat: format(this.props.start, this.props.frmt)};			
	}
	tick() {
		this.setState(prevState => ({
			seconds: prevState.seconds + this.props.flag,			
			timeFormat: format(this.state.seconds, this.props.frmt)
		}));
	}
	componentDidMount() {
		this.interval = setInterval(() => this.tick(), 1000);
	}
	componentWillUnmount() {
		clearInterval(this.interval);
	}
	render() {
		return (<span>{this.state.timeFormat}</span>);
	}
}

class Labelcontesttime extends React.Component{
	constructor(props){
		super(props);
		this.state={
			start:new Date((new Date(this.props.start)).getTime()),
			end:new Date((new Date(this.props.end)).getTime()),
			now:new Date((new Date(this.props.now)).getTime())//+1000*60*60*4)
		};
	}
	render(){
		const ele=(<div>Inicio:{this.state.start.toLocaleString()}<br/>
				   Fin:{this.state.end.toLocaleString()}</div>);
		var frm=[{s:1, t:"s"},{s:60, t:"m"}, {s:3600, t:"h"}, {s:90000, t:"d"}];		
		if(this.state.now<this.state.start)
			return (<div>							
					Empieza en:<Timer
					start={(this.state.start.getTime()-this.state.now.getTime())/1000}
					frmt={frm}
					flag={-1}/>
					{ele}</div>);
		else
			return (<div>
					Corriendo:<Timer
					start={(this.state.now.getTime()-this.state.start.getTime())/1000}
					frmt={frm}
					flag={1}/>
					Termina:<Timer
					start={(this.state.end.getTime()-this.state.now.getTime())/1000}
					frmt={frm}
					flag={-1}/>
					{ele}</div>);
	}
}
class Labelcontestlist extends React.Component{
	constructor(props){
		super(props);
		console.log(props);
		this.state={
			start:new Date((new Date(this.props.start)).getTime()),
			end:new Date((new Date(this.props.end)).getTime()),
			now:new Date((new Date(this.props.now)).getTime()+1000*60*60*4)
		};
	}
	render(){						
		var frm=[{s:1, t:"s"},{s:60, t:"m"}, {s:3600, t:"h"}, {s:90000, t:"d"}];		
		if(this.state.now<this.state.start)
			return (
				<div
				  className="center-align hoverable"
				  style={{
					  borderBottom:"3px solid "+this.props.dat.cl2[0],
					  borderRight:'3px solid '+this.props.dat.cl2[0],
					  borderLeft:'3px solid '+this.props.dat.cl2[0],
					  borderRadius:'0px 0px 10px 10px'
				  }}
				  >
				  <h5
					className="green lighten-1"
					style={{padding:'10 20 15 20'}}>
					<a href={"contest.php?cid="+this.props.id} className={this.props.dat.tx1[localStorage.getItem("skin")]}>
					  {this.props.title}
					  <div className="secondary-content">
						<i className={"material-icons left-align"+this.props.dat.tx1[localStorage.getItem("skin")]}>send</i>
					  </div>
					  
					</a>
				  </h5>				
				  <Labelcontesttime start={this.props.start} end={this.props.end} now={this.props.now}/>					
				</div>
			);
		else
			return (
				<div className="center-align hoverable"
					 style={{
						 borderBottom:'3px solid '+this.props.dat.cl1[0],
						 borderRight:'3px solid '+this.props.dat.cl1[0],
						 borderLeft:'3px solid '+this.props.dat.cl1[0],
						 borderRadius:'0px 0px 10px 10px'
					 }}
					 >
				  <h5 className="red lighten-1"
					  style={{padding:'10 20 15 20'}}>
					<a href={"contest.php?cid="+this.props.id} className={this.props.dat.tx1[localStorage.getItem("skin")]}>
					  {this.props.title}
					  <div className="secondary-content">
						<i className={"material-icons left-align "+this.props.dat.tx1[localStorage.getItem("skin")]}>send</i>
					  </div>
					  
					</a>
				  </h5>
				  <Labelcontesttime start={this.props.start} end={this.props.end} now={this.props.now}/>
				</div>							
			);
	}
}
class Contestlist extends React.Component {
	constructor(props){
		super(props);
		this.state={items:this.props.list};
	}
	render() {		
		return (
			<div className={this.props.dat.bg2[localStorage.getItem("skin")]} >
			  <h4 className={this.props.dat.st4[localStorage.getItem("skin")]+" center-align"}
				  style={{paddingTop:5, paddingRight:20, paddingBottom:15, paddingLeft:20,borderRadius: 5}}>
				Concursos
			  </h4>			
			  {this.state.items.map(item => (				  
							   <Labelcontestlist
									 start={item.start}
									 end={item.end}
									 now={item.now}
									 dat={this.props.dat} id={item.id}
									 title={item.title}/>						
			  ))}
			</div>
		);
	}
}
//*******************************************INDEX****************************/
class Index extends React.Component {
	constructor(props){
		super(props);
	}
	render() {
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>				
			  <Header dat={dat} msg={msg}/>				
			  <div className="row">
				<div className="col s9 center-align" id="principal">
				  <h1>Juez Virtual</h1>
				  <h2>Universidad Mayor de San Andres</h2>						
				</div>
				<div className="col s3" id="contest-list">
				  <Contestlist dat={dat} list={listContest}/>
				</div>
			  </div>
			  <Footer dat={dat} msg={msg}/>
			</div>
		);
	}
}

//*******************************************LOGIN****************************/
class Login extends React.Component {
	constructor(props){
		super(props);
	}
	render() {
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>				
			  <Header dat={dat} msg={msg}/>
			  <br/><br/>
			  <form action="login.php" method="post">
				<div className="row">
				  <div className="col s10 center-align">
					<input id="password" name="user_id" type="text" className={"validate"+this.props.dat.tx1[localStorage.getItem("skin")]}/>
					<label htmlFor="password">{this.props.msg.userId}</label>
					<input id="password" name="password" type="password" className={"validate"+this.props.dat.tx1[localStorage.getItem("skin")]}/>
					<label htmlFor="password">Password</label>
				  </div>					
				  <div className="col s2 center-align">
					<input name="submit" type="submit" size="10" value="Ingresar" className={"btn waves-effect"+this.props.dat.st4[localStorage.getItem('skin')]} style={{padding:"15 10 15 10", height:"100px"}}/>
				  </div>
				</div>
				<div className="row">
				  <div className="col s10 offset-s1 center-align">
					<a href="lostpassword.php" className={"col s5 btn waves-effect "+ this.props.dat.st4[localStorage.getItem('skin')] }>
					  Recuperar contraseña
					</a>
					<a href="./registerpage.php" className={"col s5 offset-s2 btn waves-effect"+this.props.dat.st4[localStorage.getItem('skin')]}>
					  ¿No tienes usuario?
					</a>
					
				  </div>
				  
				</div>
			  </form>
			  <Footer dat={dat} msg={msg}/>
			</div>
		);
	}
}

/********************PROBLEMSET*****************/
class Tabla extends React.Component{
	constructor(props){
		super(props);
		this.state={order:0, table:this.props.tabla};
		console.log("taaaaaable",this.state.table);
	}
	handleClick(e, num){
		console.log("Click", e, num);
		console.log(this.state.table.body);
		console.log("State:",this.state);
		var aux = this.state.table;
		if(this.state.order==num) aux.body.rows.sort(function(a, b){
			if(typeof a.row[num].text == 'string') a = a.row[num].text.toUpperCase();
			else a = a.row[num].text;
			if(typeof b.row[num].text == 'string') b = b.row[num].text.toUpperCase();
			else b = b.row[num].text;
			if (a > b) return -1;
			if (a < b) return 1;
			return 0;
		});
		else aux.body.rows.sort(function(a, b){
			if(typeof a.row[num].text == 'string') a = a.row[num].text.toUpperCase();
			else a = a.row[num].text;
			if(typeof b.row[num].text == 'string') b = b.row[num].text.toUpperCase();
			else b = b.row[num].text;
			if (a < b) return -1;
			if (a > b) return 1;
			return 0;
		});
		if(this.state.order==num) this.setState({order:-1, table:aux});
		else this.setState({order:num, table:aux});
		console.log(this.state.table.body);
	}
	render(){
		return(				 
				<table className={"highlight"+this.props.dat.bg2[localStorage.getItem("skin")]}>
				<thead className={this.props.dat.bg4[localStorage.getItem("skin")]}>				<tr>
				{this.state.table.head.row.map((item,index)=>{
					return(
						<th>{item.text}<i className="material-icons left" onClick={(e)=>this.handleClick(e,index)}>{"swap_vert"}</i></th>
					);
				})}
			</tr>
				</thead>
				<tbody>

			{this.state.table.body.rows.map(item =>{
				return(
					<tr className={item.props.bgColor}>
					  {item.row.map(iitem=>{
						  return (
							  <td>
								<a href={iitem.link} className={"validate"+this.props.dat.tx1[localStorage.getItem("skin")]}><p dangerouslySetInnerHTML={{__html: iitem.text}}></p>
								</a>
							  </td>);
					  })}
					</tr>
				);
			})}

			
			</tbody>
				
			</table>
		);
	}
}
class Problemset extends React.Component {
	constructor(props){
		super(props);
		console.log(this.props.tabla);
	}
	render() {
		var arrpag=[];
		if(this.props.dat.page>1){
			arrpag.push({class:"waves-effect",
						 href:"problemset.php?page="+(this.props.dat.page-1),
						 text:"",
						 icon:"chevron_left"});
		}else{
			arrpag.push({class:"disabled",
						 href:"#!",
						 text:"",
						 icon:"chevron_left"});
		}
		for(var i=1; i<=this.props.dat.totalPage; i++){
			if(i==this.props.dat.page)
				arrpag.push({class:this.props.dat.bg3[localStorage.getItem("skin")],
							 href:"problemset.php?page="+i,
							 text:i});
			else arrpag.push({class:"waves-effect",
							  href:"problemset.php?page="+i,
							  text:i});
		}
		if(this.props.dat.page<this.props.dat.totalPage){
			arrpag.push({class:"waves-effect",
						 href:"problemset.php?page="+(this.props.dat.page+1),
						 text:"",
						 icon:"chevron_right"});
		}else{
			arrpag.push({class:"disabled",
						 href:"#!",
						 text:"",
						 icon:"chevron_right"});
		}
		const aux = (<ul className={"pagination center-align"}>
					 {arrpag.map(item => (
						 <li className={item.class} key={item.href+item.icon}>
						   <a href={item.href} className={this.props.dat.tx1[localStorage.getItem("skin")]}>
							 {item.text}<i className="material-icons">{item.icon}</i>
						   </a>
						 </li>
					 ))}
					 </ul>);
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>
			  <Header dat={dat} msg={msg}/>
			  <div style={{align:'center', width:'90%',
				   marginLeft:'auto', marginRight:'auto'}}>
				<h1 style={{textAlign:'center'}}
					className={this.props.dat.st4[localStorage.getItem("skin")]}>Conjunto de Problemas</h1>
				{aux}				
				<div className="row">					
				  <form action="problem.php">
					<div className="col s3 offset-s1">
					  <input placeholder="1006" id="problemId"
							 type="text"
							 className={"validate"+
							 this.props.dat.tx1[localStorage.getItem("skin")]} name="id"/>
					  <label htmlFor="problemId">ID del problema</label>
					</div>
					<button className={"btn waves-effect col s2"+
							this.props.dat.st4[localStorage.getItem("skin")]}type="submit">Ir
					  <i className="material-icons right">send</i>
					</button>
				  </form>					
				  <form>
					<div className="col s3">
					  <input placeholder="laberinto" id="search" type="text"
							 className={"validate"+
							 this.props.dat.tx1[localStorage.getItem("skin")]} name="search"/>
					  <label htmlFor="search">Palabra</label>
					</div>
					<button className={"btn waves-effect col s2"+
							this.props.dat.st4[localStorage.getItem("skin")]}
							type="submit">Buscar
					  <i className="material-icons right">search</i>
					</button>
				  </form>
				</div>
				<Tabla dat={dat} tabla={this.props.tabla}/>
			  </div>
			  <Footer dat={dat} msg={msg}/>
			  
			</div>
			
		);
	}
}

/**************************STATUS*****************************/

class Select extends React.Component{
	constructor(props) {
		super(props);
		this.handleChange = this.handleChange.bind(this);
		
	}
	handleChange(event) {
		if(this.props.onChange)
			document.getElementById(this.props.form).submit();
	}
	render(){
		var aux;
		if(this.props.todos)
			aux=(<option selected={this.props.selected==-1} value={-1} key={-1}>
				 {"Todos"}
				 </option>);
		return (
			<div>
			  <select className="browser-default"
					  name={this.props.name}
					  id={this.props.id}
					  onChange={this.handleChange}>
				{aux}
				{this.props.options.map((item , index)=> (
					<option value={index} key={index} selected={this.props.selected==index}>
					  {item}
					</option>
				))}
			</select>
				<label htmlFor={this.props.id}>{this.props.label}</label>
				</div>
		);
	}
}
class Status extends React.Component {
	constructor(props){
		super(props);
		//this.handleClick = this.handleClick.bind(this);
	}
	render(){
		var inputCid;
		if(this.props.dat.getCid!="")inputCid=(<input type='hidden' name='cid' value={dat.getCid}/>); // creo es envano notiene que
		var selectShowSim;
		if(this.props.dat.admin||this.props.dat.sourceBrowser){
			selectShowSim=(
				<div className="input-field col s2">
				  <Select name={"showsim"} id="Selshowsim"
						  selected={this.props.dat.getShowsim}
						  options={[10,50, 60, 70, 80, 90, 100]}
						  label={"Sim"}
						  form={"simform"}
						  />
				</div>
			);
		}
		var hrefAnterior="status.php?";
		if(this.props.dat.getPrevtop!=""){
			hrefAnterior+=this.props.dat.getGet+"&top="+this.props.dat.getPrevtop;
		}else{
			hrefAnterior+=this.props.dat.getGet+"&top="+(parseInt(this.props.dat.top)+20);
		}
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>			  
			  <Header dat={dat} msg={msg}/>
			  <div style={{align:'center', width:'90%',
				   marginLeft:'auto', marginRight:'auto'}}>
				<h1 style={{textAlign:'center'}}
					className={this.props.dat.st4[localStorage.getItem("skin")]}>Estado</h1>
				<div className="row">
				  <form id="simform" action="status.php" method="get">
					<div className="col s2">
					  <input placeholder="1006"
							 id="problemId"
							 type="text"
							 className={"validate"+this.props.dat.tx1[localStorage.getItem("skin")]}
							 name="problem_id"
							 defaultValue={this.props.dat.getProblemId}/>
					  <label htmlFor="problemId">Id del problema</label>
					</div>
					<div className="col s2">
					  <input placeholder="usuario"
							 id="userId"
							 type="text"
							 className={"validate"+this.props.dat.tx1[localStorage.getItem("skin")]}
							 name="user_id"
							 defaultValue={this.props.dat.getUserId}/>
					  <label htmlFor="userId">{this.props.msg.user}</label>
					</div>
					{inputCid}
					<div className="col s2">
					  <Select name={"language"} id={"Sellanguage"} 
							  selected={this.props.dat.getLanguage}
							  options={this.props.dat.languageName}
							  label={"Lenguaje"}
							  form={"simform"} todos={1} onChange={1}
							  />
					</div>					
					<div className="col s2">
					  <Select name={"jresult"} id={"Seljresult"}
							  selected={this.props.dat.getJresult}
							  options={this.props.dat.jresult}
							  label={"Resultado"}
							  form={"simform"} todos={1} onChange={1}
							  />
					</div>
					{selectShowSim}
					<button className={"btn waves-effect col s2"+
							this.props.dat.st4[localStorage.getItem("skin")]}
							type="submit">{this.props.msg.search}
					  <i className="material-icons right">search</i>
					</button>					
				  </form>							  
				</div>
				<Tabla dat={dat} tabla={this.props.tabla}/>
				<div className="row">
				  <a className={"btn waves-effect col s4"+
					 this.props.dat.st4[localStorage.getItem("skin")]}
					 href={"status.php?"+this.props.dat.getGet}>Inicio
					<i className="material-icons left">keyboard_backspace</i>
				  </a>
				  <a className={"btn waves-effect col s4"+
					 this.props.dat.st4[localStorage.getItem("skin")]}
					 href={hrefAnterior}>Anterior
					<i className="material-icons left">fast_rewind</i>
				  </a>
				  <a className={"btn waves-effect col s4"+
					 this.props.dat.st4[localStorage.getItem("skin")]}
					 href={"status.php?"+this.props.dat.getGet+"&top="+this.props.dat.bottom+
					 "&prevtop="+this.props.dat.top}>Siguiente
					<i className="material-icons right">fast_forward</i>
				  </a>
				</div>
			  </div>
			  <Footer dat={dat} msg={msg}/>
			</div>
		);
	}
}



class ShowSource extends React.Component {
	constructor(props){
		super(props);		
	}
	render() {		
		// Returns a highlighted HTML strin
		var htmlArr = Prism.highlight(this.props.dat.codigoSource, Prism.languages.javascript, 'javascript').split("\n");
		if(this.props.dat.codigoLang=="C++")		
			htmlArr = Prism.highlight(this.props.dat.codigoSource, Prism.languages.cpp, 'cpp').split("\n");
		if(this.props.dat.codigoLang=="Java")
			htmlArr = Prism.highlight(this.props.dat.codigoSource, Prism.languages.java, 'java').split("\n");
		if(this.props.dat.codigoLang=="Python")
			htmlArr = Prism.highlight(this.props.dat.codigoSource, Prism.languages.python, 'python').split("\n");
		var html = "<table><tr><td width='30px'><pre>";
		for(var i=1; i<=htmlArr.length; i++){
			html+="<div "+((i%2==0)?"class='"+this.props.dat.bg2[localStorage.getItem('skin')]+"'":"")+">"+i+"\n</div>";	
		}
		html+="</pre></td><td style='border-left:5px solid "+this.props.dat.bg4HTML[localStorage.getItem("skin")]+";'><pre>";
		for(var i=1; i<=htmlArr.length; i++){
			if(htmlArr[i-1]=="") htmlArr[i-1]="\n";
			html+="<div "+((i%2==0)?"class='"+this.props.dat.bg2[localStorage.getItem('skin')]+"'":"")+">";
			html+=htmlArr[i-1]+"</div>";
			
		}
		html+="</pre></td></tr></table>";

		
		var aux;
		if(dat.linkMail)
			aux=(<div className="row"><a href={dat.linkMail} className={"col offset-s3 s6 btn waves-effect "+ this.props.dat.st4[localStorage.getItem('skin')]+this.props.dat.tx1[localStorage.getItem("skin")]}>{"Enviar mensaje al autor"}</a></div>
				);		
		var codigo=(<h1>{this.props.dat.codigoSource}</h1>);
		if(this.props.dat.codigoOk){
			codigo=(
				<div>
				  <div className={"row center"+this.props.dat.st4[localStorage.getItem("skin")]}>
					<div className="col s2">Id del problema<br/>{this.props.dat.codigoProblem}</div>
					<div className="col s2">Usuario<br/>{this.props.dat.codigoUser}</div>
					<div className="col s2">Lenguage<br/>{this.props.dat.codigoLang}</div>
					<div className="col s2">Resultado<br/>{this.props.dat.codigoResult}</div>
					<div className="col s2">Tiempo<br/>{this.props.dat.codigoTime}</div>
					<div className="col s2">Memoria<br/>{this.props.dat.codigoMem}</div>	
				  </div>
				  {aux}				  
				  <pre><code dangerouslySetInnerHTML={{__html: html}}></code></pre>  
				</div>
			);
		}
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>			  
			  <Header dat={dat} msg={msg}/>
			  <div className="container">
				<br/>
				{codigo}				
			  </div>
			  <Footer dat={dat} msg={msg}/>
			</div>
		);
	}
}


/************************SUBMIT****************/

class Submit extends React.Component {
	constructor(props) {
		super(props);
		this.handleClick = this.handleClick.bind(this);
		this.handleChange = this.handleChange.bind(this);		
	}
	handleClick(event) {
		console.log("entro");
		do_submit(editor);
		console.log("entro");		
	}
	handleChange(event) {
		this.cambiarLenguage(document.getElementById("language").value);
		console.log(document.getElementById("language").value);
	}
	componentDidMount(){
		console.log("ya me render");
		editor = ace.edit("editor");
		//editor.setTheme("ace/theme/monokai");
		editor.session.setMode("ace/mode/javascript");
		
		//editor.setReadOnly(true);
		//var valorrr= editor.getValue();
		//alert(valorrr);
		document.getElementById("editor").style.position="relative";
		document.getElementById("editor").style.width='100%';
		document.getElementById("editor").style.height='500px';
		document.getElementById("editor").style.fontSize='20px';
		editor.resize();
		this.cambiarLenguage(document.getElementById("language").value);
	}
	cambiarLenguage(lan){
		if(lan==0) editor.session.setMode("ace/mode/c_cpp");
		if(lan==1) editor.session.setMode("ace/mode/c_cpp");
		if(lan==2) editor.session.setMode("ace/mode/pascal");
		if(lan==3) editor.session.setMode("ace/mode/java");
		if(lan==10) editor.session.setMode("ace/mode/objectivec");
		if(lan==11) editor.session.setMode("ace/mode/c_cpp");
		if(lan==12) editor.session.setMode("ace/mode/javascript");		
	}

	render() {
		var aux;
		var PID=["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ", "BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ"];
		if(this.props.dat.getId!=""){
			
			aux=(<div><h1>Problema {this.props.dat.getId}</h1>
				 <input id="problem_id" type="hidden"  value={this.props.dat.getId} name="id" /></div>);
		}else{
			aux=(<div>
				 <h1>Problema {PID[this.props.dat.getPid]} del Concurso
				 {this.props.dat.getCid}
				 </h1>
				 <input id="pid" type="hidden" value={this.props.dat.getPid} name="pid"/>
				 <input id="cid" type="hidden" value={this.props.dat.getCid} name="cid"/>
				 </div>);
		}
		var lang_count=this.props.dat.languageName.length;//count($language_ext);
		var lang=(~(parseInt(this.props.dat.getLangmask)))&((1<<(lang_count))-1);
		//console.log(lang_count, lang);
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>			  
			  <Header dat={dat} msg={msg}/>
			  <div className="container" align="center">
				
				<form id="frmSolution" action="submit.php" method="post">				  
				  {aux}
				  <select className="browser-default"
						  id="language" name="language"
						  onChange={this.handleChange}>
					{aux}
					{this.props.dat.languageName.map((item , index)=> {
									var aux;
									if(lang&(1<<index))
									 aux=(<option value={index} key={index}
													  selected={this.props.dat.cookieLastlang==index}>
													 {item}
													 </option>);
																	return(aux)})}
			</select>
				<label htmlFor={"language"}>Lenguaje</label>
				<textarea style={{width:"80%", display:"none"}} cols="180" rows="20"
							id="source" name="source">
			</textarea>
				<div id="editor" style={{position:"relative"}}>
				{this.props.dat.viewsrc}
				</div>
				<input id="Submit"
			className={"btn waves-effect col s4"+
					   this.props.dat.st4[localStorage.getItem("skin")]} type="button"
						 value={this.props.msg.submit} onClick={this.handleClick}/>
				</form>
				
				<br/>
			  </div>
			  <Footer dat={dat} msg={msg}/>
			</div>	
		);
	}
}
