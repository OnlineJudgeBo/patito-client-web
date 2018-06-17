class Errorpage extends React.Component{
	render(){
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>			  
			  <Header dat={dat} msg={msg}/>
			  <div style={{align:'center', width:'90%',
				   marginLeft:'auto', marginRight:'auto', textAlign:'center'}}>
				<p dangerouslySetInnerHTML={{__html: this.props.dat.viewErrors}}></p>		
			  </div>
			  <Footer dat={dat} msg={msg}/>
			</div>	
		);
	}
}

class Titulo extends React.Component{
	render(){
		return (
			<h2 style={{textAlign:'center', padding:"5 0 5 0",
				borderRadius:"25px 25px 0px 0px"}}
				className={this.props.dat.st4[localStorage.getItem("skin")]}>
			  {this.props.tit}</h2>
		);
	}
}
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
				{href:"contest.php", text:this.props.msg.numContest+" "+this.props.msg.contests,
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
			this.state.menu.push(/*{href: "#", text: "",iPos: "", iName: "brightness_4"},*/
								 {href: "#", text: "",iPos: "", iName: "details"});
		}else{
			this.state.menu.push(/*{href: "#", text: "",iPos: "", iName: "brightness_4"},*/
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
		var pri;
		if(this.props.tipo=="0"){
			pri=(<span className="green-text">Publico</span>);
		}else{
			pri=(<span className="red-text">Privado</span>);
		}
		if(this.state.now<this.state.start)
			return (<div><span className="green">
					Empieza en:<Timer
					start={(this.state.start.getTime()-this.state.now.getTime())/1000}
					frmt={frm}
					flag={-1}/></span>
					{ele}{pri}</div>);
		else if(this.state.now>this.state.end)
			return (<div>
					Terminó
					{ele}{pri}</div>);
		else return (<div><span className="red">
					Termina en:<Timer
					start={(this.state.end.getTime()-this.state.now.getTime())/1000}
					frmt={frm}
					flag={-1}/></span>
					 {ele}					 
					 Corriendo:<Timer
					start={(this.state.now.getTime()-this.state.start.getTime())/1000}
					frmt={frm}
					 flag={1}/><br/>{pri}
					 </div>);
	}
}
class Labelcontestlist extends React.Component{
	constructor(props){
		super(props);
		this.state={
			start:new Date((new Date(this.props.start)).getTime()),
			end:new Date((new Date(this.props.end)).getTime()),
			now:new Date((new Date(this.props.now)).getTime()+1000*60*60*4)
		};
	}
	render(){						
		var frm=[{s:1, t:"s"},{s:60, t:"m"}, {s:3600, t:"h"}, {s:90000, t:"d"}];
		var color, c;
		if(this.state.now<this.state.start){
			color="green lighten-1"; c=0
		}else{
			color="red lighten-1"; c=1;
		}
			return (
				<div
				  className="center-align hoverable"
				  style={{
					  borderBottom:"3px solid "+this.props.dat.cl2[c],
					  borderRight:'3px solid '+this.props.dat.cl2[c],
					  borderLeft:'3px solid '+this.props.dat.cl2[c],
					  borderRadius:'0px 0px 10px 10px'
				  }}
				  >
				  <h5
					className={color}
					style={{padding:'10 20 15 20'}}>
					<a href={"contest.php?cid="+this.props.id}
					   className={this.props.dat.tx1[localStorage.getItem("skin")]}>
					  {this.props.title}
					  <div className="secondary-content">
						<i className={"material-icons left-align"+
						   this.props.dat.tx1[localStorage.getItem("skin")]}>send</i>
					  </div>
					  
					</a>
				  </h5>				
				  <Labelcontesttime start={this.props.start}
									end={this.props.end}
									now={this.props.now}
									tipo={this.props.tipo}/>
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
				  style={{padding:"5 20 15 20",borderRadius: 5}}>
				{this.props.msg.contests}
			  </h4>			
			  {this.state.items.map(item => (				  
							   <Labelcontestlist
									 start={item.start}
									 end={item.end}
									 now={item.now}
									 dat={this.props.dat} id={item.id}
									 title={item.title} key={item.id}
									 tipo={item.tipo}/>
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
				   <img className="responsive-img" src="./image/opibanner.png"/>
				</div>
				
				<div className="col s3" id="contest-list">
				  <Contestlist dat={dat} list={this.props.list} msg={this.props.msg}/>
				</div>
				<div className="col s3" id="contest-list">
				  <h4 style={{textAlign:"center"}}>Actividades</h4>
				  <iframe src="https://calendar.google.com/calendar/embed?title=Actividades&amp;showTitle=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;mode=AGENDA&amp;height=500&amp;wkst=1&amp;hl=es&amp;bgcolor=%23ffffff&amp;src=codechef.com_3ilksfmv45aqr3at9ckm95td5g%40group.calendar.google.com&amp;color=%236B3304&amp;src=br1o1n70iqgrrbc875vcehacjg%40group.calendar.google.com&amp;color=%23182C57&amp;src=es-419.bo%23holiday%40group.v.calendar.google.com&amp;color=%238C500B&amp;src=google.com_jqv7qt9iifsaj94cuknckrabd8%40group.calendar.google.com&amp;color=%23125A12&amp;src=p0q3ahkka6tc69jt629k4dk33k%40group.calendar.google.com&amp;color=%23875509&amp;src=appirio.com_bhga3musitat85mhdrng9035jg%40group.calendar.google.com&amp;color=%23711616&amp;src=p%23weeknum%40group.v.calendar.google.com&amp;color=%23000000&amp;ctz=America%2FLa_Paz" style={{borderWidth:0}} width="100%" height="500" frameborder="0" scrolling="no"></iframe>
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
				<table className={"striped "+this.props.dat.bg2[localStorage.getItem("skin")]}>
				<thead className={this.props.dat.bg4[localStorage.getItem("skin")]}>
				<tr>{this.state.table.head.row.map((item,index)=>{
					return(
						<th key={item.text}>{item.text}<i className="material-icons left" onClick={(e)=>this.handleClick(e,index)}>{"swap_vert"}</i></th>
					);
				})}</tr>
				</thead>
				<tbody>
				{this.state.table.body.rows.map((item, index) =>{
					var bg;
					if(index%2) bg="rgba(0,0,0,0.1)";
					else bg="rgba(0,0,0,0.0)";
					return(
							<tr className={item.props.bgColor} key={"tr"+index}  style={{backgroundColor: bg}}>
						  {item.row.map((iitem, iindex)=>{
							  var col=this.props.dat.tx1[localStorage.getItem("skin")];
							  if(iitem.ctext) col = iitem.ctext;
							  return (
								  <td className={iitem.ctext}
									  key={"tr"+index+"td"+iindex+iitem.link}
									  style={{height: "10px", padding: "0 0 0 0",
									  textAlign:iitem.textAlign}}>
									<a href={iitem.link} className={"validate "+col}><p dangerouslySetInnerHTML={{__html: iitem.text}}></p>
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

class Pagescroll extends React.Component {
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
		return (
			<div className="center-align row" >
			  <h5 className="col s5" style={{textAlign:"right"}}>Paginas</h5><ul className="pagination col s7" style={{textAlign:"left"}}>
			  {arrpag.map(item => (
				  <li className={item.class} key={item.href+item.icon}>
					<a href={item.href} className={this.props.dat.tx1[localStorage.getItem("skin")]}>
					  {item.text}<i className="material-icons">{item.icon}</i>
					</a>
				  </li>
			  ))}
			</ul>
				</div>
		);
	}
}
class Problemset extends React.Component {
	render() {
			return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>
			  <Header dat={dat} msg={msg}/>
			  <div style={{align:'center', width:'90%',
				   marginLeft:'auto', marginRight:'auto'}}>
				<Titulo tit={this.props.msg.problems} dat={this.props.dat}/>
				<Pagescroll dat={dat}/>			
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
				<Pagescroll dat={dat}/>
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
	}
	render(){
		var inputCid;
		if(this.props.dat.getCid!="")inputCid=(<input type="hidden" name='cid' value={dat.getCid}/>);
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
				<Titulo tit={"Estado"} dat={this.props.dat}/>
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
					 href={hrefAnterior}>Anterior
					<i className="material-icons left">fast_rewind</i>
				  </a>
				  <a className={"btn waves-effect col s4"+
					 this.props.dat.st4[localStorage.getItem("skin")]}
					 href={"status.php?"+this.props.dat.getGet}>Inicio
					<i className="material-icons left">keyboard_backspace</i>
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
		if(this.props.dat.getId!=""){			
			aux=(<div><h1>Problema {this.props.dat.getId}</h1>
				 <input id="problem_id" type="hidden"  value={this.props.dat.getId} name="id" /></div>);
		}else{
			aux=(<div>
				 <h2>Problema {this.props.dat.PID[this.props.dat.getPid]} del Concurso
				 {this.props.dat.getCid}
				 </h2>
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
							id="source" name="source"></textarea>
				<div id="editor" style={{position:"relative"}}>
				{this.props.dat.viewsrc}
				</div>
				<input id="Submit"
			className={"btn waves-effect col s4"+
					   this.props.dat.st4[localStorage.getItem("skin")]} type="button"
						 value={this.props.msg.submit} onClick={this.handleClick}/>
				</form>
			  </div>
			  <Footer dat={dat} msg={msg}/>
			</div>	
		);
	}
}


/******************PROBLEM*************************/
class Problem extends React.Component {
	constructor(props) {
		super(props);
	}
	render() {
		var converter = new showdown.Converter();
		var title;
		if(this.props.dat.getId){
			title=(<h1>{this.props.dat.getId+":"+this.props.dat.probTitle}</h1>);
		}else{
			title=(<h1>{this.props.msg.problem+" "+
						this.props.dat.PID[this.props.dat.getPid]+":"+
						this.props.dat.probTitle}</h1>);
		}
		var spj;
		console.log(this.props.dat.probSpj);
		if(this.props.dat.probSpj==1)
			spj=(<span className="red-text">SPECIAL JUDGE</span>);
		var submit;
		if(this.props.dat.getId){
			submit=(<a href={"submitpage.php?id="+this.props.dat.getId}
					className={"col s2"+this.props.dat.tx1[localStorage.getItem("skin")]}>
					{this.props.msg.submit}<i className="material-icons right">send</i></a>);
		}else{
			submit=(<a href={"submitpage.php?cid="+this.props.dat.getCid+
							 "&pid="+this.props.dat.getPid+"&langmask="+
							 this.props.dat.langmask}
					className={"col s2"+this.props.dat.tx1[localStorage.getItem("skin")]}>
					{this.props.msg.submit}<i className="material-icons right">send</i></a>);
		}
		console.log(this.props.dat);
		var adm, spc;
		if(this.props.dat.admin){
			adm=(<span><a href={"admin/problem_edit.php?id="+this.props.dat.probPid+"&getkey="+
								this.props.dat.getKey}
				 className={"col s2"+this.props.dat.tx1[localStorage.getItem("skin")]}>Editar
				 <i className="material-icons right">edit</i></a>
				 <a href={"admin/quixplorer/index.php?action=list&dir="+
						  this.props.dat.probPid+"&order=name&srt=yes"}
				 className={"col s2"+this.props.dat.tx1[localStorage.getItem("skin")]}>
				 Casos de prueba
				 <i className={"material-icons right"}>attach_file</i></a></span>);
		}else{
			spc=(<div className="col s3"></div>);
		}		
		var desHtml = converter.makeHtml(this.props.dat.probDes);
		var inHtml = converter.makeHtml(this.props.dat.probInput);
		var outHtml = converter.makeHtml(this.props.dat.probOutput);
		var hintHtml = converter.makeHtml(this.props.dat.probHint);
		var hint;
		if(this.props.dat.probHint){
			hint=(<div><h5 className="col s1">{this.props.msg.hint}</h5>
				  <p dangerouslySetInnerHTML={{__html: hintHtml}}
				  className="col s11"></p></div>);
		}
		var source;
		if(this.props.dat.probSource)
			source=(<div><h5 className="col s1">Por</h5>
					<p className="col s11">{this.props.dat.probSource}</p></div>);
		return (			
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>
			  <Header dat={dat} msg={msg}/>
			  <div className="container">
				<div className={"card"+this.props.dat.st4[localStorage.getItem("skin")]}  align="center">
				  <div className="card-title">
					{title}
				  </div>
				  <h6>{"Tiempo Límite: "+this.props.dat.probTime+" seg. Memoria Límite:"+
						this.props.dat.probMem+" MB"}</h6>        
				  <h6>{"Enviados: "+this.props.dat.probSubmit+
					" Aceptados: "+this.props.dat.probAc}</h6>
				  {spj}
				  <div className="card-action" style={{position:"initial"}}>
					<div className="row"> {spc}
					{submit}
					<a href={"problemstatus.php?id="+this.props.dat.probPid}
					   className={"col s2"+this.props.dat.tx1[localStorage.getItem("skin")]}>
								  {this.props.msg.status}
					  <i className="material-icons right">list</i></a>
					<a href={"bbs.php?pid="+this.props.dat.probPid}
					   className={"col s2"+this.props.dat.tx1[localStorage.getItem("skin")]}>
						{this.props.msg.bbs}<i className="material-icons right">forum</i></a>
					{adm}
					 </div>
				  </div>				  
				</div>
				<div className="z-depth-2">
				  <div className={this.props.dat.st4[localStorage.getItem("skin")]}>
					<h4 style={{padding:"25 0 25 30"}}>
					  {this.props.msg.description}</h4>
				  </div>
				  <p dangerouslySetInnerHTML={{__html: desHtml}}
					 style={{padding:"5 30 5 30"}}></p>
				</div>
				<div className="z-depth-2">
				  <div className={this.props.dat.st4[localStorage.getItem("skin")]}>
					<h4 style={{padding:"25 0 25 30"}}>
					  {this.props.msg.input}</h4></div>
				  <p dangerouslySetInnerHTML={{__html: inHtml}}
					 style={{padding:"5 30 5 30"}}></p>
				</div>
				<div className="z-depth-2">
				  <div className={this.props.dat.st4[localStorage.getItem("skin")]}>
					<h4 style={{padding:"25 0 25 30"}}>
					  {this.props.msg.output}</h4></div>
				  <p dangerouslySetInnerHTML={{__html: outHtml}}
					 style={{padding:"5 30 5 30"}}></p>
				</div>
				<div className="row z-depth-2">
				  <div className={"col s6"+this.props.dat.st4[localStorage.getItem("skin")]}>
					<h4 style={{padding:"25 0 25 30"}}>
					  {this.props.msg.sampleInput}</h4></div>
				  <div className={"col s6"+this.props.dat.st4[localStorage.getItem("skin")]}>
					<h4 style={{padding:"25 0 25 30"}}>
					  {this.props.msg.sampleOutput}</h4>
				  </div>
				  <hr/>
				  <div className={"col s6"}>
					<pre style={{padding:"5 30 5 30"}}>
					  <span className={this.props.dat.bg4[localStorage.getItem("skin")]}>
						{this.props.dat.probSinput}</span>
					</pre>
				  </div>
				  <div className={"col s6"}>
					<pre style={{padding:"5 30 5 30"}}>
					  <span className={this.props.dat.bg4[localStorage.getItem("skin")]}>
						{this.props.dat.probSoutput}</span>
					</pre>
				  </div>
				</div>
				<div className="row">
				  {hint}
				  {source}				  
				</div>
			  </div>
			  <Footer dat={dat} msg={msg}/>
			</div>	
		);
	}
}


class Contestset extends React.Component{
	render(){
		console.log("tabla:",this.props.tabla);
		console.log("dat:",this.props.dat);
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>			  
			  <Header dat={dat} msg={msg}/>
			  <div style={{align:'center', width:'90%',
				   marginLeft:'auto', marginRight:'auto'}}>
				<Titulo tit={this.props.msg.contests} dat={this.props.dat}/>
				<Tabla tabla={this.props.tabla} dat={dat}/>
				</div>				
				<Footer dat={dat} msg={msg}/>
			</div>	
		);
	}
}

class Contestproblemset extends React.Component{
	render(){
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>			  
			  <Header dat={dat} msg={msg}/>
			  <div style={{align:'center', width:'90%',
				   marginLeft:'auto', marginRight:'auto', textAlign:'center'}}>
				<Titulo tit={this.props.msg.contest+" - "+this.props.dat.getCTitle}
						dat={this.props.dat}/>
				<h4 dangerouslySetInnerHTML={{__html: this.props.dat.getCDescription}}></h4>
				<div className="fb-like"
					 data-href={"contest.php?cid="+this.props.dat.getCid}
					 data-layout="button_count"
					 data-action="like" data-show-face="true" data-share="true" ></div>
				<Labelcontesttime now={this.props.dat.getCNow}
								  start={this.props.dat.getCStart}
								  end={this.props.dat.getCEnd}
								  tipo={this.props.dat.getCPrivate}/>
				<div className="row">					
					<a className={"btn waves-effect offset-s1 col s3"+
					   this.props.dat.st4[localStorage.getItem("skin")]}
					   href={"status.php?cid="+this.props.dat.getCid}>Estado
					  <i className="material-icons right">clear_all</i>
					</a>
					<a className={"btn waves-effect col s4"+
					   this.props.dat.st4[localStorage.getItem("skin")]}
					   href={"contestrank.php?cid="+this.props.dat.getCid}>Posciciones
					  <i className="material-icons right">equalizer</i>
					</a>
					<a className={"btn waves-effect col s3"+
					   this.props.dat.st4[localStorage.getItem("skin")]}
					   href={"conteststatistics.php?cid="+this.props.dat.getCid}>Estadisticas
					  <i className="material-icons right">trending_up</i>
					</a>
				</div>
				<Tabla tabla={this.props.tabla} dat={dat}/>
				</div>
		
				
				<Footer dat={dat} msg={msg}/>
			</div>	
		);
	}
}

class Ranklist extends React.Component{
	render(){
		var paginas=[];
		this.props.dat.pageTotal=parseInt(this.props.dat.pageTotal);
		this.props.dat.pageSize=parseInt(this.props.dat.pageSize);
		for(var i=0; i<this.props.dat.pageTotal; i+=this.props.dat.pageSize){
			var aux=(1+i)+"-"+(i+this.props.dat.pageSize);
			paginas.push({text:aux,
						  link:"ranklist.php?start="+i+
						  (this.props.dat.getScope!=""?"&scope="+
						   this.props.dat.getScope:"")});
			
		}
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>
			  <Header dat={dat} msg={msg}/>
			  <div style={{align:'center', width:'90%',
				   marginLeft:'auto', marginRight:'auto', textAlign:'center'}}>
				<Titulo tit={this.props.msg.ranklist} dat={this.props.dat}/>
				<form action="userinfo.php">
				  <div className="row">
					<div className="col s2">
					  <input placeholder="usuario"
							 id="userId"
							 type="text"
							 className={"validate"+this.props.dat.tx1[localStorage.getItem("skin")]}
							 name="user"
							 defaultValue={this.props.dat.getUserId}/>
					  <label htmlFor="userId">{this.props.msg.user}</label>
					</div>
					<button className={"btn waves-effect col s2"+
							this.props.dat.st4[localStorage.getItem("skin")]}
							type="submit">{this.props.msg.search}
					  <i className="material-icons right">search</i>
					</button>
					<a className={"btn waves-effect offset-s4 col s1"+
					   this.props.dat.st4[localStorage.getItem("skin")]}
					   href={"ranklist.php?scope=d"}>Dia
					  <i className="material-icons right">date_range</i>
					</a>
					<a className={"btn waves-effect col s1"+
					   this.props.dat.st4[localStorage.getItem("skin")]}
					   href={"ranklist.php?scope=w"}>Semana
					  <i className="material-icons right">date_range</i>
					</a>
					<a className={"btn waves-effect col s1"+
					   this.props.dat.st4[localStorage.getItem("skin")]}
					   href={"ranklist.php?scope=m"}>Mes
					  <i className="material-icons right">date_range</i>
					</a>
					<a className={"btn waves-effect col s1"+
					   this.props.dat.st4[localStorage.getItem("skin")]}
					   href={"ranklist.php?scope=y"}>Año
					  <i className="material-icons right">date_range</i>
					</a>
				  </div>
				</form>
				<Tabla dat={dat} tabla={this.props.tabla}/><br/>
				<h3>Posiciones</h3>
				<div className="row">
				{paginas.map(item => (
					<a className={"btn waves-effect col s1"+
					   this.props.dat.st4[localStorage.getItem("skin")]}
					   href={item.link} key={item.link}><span>{item.text}</span>
					</a>
				))}
			</div>
			  </div>
			  <Footer dat={dat} msg={msg}/>
			</div>
		);
	}
}

class Userinfo extends React.Component{
	render(){		
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>			  
			  <Header dat={dat} msg={msg}/>
			  <div style={{align:'center', width:'90%',
				   marginLeft:'auto', marginRight:'auto', textAlign:'center'}}>
				<h1>{this.props.dat.getUser+":"+this.props.dat.userNick}</h1>
				<h4>{((this.props.dat.userSchool!="")?this.props.dat.userSchool:"-")
					  +" "+this.props.dat.userEmail}</h4>
				<a className={"btn waves-effect col s3"+
				 this.props.dat.st4[localStorage.getItem("skin")]}
				 href={"href=mail.php?to_user=$user"+this.props.dat.getUser}>
				  {this.props.msg.mail}
				<i className="material-icons right">mail</i>
			  </a>
				<Tabla dat={dat} tabla={this.props.tabla}/>
			  </div>
			  <Footer dat={dat} msg={msg}/>
			</div>	
		);
	}
}


class Faqs extends React.Component{
	render(){
		var c="#include &lt;iostream&gt;\nusing namespace std;\nint main(){\nint a,b;\nwhile(cin >> a >> b)\ncout << a+b << endl;\nreturn 0;\n}";
		var cpp="#include &lt;stdio.h&gt;\nint main(){\nint a,b;\nwhile(scanf(\"%d %d\",&amp;a, &amp;b) != EOF)\nprintf(\"%d\\n\",a+b);\nreturn 0;\n\n}";
		var java="import java.util.*;\npublic class Main{\npublic static void main(String args[]){\nScanner cin = new Scanner(System.in);\nint a, b;\nwhile (cin.hasNext()){\na = cin.nextInt(); b = cin.nextInt();\nSystem.out.println(a + b);\n}\n}\n}";
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>			  
			  <Header dat={dat} msg={msg}/>
			  <div style={{align:'center', width:'90%',
				   marginLeft:'auto', marginRight:'auto', textAlign:'center'}}>
				<hr/>
				<p>Me ejecuto en <a href="http://www.debian.org/">Debian Linux</a>. 
					Utilizo <a href="http://gcc.gnu.org/">GNU GCC/G++</a> para C/C++ y <a href="http://www.oracle.com/technetwork/java/index.html">sun-java-jdk1.6</a> para Java, dentro de poco me actualizare a Java 8 o 7 . Mis opciones de compilacion son:<br/>
				</p>
				
				<hr/>
				<table border="1">
					<tr>
						<td>C:</td>
						<td className="blue-text">gcc Main.c -o Main -fno-asm -O2 -Wall -lm --static -std=c99 -DONLINE_JUDGE</td>
					</tr>
					<tr>
						<td>C++:</td>
						<td className="blue-text">g++ Main.cc -o Main -fno-asm -O2 -Wall -lm --static -std=c++11 -DONLINE_JUDGE</td>
					</tr>
					<tr>
						<td>Java:</td>
						<td className="blue-text">javac -J-Xms32m -J-Xmx256m Main.java<br/>
							<span className="red-text">*Java usa 512M de memoria maxima</span>
						</td>
					</tr>
				</table>
				<font className="green-text">Solucion del problema 1000</font><br/>
				<pre>Usando C<div className="blue-text" style={{align:"left"}}>
					{c}
				</div></pre>
				Usando C++:<br/>
				<pre><font color="blue">
					{cpp}
				</font></pre>
				<br/><br/>
				Usando Java:<br/>
				<pre><font color="blue">
					{java}
					</font></pre>

				<hr/>			  

					<font color="green"> Mis respuestas son: </font><br/>
				<hr/>

				<p><font color="blue">Pending</font> : Estoy ocupado, en un momento revisare su codigo. </p>
				<p><font color="blue">Pending Rejudge</font>: Los datos de prueba se actualizaron y volvere a revisarlos de nuevo :D.</p>
				<p><font color="blue">Compiling</font> : Estoy compilando su codigo.<br/>
				</p>
				<p><font color="blue">Running &amp; Judging</font>: Estoy evaluando tu codigo.<br/>
				</p>
				<p><font color="blue">Accepted</font> : OK! todo esta super!.<br/>
					<br/>
					<font color="blue">Presentation Error</font> : Tienes un espacio en blanco o una linea en blanco al final.<br/>
					<br/>
					<font color="blue">Wrong Answer</font> : Tu codigo no corre para todos los casos, intenta de nuevo ;-).<br/>
					<br/>
					<font color="blue">Time Limit Exceeded</font> : Tu programa no corre dentro los limites de tiempo.<br/>
					<br/>
					<font color="blue">Memory Limit Exceeded</font> : Tu programa consume mucha memoria.  <br/>
					<br/>
					<font color="blue">Output Limit Exceeded</font>: Tu programa intento escribir demasiada informacion de salida<br/>
					<br/>
					<font color="blue">Runtime Error</font> :  Tu programa se desborda o hay división entre cero.<br/>
				</p>
				<p>  <font color="blue">Compile Error</font> : Hay un error en la compilacion.<br/>
					<br/>
				</p>
				<hr/>
				<center>
					<font color="green" size="+2">Sugerencias <a href="bbs.php">Foro</a></font>
				</center>
				<hr/>

				  
				<center>
					<table width="100%" border="0">
						<tr>
							<td align="right" width="5%">
								<font color="green"> Agradecimientos a :</font>
								<a href = "index.php"><font color="red">HUSTOJ</font></a> 
								<a href = "http://code.google.com/p/hustoj/source/detail?r=1980"><font color="red">R1980+</font></a></td>
						</tr>
					</table>
				</center>
			  </div>
			  <Footer dat={dat} msg={msg}/>
			</div>	
		);
	}
}
