class Errorpage extends React.Component{
	render(){
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]} style={{minHeight:"100%"}}>
			  <Header dat={dat} msg={msg}/>
			  <div style={{align:'center', width:'90%',
				   marginLeft:'auto', marginRight:'auto', textAlign:'center'}}>
				<p dangerouslySetInnerHTML={{__html: this.props.dat.error}}></p>		
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
	constructor(props){
		super(props);
		this.handleClick = this.handleClick.bind(this);
	}
	handleClick(e){
		//localStorage.setItem("skin", (localStorage.getItem("skin")+1)%3);
		if(localStorage.getItem("skin")>1) localStorage.setItem("skin", 0);
		localStorage.setItem("skin", 1-(localStorage.getItem("skin")));
		loadPag();
	}
	render(){
		var a = new Date();
		var t = a.getHours()*3600+a.getMinutes()*60+a.getSeconds();
		var frm=[{s:1, t:""},{s:60, t:":"}, {s:3600, t:":"}];
		var user;
		if(this.props.dat.user_id)
			user=<a href={"./userinfo.php?user="+this.props.dat.user_id}
		className={"hoverable "+this.props.dat.st3[localStorage.getItem("skin")]}
		style={{fontSize:"x-large", fontWeight:"900", padding:"10 5 10 5", borderRadius:"30px 30px 0px 0px"}}>
			{this.props.dat.user_id}
			</a>;
		return (<div> <br/><br/><br/><br/>
				<div className={"footer-copyright z-depth-2"+
								this.props.dat.st3[localStorage.getItem("skin")]}
				style={{padding:"10 0 10 0", bottom:"0", width:"100%", position:"fixed"}}>
				<div className="container">				
				Bienvenido {user} al Juez Virtual de la Universidad Mayor de San Andrés
				<div className="right" style={{fontSize:"large", fontWeight:"600"}}><Timer start={t} frmt={frm} flag={1}/>
				<span ><a className={"hoverable "+this.props.dat.st3[localStorage.getItem("skin")]}
				style={{padding:"5 20 5 20", borderRadius:"20px 20px 20px 20px", fontSize:"x-large"}}
				onClick={(e)=>this.handleClick(e)}>
				<i className={"material-icons"}>brightness_4</i>
				</a></span>
				</div>
				</div>
				</div>
				</div>
		);
	}
}

class Header extends React.Component{	
	constructor(props){
		super(props);
		//this.state={tipe:"main", list:this.props.list};
		//{["problem.php", "status.php", ]}
		this.handleClick = this.handleClick.bind(this);
		
		this.state = {
			menuMain:[
				{href:(this.props.pro?"#":"problem.php"),
				 text:this.props.msg.problems,
				 click:"problemSet",
				  iName:"view_comfy"},
				{href:(this.props.sta?"#":"status.php"),
				 text:this.props.msg.status,
				 click:"status",
				 target:"_blank",
				  iName:"clear_all"},
				{href:(this.props.ra?"#":"ranklist.php"),
				 text:this.props.msg.ranklist,
				 click:"ranking",
				  iName:"equalizer"},
				{href:(this.props.co?"#":"contest.php"),
				 text:this.props.msg.numContest+" "+this.props.msg.contests,
				 click:"contestSet",
				  iName:"view_headline"},
				{href:"faqs.php", text:this.props.msg.faq,
				 click:"faqs",
				  iName:"question_answer"}],
			menuContest:[
				{href:"#", text:this.props.msg.problems,
				 click:"problemSet",
				  iName:"view_comfy"},
				{href:(this.props.sta?"#":"status.php"),
				 text:this.props.msg.status,
				 click:"status",
				 target:"_blank",
				  iName:"clear_all"},
				{href:"#", text:this.props.msg.ranklist,
				 click:"ranking",
				 iName:"equalizer"},
				{href:"#", text:"Estadisticas",
				 click:"statistics",
				 iName:"trending_up"},
				{href:(this.props.co?"#":"contest.php"),
				text:this.props.msg.numContest+" "+this.props.msg.contests,
				 click:"contestSet", iName:"view_headline"}],
			menuUser:[
				{href: "./modifypage.php",
				 text: this.props.msg.userInfo,
				 iName: "edit"},
				{href: "./userinfo.php?user="+this.props.dat.user_id,
				 text: this.props.dat.user_id,
				 iName: "person"},
				{href: "./mail.php",
				 text: this.props.dat.mail,
				 iName: "mail"},
				{href: "./status.php?user_id="+this.props.dat.user_id,
				 text: "Reciente",
				 iName: "send"}],
			text:'',
			menu:[],
			head:"main"
		};
		if(this.props.dat.user_id!=""){
			if(this.props.dat.mail>0){
				this.state.menuMain.push({
  					href: "./mail.php", text: this.props.dat.mail,
  					iName: "mail"
  				});
			}
			this.state.menuMain.push(
 				{href: "#", text: "|", iName: "arrow_downward"});
			this.state.menuContest.push(
 				{href: "#", text: "|", iName: "arrow_downward"});
		}else{
			this.state.menuMain.push(
				{href: "#", text: "Ingresar/Registrarse",
				 click:"login",
				 iName: "transfer_within_a_station"});
			this.state.menuContest.push(
				{href: "#", text: "Ingresar/Registrarse",
				 click:"login",
				 iName: "transfer_within_a_station"});
		}
		if(this.props.dat.admin ||
		   this.props.dat.contestCreator ||
		   this.props.dat.problemEditor ||
		   this.props.dat.problemME)
			this.state.menuUser.push({
  				href: "./admin", text: this.props.msg.admin,
  				iName: "build"
  			});
		this.state.menuUser.push({href: "./logout.php", text: this.props.msg.logout,
  								  iName: "exit_to_app"},
								 {href: "#", text:"|" , iName: "arrow_upward"});
		this.state.menu=this.state.menuMain;
	}
	componentWillReceiveProps(nextProps) {
		console.log("ReciveProps Head", nextProps);
		if(nextProps.head==this.state.head) return;
		console.log("ReciveProps Headdddddddddddddddddd", nextProps);
		if(nextProps.head=="contest"){
			this.setState(prevState=>({
				head:"contest",
				menu:prevState.menuContest
			}));
			return ;
		}
		if(nextProps.head=="main"){
			this.setState(prevState=>({
				head:"main",
				menu:prevState.menuMain
			}));
		}		
	}
	handleClick(e, href, icn, click){
		if(click!=undefined){
			this.props.pageChange(click);
		}
		if(icn=="arrow_downward"){
			this.setState(prevState => ({menu:prevState.menuUser}));
			return ;
		}
		if(icn=="arrow_upward"){
			this.setState(prevState => ({menu:prevState.menuMain}));
		}		
	}
	render(){
		return (
			<div className="navbar-fixed">
			  <nav className={"nav-wrapper z-depth-2 "+
				   this.props.dat.st3[localStorage.getItem("skin")]}>
				<a href={this.props.ho?"#":this.props.dat.oj_home}
				   onClick={(e)=>this.handleClick(e,"#", "pato", "home")}>
				  <img src="./image/juez-patito-logo.png"/>
				</a>
				<div className={"brand-logo"+this.props.dat.st3[localStorage.getItem("skin")]}
					 style={{zIndex:-1, position:"absolute", padding:"0 0 0 20"}}><strong>{dat.title}</strong></div>
				<ul id="nav-mobile" className="right fixed white-text">
				  {this.state.menu.map(item => (<li key={item.href+item.iName}
														onClick={(e)=>this.handleClick(e,item.href, item.iName, item.click)} className="hoverable">
												<a href={item.href} className={this.props.dat.st3[localStorage.getItem("skin")]} target={item.target}><strong className="hide-on-med-and-down">{item.text}</strong>
													  <i className={"material-icons left"}>{item.iName}</i>
													</a>
												</li>
											   ))}
			</ul>
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
			color="green lighten-1"; c=0;
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
				className={color+ " truncate"}
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
	componentWillReceiveProps(nextProps) {
		this.setState({
			items: nextProps.list
		});
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

class Judgeduck extends React.Component {
	constructor(props){
		super(props);
		this.state={page:this.props.page?this.props.page:"home",
					head:"main",
					contestPage:"problemSet", contestId:-1
				   };
		// page:"home", "problemSet", "contestSet", "contest", "login"
		this.handlePageChange=this.handlePageChange.bind(this);
		this.handleHeadChange=this.handleHeadChange.bind(this);
	}
	handleHeadChange(changedHead){
		if(changedHead!=this.state.head){
			this.setState({head:changedHead});
		}
	}	
	handlePageChange(changedPage, num){
		if(changedPage=="home"){
			this.setState({page:"home"});
		}
		if(changedPage=="problemSet"){
			if(this.state.page=="contest")
				this.setState({contestPage:"problemSet"});
			else
				this.setState({page:"problemSet"});
		}
		if(changedPage=="contestSet"){
			this.setState({page:"contestSet",
						   head:"main",
						   contestPage:-1,
						   contestId:-1
						  });
		}
		if(changedPage=="ranking"){
			if(this.state.page=="contest")
				this.setState({contestPage:"ranking"});
			else
				this.setState({page:"ranking"});
		}
		if(changedPage=="statistics"){
			if(this.state.page=="contest")
				this.setState({contestPage:"statistics"});
			else
				this.setState({page:"home"});
		}
		if(changedPage=="contest"){
			this.setState({page:"contest",
						   head:"contest",
						   contestPage:"problemSet",
						   contestId:num
						  });
		}
		if(changedPage=="login"){
			this.setState({page:"login"});
		}
	}
	render(){
		var body;
		if(this.state.page=="home"){
			body=<Index dat={dat} msg={msg}/>;
		}
		if(this.state.page=="problemSet"){
			body=<Problemset dat={dat} msg={msg} problem={-1}/>;
		}
		if(this.state.page=="contestSet" || this.state.page=="contest"){
			body=<Contestset dat={dat} msg={msg}
			pageChange={this.handlePageChange}
			contest={this.state.contestId}
			contestPage={this.state.contestPage}/>;
		}
		if(this.state.page=="ranking"){
			body=<Ranking dat={dat} msg={msg}/>;
		}
		if(this.state.page=="login"){
			body=<Login_register dat={dat} msg={msg}/>;
		}
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]} style={{minHeight:"100%"}}>
			  <Header dat={dat} msg={msg}
					  pageChange={this.handlePageChange}
					  pro={1}
					  ho={1}
					  co={1}
					  ra={1}
					  head={this.state.head}/>
			  <div style={{align:'center', width:'90%',
				   marginLeft:'auto', marginRight:'auto'}}>
				{body}
			  </div>
			  <Footer dat={dat} msg={msg}/>
			</div>
		);
		
	}
}
class Index extends React.Component {
	constructor(props){
		super(props);
		this.state={list:[]};
	}
	componentWillMount(){
		fetch('https://jv.umsa.bo/api/listContest.php')
			.then((response) => {
				return response.json();
			})
			.then((resp) => {
				this.setState({list:resp});
			});
	}
	render() {
		return (
			<div className="row">
			  <div className="col s9 center-align" id="principal">
				<h2>Juez Virtual</h2>
				<h3>Universidad Mayor de San Andres</h3>
				<img className="responsive-img" src="./image/opibanner.png" style={{height:"180px"}}/>
				<h3>Tambien puedes visitar nuestra antigua interfaz <a href="https://jv.umsa.bo/jv/">Clic</a></h3>
			  </div>				
			  <div className="col s3" id="contest-list">
				<div className="row">
				  <div className="col s12">
					<Contestlist dat={dat} list={this.state.list} msg={msg}/>
				  </div>
				</div>
				<div className="row">
				  <div className="col s12">
					<h4 className={this.props.dat.st4[localStorage.getItem("skin")]+" center-align"}
						style={{padding:"5 20 15 20",borderRadius: 5}}>
					  Actividades
					</h4>	
					<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;mode=AGENDA&amp;height=600&amp;wkst=1&amp;bgcolor=%23ffffff&amp;src=codechef.com_3ilksfmv45aqr3at9ckm95td5g%40group.calendar.google.com&amp;color=%235A6986&amp;src=br1o1n70iqgrrbc875vcehacjg%40group.calendar.google.com&amp;color=%234E5D6C&amp;src=google.com_jqv7qt9iifsaj94cuknckrabd8%40group.calendar.google.com&amp;color=%234E5D6C&amp;src=50buvctqlvh01of5oupqeaepv4%40group.calendar.google.com&amp;color=%23A32929&amp;src=p0q3ahkka6tc69jt629k4dk33k%40group.calendar.google.com&amp;color=%234E5D6C&amp;src=appirio.com_bhga3musitat85mhdrng9035jg%40group.calendar.google.com&amp;color=%234E5D6C&amp;ctz=America%2FLa_Paz" style={{borderWidth:0}} width="100%" height="500" frameBorder="0" scrolling="no"></iframe>
				  </div>
				</div>
			  </div>
			</div>
		);
	}
}

//*******************************************LOGIN****************************/
class Login_register extends React.Component {
	constructor(props){
		super(props);
	}
	render() {
		return (			
			<div className="row">
			  <div className="col s7">
				<Titulo tit={"Ingresar"}
						dat={this.props.dat}/>
				<form action="login.php" method="post">
				  <div className="row">
					<div className="col s8 center-align">
					  <div className="input-field">
						<input id="password" name="user_id" type="text"
							   className={"validate"+this.props.dat.tx1[localStorage.getItem("skin")]}
							   pattern=".{3,50}"/>
						<label htmlFor="password"
							   className={this.props.dat.tx1[localStorage.getItem("skin")]}>
						  {this.props.msg.userId}</label>
					  </div>
					  <div className="input-field">
						<input id="password" name="password" type="password"
							   className={"validate"+this.props.dat.tx1[localStorage.getItem("skin")]}
							   pattern=".{3,50}"/>
						<label htmlFor="password"
							   className={this.props.dat.tx1[localStorage.getItem("skin")]}>
						  Password</label>
					  </div>
					</div>					
					<div className="col s4 center-align">
					  <button name="submit" type="submit" value="Ingresar"
							  className={"btn waves-effect valign-wrapper"+
							  this.props.dat.st4[localStorage.getItem('skin')]}
							  style={{padding:"15 10 15 10", height:"100px", width:"100%"}}>Ingresar</button>
					</div>
				  </div>
				  <div className="row">						
					<a href="lostpassword.php"
					   className={"offset-s1 col s10 btn waves-effect "+
					   this.props.dat.st4[localStorage.getItem('skin')] }>
					  Recuperar contraseña
					</a>
				  </div>
				</form>
			  </div>
			  <div className="col offset-s1 s4 ">
				<Titulo tit={this.props.msg.regInfo}
						dat={this.props.dat}/>
				<form method="post" action="register.php" id="formulario">
				  <div className="input-field">
					<i className="material-icons prefix">account_box</i>
					<input placeholder={this.props.msg.userId}
						   id="userId" name="user_id"
						   type="text" required
						   className={this.props.dat.tx1[localStorage.getItem("skin")]+" validate"}
						   pattern=".{3,50}"/>
					<label htmlFor="userId"
						   className={this.props.dat.tx1[localStorage.getItem("skin")]}>
					  {this.props.msg.userId}</label>
					<span className={"helper-text"+this.props.dat.tx1[localStorage.getItem("skin")]}
						  data-error="Muy corto/Muy largo" data-success="right">3 a 50 caracteres</span>
				  </div>
				  <div className="input-field">
					<i className="material-icons prefix">tag_faces</i>
					<input placeholder={this.props.msg.nick}
						   id="nick" name="name"
						   type="text" required
						   className={this.props.dat.tx1[localStorage.getItem("skin")]+" validate"}						   
						   pattern=".{3,50}"/>
					<label htmlFor="nick"
						   className={this.props.dat.tx1[localStorage.getItem("skin")]}>
					  {this.props.msg.nick}</label>
					<span className={"helper-text"+this.props.dat.tx1[localStorage.getItem("skin")]}
						  data-error="Muy corto/Muy largo" data-success="right">3 a 50 caracteres</span>
				  </div>
				  <div className="input-field">
					<i className="material-icons prefix">tag_faces</i>
					<input placeholder={this.props.msg.lastname}
						   id="lastname" name="lastname"
						   type="text" required
						   className={this.props.dat.tx1[localStorage.getItem("skin")]+" validate"}						   
						   pattern=".{3,50}"/>
					<label htmlFor="lastname"
						   className={this.props.dat.tx1[localStorage.getItem("skin")]}>
					  {this.props.msg.lastname}</label>
					<span className={"helper-text"+this.props.dat.tx1[localStorage.getItem("skin")]}
						  data-error="Muy corto/Muy largo" data-success="right">3 a 50 caracteres</span>
				  </div>
				  <div className="input-field">
					<i className="material-icons prefix">email</i>
					<input placeholder={this.props.msg.email}
						   id="email" 
						   type="email" required
						   className={this.props.dat.tx1[localStorage.getItem("skin")]+" validate"}
						   name="email"/>
					<label htmlFor="email"
						   className={this.props.dat.tx1[localStorage.getItem("skin")]}>
					  {this.props.msg.email}</label>
					<span className={"helper-text"+this.props.dat.tx1[localStorage.getItem("skin")]}
						  data-error="Correo no valido" data-success="right">correo</span>
				  </div>
				  <Select name={"pais"} id="pais"
						  selected={-1}
						  options={this.props.dat.paisArr.options}
						  values={this.props.dat.paisArr.values}
						  label={this.props.msg.country}
						  form={"formulario"}
						  todos={0} onChange={0} dat={this.props.dat}
						  />
				  <div className="input-field">
					<i className="material-icons prefix">school</i>
					<input placeholder={this.props.msg.institute}
						   id="institucion" name="school"
						   type="text" required
						   className={this.props.dat.tx1[localStorage.getItem("skin")]+" validate"}						   
						   pattern=".{3,50}"/>
					<label htmlFor="institucion"
						   className={this.props.dat.tx1[localStorage.getItem("skin")]}>
					  {this.props.msg.institute}</label>
					<span className={"helper-text"+this.props.dat.tx1[localStorage.getItem("skin")]}
						  data-error="Muy corto/Muy largo" data-success="right">3 a 50 caracteres</span>
				  </div>
				  <div className="input-field">
					<i className="material-icons prefix">vpn_key</i>
					<input placeholder={this.props.msg.password}
						   id="password" name="password"
						   type="password" required
						   className={this.props.dat.tx1[localStorage.getItem("skin")]+" validate"}						   
						   pattern=".{6,50}"/>
					<label htmlFor="password"
						   className={this.props.dat.tx1[localStorage.getItem("skin")]}>
					  {this.props.msg.password}</label>
					<span className={"helper-text"+this.props.dat.tx1[localStorage.getItem("skin")]}
						  data-error="Muy corto/Muy largo" data-success="right">6 a 50 caracteres</span>
				  </div>
				  <div className="input-field">
					<i className="material-icons prefix">vpn_key</i>
					<input placeholder={this.props.msg.repeatPassword}
						   id="rptpassword" name="rptpassword"
						   type="password" required
						   className={this.props.dat.tx1[localStorage.getItem("skin")]+" validate"}						   
						   pattern=".{6,50}"/>
					<label htmlFor="rptpassword"
						   className={this.props.dat.tx1[localStorage.getItem("skin")]}>
					  {this.props.msg.repeatPassword}</label>
					<span className={"helper-text"+this.props.dat.tx1[localStorage.getItem("skin")]}
						  data-error="Muy corto/Muy largo" data-success="right">6 a 50 caracteres</span>
				  </div>
				  <button className={"btn waves-effect"+
						  this.props.dat.st4[localStorage.getItem("skin")]}
						  type="submit" name="submit" id="submit">{"Crear"}
					<i className="material-icons right">check</i>
				  </button>
				  <button className={"btn waves-effect"+
						  this.props.dat.st4[localStorage.getItem("skin")]}
						  type="reset" name="reset" id="reset">{"Reset"}
					<i className="material-icons right">clear</i>
				  </button>
				</form>
			  </div>
			</div>
		);
	}
}

class Tabla extends React.Component{
	constructor(props){
		super(props);
		this.state={order:0, table:this.props.tabla};
		this.handleClickTd = this.handleClickTd.bind(this);
	}
	componentWillReceiveProps(nextProps) {
		this.setState({
			table: nextProps.tabla
		});
	}
	handleClick(e, val){		
		var num = val;
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
	}
	handleClickTd(e, click){
		if(click!=undefined){
			this.props.page(click);
		}
	}
	render(){
		return(				 
				<table className={this.props.dat.bg2[localStorage.getItem("skin")]}
			style={{width:this.state.table.props.width}}>
				<thead className={this.props.dat.st4[localStorage.getItem("skin")]}>
				<tr>{this.state.table.head.row.map((item,index)=>{
					var col=this.props.dat.tx4[localStorage.getItem("skin")];
					if(item.ctext) col = item.ctext;
					return(
						<th key={item.text+item.link} style={{textAlign:"center"}}
							 className="hoverable">
						  <i className="material-icons tiny" onClick={(e)=>this.handleClick(e,index)}>{"swap_vert"}</i>
						  <a href={item.link}  target="_blank"
							 className={col} onClick={(e)=>this.handleClickTd(e,item.click)}>{item.text}</a></th>
					);
				})}</tr>
				</thead>
				<tbody>
				{this.state.table.body.rows.map((item, index) =>{
					var bg;
					if(index%2) bg="rgba(0,0,0,0.1)";
					else bg="rgba(0,0,0,0.0)";
					return(
						<tr className={item.props.bgColor} key={"tr"+index}
							style={{backgroundColor: bg}}>
						  {item.row.map((iitem, iindex)=>{
							  var col=this.props.dat.tx1[localStorage.getItem("skin")];
							  if(iitem.ctext) col = iitem.ctext;
							  return (
								  <td className={(((typeof iitem.link!= 'undefined')||(typeof iitem.click!='undefined'))?"hoverable ":"")+
												 (iitem.ctext?iitem.ctext:"")+" "+
									  (iitem.bgcolor?iitem.bgcolor:"")}
									  key={"tr"+index+"td"+iindex+iitem.link}
									  style={{ padding: "5 0 5 0",
											  borderBottomColor:iitem.borderBottomColor,
											  borderBottomStyle:iitem.borderBottomStyle,
											  borderBottomWidth:iitem.borderBottomWidth,
											  borderRightColor:iitem.borderLeftColor,
											  borderRightStyle:iitem.borderLeftStyle,
											  borderRightWidth:iitem.borderLeftWidth,
											  backgroundColor:iitem.bgColorHTML,
									  textAlign:iitem.textAlign}}
									  onClick={(e)=>this.handleClickTd(e,iitem.click)}>
									<a href={iitem.link} target={(iitem.link=="#"?"":"_blank")}
									  className={col}>
									  <p dangerouslySetInnerHTML={{__html: iitem.text}}></p>
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
			<div className="input-field">
			  <select className="browser-default"
					  name={this.props.name}
					  id={this.props.id}
					  onChange={this.handleChange}>
				{aux}
				{this.props.options.map((item , index)=> (
					<option value={(this.props.values?this.props.values[index]:index)} key={index} selected={this.props.selected==index}>
					  {item}
					</option>
				))}
			</select>
				<label htmlFor={this.props.id}
			className={this.props.dat.tx1[localStorage.getItem("skin")]+" active"}>
				{this.props.label}</label>
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
				<div className="col s2 hoverable">
				  <Select name={"showsim"} id="Selshowsim"
						  selected={this.props.dat.getShowsim}
						  options={this.props.dat.simArr}
						  label={"Similitud"}
						  form={"simform"}
						  todos={1} onChange={1} dat={this.props.dat}
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
                        <div className={this.props.dat.st1[localStorage.getItem("skin")]} style={{minHeight:"100%"}}>
                          <Header dat={dat} msg={msg}/>
                          <div style={{align:'center', width:'90%',
                                   marginLeft:'auto', marginRight:'auto', textAlign:'center'}}>
                                <p dangerouslySetInnerHTML={{__html: this.props.dat.error}}></p>


			<div><Titulo tit={"Estado"} dat={this.props.dat}/>
				<div className="row">
				  <form id="simform" action="status.php" method="get">
					<div className="input-field col s2 hoverable">
					  <input id="problemId"
							 type="text"
							 className={"validate"+this.props.dat.tx1[localStorage.getItem("skin")]}
							 name="problem_id"
							 defaultValue={(this.props.dat.getCid!=""?this.props.dat.PID[this.props.dat.getProblemId]:this.props.dat.getProblemId)}/>
					  <label htmlFor="problemId"
							 className={this.props.dat.tx1[localStorage.getItem("skin")]+
							 (this.props.dat.getProblemId!=""?" active":"")}>
						Id del problema</label>
					</div>
					<div className="input-field inline col s2 hoverable">
					  <input id="userId"
							 type="text"
							 className={"validate"+this.props.dat.tx1[localStorage.getItem("skin")]}
							 name="user_id"
							 defaultValue={this.props.dat.getUserId}/>
					  <label htmlFor="userId"
							 className={this.props.dat.tx1[localStorage.getItem("skin")]+
							 (this.props.dat.getUserId!=""?" active":"")}>
						{this.props.msg.user}</label>
					</div>
					{inputCid}
					<div className="col s2 hoverable">
					  <Select name={"language"} id={"Sellanguage"} 
							  selected={this.props.dat.getLanguage}
							  options={this.props.dat.languageName}
							  label={"Lenguaje"}
							  form={"simform"} todos={1} onChange={1}
							  dat={this.props.dat}
							  />
					</div>					
					<div className="col s2 hoverable">
					  <Select name={"jresult"} id={"Seljresult"}
							  selected={this.props.dat.getJresult}
							  options={this.props.dat.jresult}
							  label={"Resultado"}
							  form={"simform"} todos={1} onChange={1}
							  dat={this.props.dat}
							  />
					</div>
					{selectShowSim}
					<button className={"input-field btn waves-effect col s2 hoverable"+
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
		var htmlArr = Prism.highlight(this.props.dat.codigoSource,
									  Prism.languages.javascript, 'javascript').split("\n");
		if(this.props.dat.codigoLang=="C++")		
			htmlArr = Prism.highlight(this.props.dat.codigoSource,
									  Prism.languages.cpp, 'cpp').split("\n");
		if(this.props.dat.codigoLang=="Java")
			htmlArr = Prism.highlight(this.props.dat.codigoSource,
									  Prism.languages.java, 'java').split("\n");
		if(this.props.dat.codigoLang=="Python")
			htmlArr = Prism.highlight(this.props.dat.codigoSource,
									  Prism.languages.python, 'python').split("\n");
		var html = "<table><tr><td width='30px'><pre>";
		for(var i=1; i<=htmlArr.length; i++){
			html+="<div "+((i%2==0)?"class='"+
						   this.props.dat.bg2[localStorage.getItem('skin')]+"'":"")+
				">"+i+"\n</div>";	
		}
		html+="</pre></td><td style='border-left:5px solid "+
			this.props.dat.bg4HTML[localStorage.getItem("skin")]+";'><pre>";
		for(var i=1; i<=htmlArr.length; i++){
			if(htmlArr[i-1]=="") htmlArr[i-1]="\n";
			html+="<div "+((i%2==0)?"class='"+
						   this.props.dat.bg2[localStorage.getItem('skin')]+"'":"")+">";
			html+=htmlArr[i-1]+"</div>";			
		}
		html+="</pre></td></tr></table>";	
		var aux;
		if(dat.linkMail)
			aux=(<div className="row"><a href={dat.linkMail}
				 className={"col offset-s3 s6 btn waves-effect "+
							this.props.dat.st4[localStorage.getItem('skin')]+
							this.props.dat.tx1[localStorage.getItem("skin")]}>
				 {"Enviar mensaje al autor"}</a></div>
				);		
		var codigo=(<h1>{this.props.dat.codigoSource}</h1>);
		if(this.props.dat.codigoOk){
			codigo=(
				<div>
				  <div className={"row center"+
					   this.props.dat.st4[localStorage.getItem("skin")]}>
					<div className="col s2">Id del problema<br/>
					  {this.props.dat.codigoProblem}</div>
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
				  <div style={{align:'', width:'90%',
					   marginLeft:'auto', marginRight:'auto', textAlign:''}}>
					{codigo}
				  </div>
				  <Footer dat={dat} msg={msg}/>
			</div>
		);
	}
}

class Submit extends React.Component {
	constructor(props) {
		super(props);
		this.handleClick = this.handleClick.bind(this);
		this.handleChange = this.handleChange.bind(this);
		console.log(ace);
	}
	handleClick(event) {
		do_submit(editor);
	}
	handleChange(event) {
		this.cambiarLenguage(document.getElementById("language").value);
	}
	componentDidMount(){
		this.props.dat.editor = ace.edit("editor");
		if(localStorage.getItem("skin")==1)
			this.props.dat.editor.setTheme("ace/theme/monokai");
		this.props.dat.editor.session.setMode("ace/mode/javascript");		
		//editor.setReadOnly(true);
		document.getElementById("editor").style.position="relative";
		document.getElementById("editor").style.width='100%';
		document.getElementById("editor").style.height='500px';
		document.getElementById("editor").style.fontSize='20px';
		this.props.dat.editor.resize();
		//this.cambiarLenguage(document.getElementById("language").value);
	}
	cambiarLenguage(lan){
		if(lan==0) this.props.dat.editor.session.setMode("ace/mode/c_cpp");
		if(lan==1) this.props.dat.editor.session.setMode("ace/mode/c_cpp");
		if(lan==2) this.props.dat.editor.session.setMode("ace/mode/pascal");
		if(lan==3) this.props.dat.editor.session.setMode("ace/mode/java");
		if(lan==10) this.props.dat.editor.session.setMode("ace/mode/objectivec");
		if(lan==11) this.props.dat.editor.session.setMode("ace/mode/c_cpp");
		if(lan==12) this.props.dat.editor.session.setMode("ace/mode/javascript");		
	}
	render() {
		var aux;
		if(this.props.dat.getId!=""){			
			aux=(<div><h1>Problema {this.props.dat.getId}</h1>
				 <input id="problem_id" type="hidden"
				 value={this.props.dat.getId} name="id" /></div>);
		}else{
			aux=(<div>
				 <h2>Problema {this.props.dat.PID[this.props.dat.getPid]} del Concurso
				 {this.props.dat.getCid}
				 </h2>
				 <input id="pid" type="hidden" value={this.props.dat.getPid} name="pid"/>
				 <input id="cid" type="hidden" value={this.props.dat.getCid} name="cid"/>
				 </div>);
		}
		var lang_count=this.props.dat.languageName.length;
		var lang=(~(parseInt(this.props.dat.getLangmask)))&((1<<(lang_count))-1);
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>			  
			  <Header dat={dat} msg={msg}/>
			  <div className="container" align="center">				
				<form id="frmSolution" action="submit.php" method="post">				  
				  {aux}
				  <div className="input-field">
					<select className="browser-default"
							id="language" name="language"
							onChange={this.handleChange}>
					  {aux}
					  {this.props.dat.languageName.map((item , index)=>{
																	var aux;
																	if(lang&(1<<index))
																					 aux=(<option value={index} key={index}
																									  selected={this.props.dat.cookieLastlang==index}>
																						  {item}
																						  </option>);
						  return(aux);})}
			</select>
				<label htmlFor={"language"}
			className={this.props.dat.tx1[localStorage.getItem("skin")]+" active"}>
				Lenguaje</label>
				</div>
				<textarea style={{width:"80%", display:"none"}} cols="180" rows="20"
			id="source" name="source"></textarea>
				<div id="editor" style={{position:"relative"}}>
				{this.props.dat.viewsrc}</div>
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

class Pagescroll extends React.Component {
	constructor(props){
		super(props);
		this.handleClick = this.handleClick.bind(this);
	}	
	handleClick(e, click){
		if(click!=undefined){
			this.props.changePage(click);
		}
	}
	render() {
		if(this.props.pages==0) return (<div></div>);
		var arrpag=[];
		if(this.props.page>1){
			arrpag.push({class:"waves-effect hoverable",
						 href:"#",
						 click:this.props.page-1,
						 text:"",
						 icon:"chevron_left"});
		}else{
			arrpag.push({class:"disabled",
						 href:"#!",
						 text:"",
						 icon:"chevron_left"});
		}
		for(var i=1; i<=this.props.pages; i++){
			if(i==this.props.page)
				arrpag.push({class:this.props.dat.st4[localStorage.getItem("skin")],
							 href:"#",
							 text:i});
			else arrpag.push({class:"waves-effect hoverable",
							  href:"#",
							  click:i,
							  text:i});
		}
		if(this.props.page<this.props.pages){
			arrpag.push({class:"waves-effect hoverable",
						 href:"#",
						 click:this.props.page+1,
						 text:"",
						 icon:"chevron_right"});
		}else{
			arrpag.push({class:"disabled",
						 href:"#!",
						 text:"",
						 icon:"chevron_right"});
		}
		return (
			<div className="center-align row">
			  <h5 className="col s4" style={{textAlign:"right"}}>Paginas</h5><ul className="pagination col s8" style={{textAlign:"left"}}>
				{arrpag.map((item) =>{ return (
					<li className={item.class}
						key={item.href+item.icon+item.click}
						onClick={(e)=>this.handleClick(e,item.click)}>
																				<a href={item.href} className={this.props.dat.tx1[localStorage.getItem("skin")]}>
																				{item.text}<i className="material-icons">{item.icon}</i>
																				</a>
</li>
				);})}
			</ul>
				</div>
		);
	}
}


class Problemset extends React.Component {
	constructor(props) {
		super(props);
		this.state={problemNum:0, problem:this.props.problem, problemSet:-1};
		this.handleProblemChange = this.handleProblemChange.bind(this);
		this.handlePageChange = this.handlePageChange.bind(this);
	}
	componentWillMount(){
		fetch('https://jv.umsa.bo/api/problem.php')
			.then((response) => {
				return response.json();
			})
			.then((resp) => {
				this.setState({problemSet:resp});
			});
	}
	componentWillReceiveProps(nextProps) {
		this.setState({
			problem: nextProps.problem
		});
	}
	handleProblemChange(changedProblem){
		var url = "https://jv.umsa.bo/api/problem.php?id="
			+this.state.problemSet.tabla.body.rows[changedProblem].row[0].text;
		fetch(url)
			.then((response) => {
				return response.json();
			})
			.then((resp) => {
				if(resp.id){
					this.setState({problem:resp, problemNum:changedProblem});
				}else{
					console.log("algo salio mal Problempage1 handleProblemChange");
				}
			});
	}
	handlePageChange(changedPage){
		if(changedPage=="problemSet"){
			this.setState({problem:-1}); return ;
		}
		var url = "https://jv.umsa.bo/api/problem.php?page="+changedPage;
		fetch(url)
			.then((response) => {
				return response.json();
			})
			.then((resp) => {
				this.setState({problemSet:resp, problem:-1});
			});
	}
	cambiar(event, num){
		if(num==0){
			this.setState(prevState=>({problem:-1}));
			return ;
		}
		var aux = this.state.problemNum;
		aux += num;		
		if(aux<0 || aux>=this.state.problemSet.tabla.body.rows.length){
			this.setState(prevState=>({problem:-1}));
			return ;
		}
		this.handleProblemChange(aux);
	}
	render() {
		if(this.state.problem!=-1){ //Problem
			return (
				<div>
				  <div className="row">
					<div className={"col s2 center-align"}
						 style={{padding:"150 0 0 0"}}
						 onClick={(e)=>(this.cambiar(e, -1))}>
					  <div className={"pinned"}>
						<i className={"material-icons large hoverable"}
						   style={{borderRadius:"50px 0px 0px 50px"}}>{"arrow_back"}
						</i>
					  </div>
					</div>
					<div className={"col s8"}>
					  <Problem dat={dat} msg={msg}
							   problem={this.state.problem}/>
					</div>
					<div className={"col offset-s1 s1 center-align"}
						 style={{padding:"150 0 0 0"}}
						 onClick={(e)=>this.cambiar(e, 1)}>
					  <div className={"pinned"}><i className={"material-icons large hoverable"}
												   style={{borderRadius:"0px 50px 50px 0px"}}>arrow_forward</i></div>
					</div>
				  </div>
				</div>				
			);
		}else{
			if(this.state.problemSet!=-1){ //ProblemSet			
				var pagS=(
					<div className="row">
					  <div className={"col s4 hoverable center-align"+
						   this.props.dat.st4[localStorage.getItem("skin")]}
						   onClick={(e)=>this.cambiar(e, -1)}>
						<i className="material-icons small">
						  arrow_back</i></div>
					  <div className={"col s4 center-align hoverable"+this.props.dat.st4[localStorage.getItem("skin")]}
						   onClick={(e)=>this.cambiar(e, 0)}>
						<i className="material-icons small"
						   >arrow_downward</i>
					  </div>

					  <div className={"col s4 center-align hoverable"+this.props.dat.st4[localStorage.getItem("skin")]} onClick={(e)=>this.cambiar(e, 1)}>
						<i className="material-icons small"
						   >arrow_forward</i>
					  </div>
					</div>
				);
				return (
					<div>
					  <Titulo tit={this.props.msg.problems} dat={this.props.dat}/>
					  <Pagescroll dat={dat}
								  pages={this.state.problemSet.pages.total}
								  page={this.state.problemSet.pages.in}
								  changePage={this.handlePageChange}/>
					  <div className="row">
						<form action="problem.php">
						  <div className="input-field col s3 offset-s1 hoverable">
							<input id="problemId"
								   type="text"
								   className={"validate"+
								   this.props.dat.tx1[localStorage.getItem("skin")]} name="id"/>
							<label htmlFor="problemId"
								   className={this.props.dat.tx1[localStorage.getItem("skin")]+
								   (1!=1?" Active":"")}>ID del problema</label>
						  </div>
						  <button className={"input-field btn waves-effect col s2 hoverable"+
								  this.props.dat.st4[localStorage.getItem("skin")]}type="submit">Ir
							<i className="material-icons right">send</i>
						  </button>
						</form>
						<form>
						  <div className="input-field col s3 hoverable">
							<input id="search" type="text"
								   className={"validate"+
								   this.props.dat.tx1[localStorage.getItem("skin")]} name="search"/>
							<label htmlFor="search"
								   className={this.props.dat.tx1[localStorage.getItem("skin")]+
								   (1!=1?" Active":"")}>Busca algo</label>
						  </div>
						  <button className={"input-field btn waves-effect col s2 hoverable"+
								  this.props.dat.st4[localStorage.getItem("skin")]}
								  type="submit">Buscar
							<i className="material-icons right">search</i>
						  </button>
						</form>
					  </div>
					  <Tabla dat={this.props.dat} tabla={this.state.problemSet.tabla} page={this.handleProblemChange}/>
					  <Pagescroll dat={dat}
								  pages={this.state.problemSet.pages.total}
								  page={this.state.problemSet.pages.in}
								  changePage={this.handlePageChange}/>
					</div>
				);
			}
		}
		return (
			<div className="preloader-wrapper big active">
			  <div className="spinner-layer spinner-blue">
				<div className="circle-clipper left">
				  <div className="circle"></div>
				</div><div className="gap-patch">
				  <div className="circle"></div>
				</div><div className="circle-clipper right">
				  <div className="circle"></div>
				</div>
			  </div>
			</div>
		);
	}
}

class Problem extends React.Component {
	constructor(props) {
		super(props);
		this.state={problem:{}, problemId:-1, problemContest:-1};
	}
	componentWillMount(){
		fetch('https://jv.umsa.bo/api/problem.php?id='+this.props.id)
			.then((response) => {
				return response.json();
			})
			.then((empleados) => {
				this.setState({ problem: empleados });				
			});
		//console.log("State Problem Will", this.state);
	}
	componentDidMount() {
		//console.log("State Problem Did", this.state);
		MathJax.Hub.Typeset();
	}
	componentDidUpdate(){
		//console.log("State Problem DidUpdate", this.state);
		MathJax.Hub.Typeset();
	}
	copiarAlPortapapeles(e, text) {
		var aux = document.createElement("textarea");
		aux.innerHTML=text;
		document.body.appendChild(aux);
		aux.select();
		document.execCommand("copy");
		document.body.removeChild(aux);
	}
	render() {
		var converter = new showdown.Converter();
		var title;
		var spj;
		if(this.props.problem.spj==1)
			spj=(<span className="red-text">SPECIAL JUDGE</span>);
		var menu =[];
		if(this.props.problem.cId){
			title=(
				<Titulo tit={this.props.msg.problem+" "+
						this.props.dat.PID[this.props.problem.pId]+": "+
						this.props.problem.title} dat={this.props.dat}/>);
			if(this.props.problem.submit && (this.props.dat.user_id!=""))
				menu.push({text:this.props.msg.submit,
						   link:"submitpage.php?cid="+this.props.problem.cId+
						   "&pid="+this.props.problem.pId+"&langmask="+
						   this.props.dat.contest.langmask,
						   icon:"send"});
			menu.push({text:this.props.msg.status,
					   link:"status.php?problem_id="+this.props.dat.PID[this.props.problem.pId]+
					   "&cid="+this.props.problem.cId,
					   icon:"list"});
		}else{
			title=(<Titulo tit={this.props.problem.id+":"+this.props.problem.title}
				   dat={this.props.dat}/>);
			if(this.props.problem.submit && (this.props.dat.user_id!=""))
				menu.push({text:this.props.msg.submit,
						   link:"submitpage.php?id="+this.props.problem.id,
						   icon:"send"});
			menu.push({text:this.props.msg.status,
					   link:"status.php?problem_id="+this.props.problem.id,
					   icon:"list"});
			menu.push({text:"Estadísticas",
					   link:"problemstatistics.php?id="+this.props.problem.id,
					   icon:"trending_up"});
		}		
		/*menu.push({text:this.props.msg.bbs,
				   link:"#",//"bbs.php?pid="+this.props.problem.id,
				   icon:"forum"});*/
		var spc;
		var spcNum=0;
		if(this.props.dat.admin){
			menu.push({text:"Editar",
					   link:"admin/problem_edit.php?id="+this.props.problem.id+"&getkey="+
					   this.props.dat.getKey,
					   icon:"edit"});
			menu.push({text:"Casos",
					   link:"jv/admin/quixplorer/index.php?action=list&dir="+
					   this.props.problem.id+"&order=name&srt=yes",
					   icon:"attach_file"});
			spcNum=1;
			if(this.props.problem.cId) spcNum++;
		}else{			
			if((this.props.problem.submit) &&
			   (this.props.dat.user_id!=""))
				spcNum=3;
			else spcNum=4;
			if((this.props.problem.cId)) spcNum++;
			
		}
		if(spcNum)
			spc=(<div className={"col s"+spcNum}></div>);
		var desHtml = (converter.makeHtml(this.props.problem.des));// ltxParse
		var inHtml = (converter.makeHtml(this.props.problem.input));
		var outHtml = (converter.makeHtml(this.props.problem.output));
		var hintHtml = (converter.makeHtml(this.props.problem.hint));
		var hint;
		if(this.props.problem.hint){
			hint=(<div><h5 className="col s1">{this.props.msg.hint}</h5>
				  <p dangerouslySetInnerHTML={{__html: hintHtml}}
				  className="col s11"></p></div>);
		}
		var source;
		if(this.props.problem.source)
			source=(<div><h5 className="col s1">Por</h5>
					<p className="col s11">{this.props.problem.source}</p></div>);
		
		return (
			<div>			  
			  <div align="center">
				{title}				
				<h6>{"Tiempo Límite: "+this.props.problem.time+" seg. Memoria Límite:"+
				  this.props.problem.mem+" MB"}</h6>        
				<h6>{"Enviados: "+this.props.problem.submit+
				  " Aceptados: "+this.props.problem.ac}</h6>
				{spj}				
				  <div className="row"> {spc}
					{menu.map(item => (
						<div className={"col s2 hoverable btn"+this.props.dat.st4[localStorage.getItem("skin")]} key={item.icon}>
						  <a href={item.link} target="_blank"
							 className={this.props.dat.tx1[localStorage.getItem("skin")]}>
							<span className="hide-on-med-and-down">{item.text}</span>
							<i className="material-icons right">{item.icon}</i></a>
						</div>
					))}
			</div>				  
				</div>
				<div className="row z-depth-2">
				<h4 style={{padding:"25 0 25 30"}} className={this.props.dat.st4[localStorage.getItem("skin")]}>
				{this.props.msg.description}</h4>
				<h5 dangerouslySetInnerHTML={{__html: desHtml}}
			style={{padding:"5 30 5 30"}}></h5>
				</div>
				<div className="row z-depth-2"> 
				<h4 style={{padding:"25 0 25 30"}} className={this.props.dat.st4[localStorage.getItem("skin")]}>
				{this.props.msg.input}</h4>
				<h5 dangerouslySetInnerHTML={{__html: inHtml}}
			style={{padding:"5 30 5 30"}}></h5>
				</div>
				<div className="row z-depth-2">
				<h4 style={{padding:"25 0 25 30"}} className={this.props.dat.st4[localStorage.getItem("skin")]}>
				{this.props.msg.output}</h4>
				<h5 dangerouslySetInnerHTML={{__html: outHtml}}
			style={{padding:"5 30 5 30"}}></h5>
				</div>
				<div className="row z-depth-2">
				<div className={"col s6"+this.props.dat.st4[localStorage.getItem("skin")]}>
				<h4 style={{padding:"25 0 25 30"}}>{this.props.msg.sampleInput}
				<i className="waves-effect material-icons small right hoverable" onClick={
					(e)=>this.copiarAlPortapapeles(e,this.props.problem.sinput)}>
				content_copy</i></h4>
				</div>
				<div className={"col s6"+this.props.dat.st4[localStorage.getItem("skin")]}>
				<h4 style={{padding:"25 0 25 30"}}>{this.props.msg.sampleOutput}
				<i className="waves-effect material-icons small right hoverable" onClick={
					(e)=>this.copiarAlPortapapeles(e,this.props.dat.problem.soutput)}>
				content_copy</i>
				</h4>
				</div>
				<hr/>
				<div className={"col s6"}>
				<pre style={{padding:"5 30 5 30"}}>
				<span className={this.props.dat.bg4[localStorage.getItem("skin")]}>
				{this.props.problem.sinput}</span>
				</pre>
				</div>
				<div className={"col s6"}>
				<pre style={{padding:"5 30 5 30"}}>
				<span className={this.props.dat.bg4[localStorage.getItem("skin")]}>
				{this.props.problem.soutput}</span>
				</pre>
				</div>
				</div>
				<div className="row">
				{hint}
			{source}				  
			</div>
				</div>
		);
	}
}
//////////////////////****************CONTEST*******************************///////////////
class Contestset extends React.Component{
	constructor(props){
		super(props);
		this.state={page:"loading",
					contestId:this.props.contest, contestPage:this.props.contestPage};
		this.handleContestChange = this.handleContestChange.bind(this);
		this.cargar = this.cargar.bind(this);
	}
	cargar(contestId, contestPage){
		if(contestId==-1){
			fetch('https://jv.umsa.bo/api/contest.php')
				.then((response) => {
					return response.json();
				})
				.then((resp) => {
					console.log("resp",resp);
					this.setState({page:"contestSet",
								   contest:-1,
								   contestSet:resp
								   });
				});
		}else{
			var url = "https://jv.umsa.bo/api/contest.php?id="+contestId;
			fetch(url)
				.then((response) => {
					return response.json();
				})
				.then((resp) => {
					if(resp.id){
						this.setState({page:"contest",
									   contest:resp,
									   contestPage:contestPage});
					}else{
						console.log("algo salio mal Problempage1 handleProblemChange");
					}
				});
		}
	}
	componentWillMount(){
		this.cargar(this.props.contest, this.props.contestPage);
	}
	componentWillReceiveProps(nextProps) {
		this.setState({page:"loading"});
		this.cargar(nextProps.contest, nextProps.contestPage);
	}
	handleContestChange(changedContest){
		this.props.pageChange("contest", changedContest);
	}
	render(){
		if(this.state.page=="contest"){
			return <Contest contest={this.state.contest} page={this.state.contestPage}/>;
		}else{
			if(this.state.page=="contestSet"){
				return <div><Titulo tit={this.props.msg.contests} dat={this.props.dat}/>
					<Tabla tabla={this.state.contestSet.tabla} dat={dat}
				page={this.handleContestChange}/></div>;
			}
		}
		return (
			<div className="preloader-wrapper big active">
			  <div className="spinner-layer spinner-blue">
				<div className="circle-clipper left">
				  <div className="circle"></div>
				</div><div className="gap-patch">
				  <div className="circle"></div>
				</div><div className="circle-clipper right">
				  <div className="circle"></div>
				</div>
			  </div>
			</div>
		);
	}
}

class Contest extends React.Component{ // dat msg global variables
	constructor(props) {
		super(props);
		this.handleProblemChange = this.handleProblemChange.bind(this);
		this.state={page:(this.props.page?this.props.page:"loading"), problem:-1, contest:this.props.contest};
		if(this.props.contest.onlyContest){			
			this.state.page="ranking";
			return ;
		}
		this.state.config = {
			type: 'line',
			data: {
				labels: this.state.contest.statistics.graphics.labels.map((x)=>{return (new Date(parseFloat(x))).toLocaleString();})
				,datasets: [{
					label: 'Sub',
					backgroundColor: 'rgb(2, 151, 39, 0.7)',
					borderColor: Chart.helpers.color.red,
					//fill: false,
					lineTension:0.1,
					data:  this.state.contest.statistics.graphics.d2//.map((x)=>{return new Date(x).toLocaleString();})
					
				},{
					label: 'Ac',					
					backgroundColor: 'rgb(255,0,0, 0.5)',
					borderColor: Chart.helpers.color.green,
					//fill: false,
					lineTension:0.1,
					data:  this.state.contest.statistics.graphics.d1//.map((x)=>{return new Date(x).toLocaleString();})
				}]
			},
			options: {
				title: {
					text: 'Chart.js Time Scale'
				},
				scales: {
					xAxes: [{}],
					yAxes: [{
						scaleLabel: {
							display: true,
							labelString: 'value',
						},
						ticks: {
							beginAtZero:true,
							stepSize:1		
						},
						//height:5
					}],					
				},
			}
		};
	}	
	handleProblemChange(changedProblem){
		if(this.state.onlyContest) return ;
		this.setState({page:"loading"});
		var cad = this.state.contest.tabla.body.rows[changedProblem].row[0].text;
		var numc = "";
		var sw = 0;
		for(var i=0; i<cad.length; i++){
			if(cad.charAt(i)==")") break;
			if(cad.charAt(i)=="("){ sw=1; i++; }
			if(sw==1){
				numc+=cad.charAt(i);
			}
		}
		var url = "https://jv.umsa.bo/api/problem.php?id="+numc;
		fetch(url)
			.then((response) => {
				return response.json();
			})
			.then((resp) => {
				resp.submit=(this.state.contest.now<this.state.contest.end);
				resp.cId=parseInt(numc);
				resp.pId=parseInt(changedProblem);
				if(resp.id){					
					this.setState({problem:resp, problemNum:changedProblem, page:"problem"});
				}else{
					console.log("algo salio mal Problempage1 handleProblemChange");
				}
			});
	}
	componentWillReceiveProps(nextProps) {
		if(this.state.onlyContest) return ;
		if (nextProps.page !== this.state.page) {
			this.setState({
				page: nextProps.page,
				contest: nextProps.contest
			});
		}
		console.log("will Prop");
	}
	componentDidMount(prevProps) {
		var ctx = document.getElementById("myChart");
		if(ctx)
			var myLineChart = new Chart(ctx, this.state.config);
		console.log("ctx", ctx);
	}	
	cambiar(event, num){
		if(num==0){
			this.setState(prevState=>({page:"problemSet"}));
			return ;
		}
		var aux = this.state.problemNum;
		aux += num;
		if(aux<0 || aux>=this.state.contest.tabla.body.rows.length){
			this.setState(prevState=>({page:"problemSet",problem:-1}));
			return ;
		}
		this.handleProblemChange(aux);		
	}	
	render(){
		var body=(<h1>CONTEST ERROR!... Explicame como llegaste aqui...</h1>);		
		var converter = new showdown.Converter();
		if(this.state.page=="problemSet"){			
			var desHtml = converter.makeHtml(this.props.contest.description);
			body = (
				<Tabla tabla={this.state.contest.tabla} dat={dat}
					   page={this.handleProblemChange}/>
			);
		}
		if(this.state.page=="problem"){
			body=(
				<div>
				  <div className="row">
					<div className={"col s2 center-align"}
						 style={{padding:"150 0 0 0"}}
						 onClick={(e)=>(this.cambiar(e, -1))}>
					  <div className={"pinned"}>
						<i className={"material-icons large hoverable"}
						   style={{borderRadius:"50px 0px 0px 50px"}}>{"arrow_back"}
						</i>
					</div></div>
					<div className={"col s8"}>
					  <Problem dat={dat} msg={msg}
							   problem={this.state.problem}/>
					</div>
					<div className={"col offset-s1 s1 center-align"}
						 style={{padding:"150 0 0 0"}}
						 onClick={(e)=>this.cambiar(e, 1)}>
					  <div className={"pinned"}>
						<i className={"material-icons large hoverable"}
						   style={{borderRadius:"0px 50px 50px 0px"}}>arrow_forward</i>
					  </div>
					</div>
				  </div>
				</div>);
		}
		if(this.state.page=="ranking"){
			desHtml = converter.makeHtml(this.props.contest.description);
			body=(
				<div>
				  <div className="row">
					<a className={"btn waves-effect offset-s4 col s4"+
					   dat.st4[localStorage.getItem("skin")]}
					   href={"contestrank.xls.php?cid="+this.props.contest.id}>Download
					  <i className="material-icons right">file_download</i>
					</a>
				  </div>
				  <Tabla dat={dat} tabla={this.props.contest.ranking.tabla}
						 page={this.handleProblemChange}/>
				</div>
			);
		}
		if(this.state.page=="statistics"){			
			body=(
				<div>				  
				  <Tabla dat={dat} tabla={this.props.contest.statistics.tabla} page={this.handleProblemChange}/>
				  <div style={{width:"60%", height:"200px"}}>
					<canvas id="myChart" width="400px" height="400px" ref="canvaMyChart"></canvas>
					
				  </div>
				  <div style={{height:"800px"}}></div>
				</div>
			);
		}
		if(this.state.page=="loading"){
			body=(
				<div className="preloader-wrapper big active">
				  <div className="spinner-layer spinner-blue">
					<div className="circle-clipper left">
					  <div className="circle"></div>
					</div><div className="gap-patch">
					  <div className="circle"></div>
					</div><div className="circle-clipper right">
					  <div className="circle"></div>
					</div>
				  </div>
				</div>);
		}
		var onlyContest;
		if(this.state.contest.onlyContest)
			onlyContest=<h3>Solo estas permitido a ver el Ranking como invitado</h3>;
		return (
			<div>			  
			  <Titulo tit={msg.contest+" - "+this.state.contest.title} dat={dat}/>
			  <center><h5 dangerouslySetInnerHTML={{__html: desHtml}}></h5>
					<div className="fb-like"
						 data-href={"contest.php?cid="+this.state.contest.id}
						 data-layout="button_count"
						 data-action="like" data-show-face="true" data-share="true" ></div>
					<Labelcontesttime now={this.state.contest.now}
									  start={this.state.contest.start}
									  end={this.state.contest.end}
									  tipo={this.state.contest.private}/>
					{onlyContest}{body}</center>
			</div>
		);
	}
}
class Ranking extends React.Component{
	constructor(props){
		super(props);
		this.state={page:"loading", ranking:-1};
		this.handlePageChange = this.handlePageChange.bind(this);
	}
	componentWillMount(){
		console.log("WillMount");
		fetch('https://jv.umsa.bo/api/ranking.php')
			.then((response) => {
				return response.json();
			})
			.then((resp) => {
				console.log(resp);
				this.setState({page:"ranking",
							   ranking:resp});
			});
	}
	handlePageChange(changedPage){
		this.setState({page:"loading"});
		fetch('https://jv.umsa.bo/api/'+changedPage)
			.then((response) => {
				return response.json();
			})
			.then((resp) => {
				
				this.setState({page:"ranking",
							   ranking:resp});
			});
	}
	render(){
		console.log(this.state, "Render Rank");
		if(this.state.page=="loading"){
			return (
				<div className="preloader-wrapper big active">
				  <div className="spinner-layer spinner-blue">
					<div className="circle-clipper left">
					  <div className="circle"></div>
					</div><div className="gap-patch">
					  <div className="circle"></div>
					</div><div className="circle-clipper right">
					  <div className="circle"></div>
					</div>
				  </div>
				</div>
			);
		}
		var paginas=[];
		//this.props.dat.pageTotal=parseInt(this.props.dat.pageTotal);
		//this.props.dat.pageSize=parseInt(this.props.dat.pageSize);
		for(var i=0; i<this.state.ranking.pageTotal; i+=this.state.ranking.pageSize){
			var aux=(1+i)+"-"+(i+this.state.ranking.pageSize);
			paginas.push({text:aux,
						  link:"#",
						  click:"ranking.php?start="+i+
						  (this.props.dat.getScope!=""?"&scope="+
						   this.props.dat.getScope:"")});
			
		}
		return (
			<div>
			  <Titulo tit={this.props.msg.ranklist} dat={this.props.dat}/>
			  <form action="userinfo.php">
				<div className="row">
				  <div className="input-field inline col s2">
					<input placeholder="usuario"
						   id="userId"
						   type="text"
						   className={this.props.dat.tx1[localStorage.getItem("skin")]}
						   name="user"
						   defaultValue={this.state.ranking.getUserId}/>
					<label htmlFor="userId"
						   className={this.props.dat.tx1[localStorage.getItem("skin")]+
						   (this.state.ranking.getUserId!=""?" active":"")}>
					  {this.props.msg.user}</label>
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
			  <Tabla dat={dat} tabla={this.state.ranking.tabla}/><br/>
			  <h3>Posiciones</h3>
			  <div className="row">
				{paginas.map((item) => 
							 <a className={"btn waves-effect col s1"+
										   this.props.dat.st4[localStorage.getItem("skin")]}
									onClick={(e)=>this.handlePageChange(e, item.click)}
									href={item.link} key={item.link}><span>{item.text}</span>
								 </a>
							)}</div>
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
				<h1>{this.props.dat.user.rank+". "+this.props.dat.user.id+":"+this.props.dat.user.nick}</h1>
				<h4>{((this.props.dat.user.school!="")?this.props.dat.user.school:"-")
				  +" "+this.props.dat.user.email}</h4>
				<a className={"btn waves-effect col s3"+
				   this.props.dat.st4[localStorage.getItem("skin")]}
				   href={"href=mail.php?to_user=$user"+this.props.dat.user.id}>
				  {this.props.msg.mail}
				  <i className="material-icons right">mail</i>
				</a>
				<div className="row">
				  <div className="col s5">
					<Tabla dat={dat} tabla={this.props.dat.user.tablaSubmit}/>
				  </div>
				  <div className="col s7">
					{this.props.dat.user.problemsAc.map((item) =>
														<span><a className={this.props.dat.st4[localStorage.getItem("skin")]}
														   href={"problem.php?id="+item} key={item} target="_blank">{item}
														</a> </span>
					)}
					
				  </div>
				</div>
				
			{this.props.dat.user.tablaLogs.body.rows.length>0?<Tabla dat={dat} tabla={this.props.dat.user.tablaLogs}/>:""}
			  </div>
			  <Footer dat={dat} msg={msg}/>
			</div>	
		);
	}
}


class Faqs extends React.Component{
	render(){
		var aux=`
#### Me ejecuto en [Debian Linux](http://www.debian.org) . Utilizo [GNU GCC/G++](http://gcc.gnu.org/) para C/C++ y [sun-java-jdk1.6](http://www.oracle.com/technetwork/java/index.html) para Java, dentro de poco me actualizare a Java 8 o 7.

## Mis opciones de compilacion son:

- *C* : \`gcc Main.c -o Main -fno-asm -O2 -Wall -lm --static -std=c99 -DONLINE_JUDGE\`
- *C++* : \`g++ Main.cc -o Main -fno-asm -O2 -Wall -lm --static -std=c++11 -DONLINE_JUDGE\`
- _Java_ : \`javac -J-Xms32m -J-Xmx256m Main.java *Java usa 512M de memoria maxima\`

## Solucion del problema 1000

### Usando C

\`\`\`c
#include <stdio.h>
		
int main(){
    int a,b;
    while(scanf("%d %d",&a, &b) != EOF)
        printf("%d\\n",a+b);
    return 0;
}
\`\`\`

### Usando C++

\`\`\`c++
#include <iostream>
		  
using namespace std;
		  
int main(){
    int a,b;
    while(cin >> a >> b)
        cout << a+b << endl;
    return 0;
}
\`\`\`
### Usando Java:
			   
\`\`\`java
import java.util.*;
			   
public class Main{
    public static void main(String args[]){
        Scanner cin = new Scanner(System.in);
        int a, b;
        while (cin.hasNext()){
            a = cin.nextInt();
			b = cin.nextInt();
			System.out.println(a + b);
		}
	}
}
\`\`\`
 						 
## Mis Respuestas
| Respuesta | Explicación |
| --- | --- |
| Pendiente | Estoy ocupado, en un momento revisare su codigo|
| Pendiente para Juzgar | Los datos de prueba se actualizaron y volvere a revisarlos de nuevo :D|
| Compilando | Estoy compilando su codigo|
| Ejecutando y Juzgando | Estoy evaluando tu codigo|
| Aceptado | OK! todo esta super!|
| Error de Presentación | Tienes un espacio en blanco o una linea en blanco al final|
| Respuesta Incorrecta | Tu codigo no corre para todos los casos, intenta de nuevo ;-)|
| Tiempo Limite Exedido | Tu programa no corre dentro los limites de tiempo|
| Memoria Limite Exedida | Tu programa consume mucha memoria|
| Salida Limte Exedida | Tu programa intento escribir demasiada informacion de salida|
| Error en Tiempo de Ejecución|  Tu programa se desborda o hay división entre cero|
| Error de Compilación | Hay un error en la compilacion|

## Agradecimientos
[HUSTOJ](index.php)
[R1980+](http://code.google.com/p/hustoj/source/detail?r=1980)
`;
		var converter = new showdown.Converter();
		var desHtml = converter.makeHtml(aux);												 
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>
			  <Header dat={dat} msg={msg}/>
			  <div style={{align:'center', width:'90%',
				   marginLeft:'auto', marginRight:'auto', textAlign:'left'}}>
				<p dangerouslySetInnerHTML={{__html: desHtml}}></p>
			  </div>
			  <Footer dat={dat} msg={msg}/>
			</div>	
		);
	}
}

class Contestrank extends React.Component{
	render(){
		var converter = new showdown.Converter();
		var desHtml = converter.makeHtml(this.props.contest.description);
		return (
			<div className={this.props.dat.st1[localStorage.getItem("skin")]}>
			  <Header dat={dat} msg={msg}/>
			  <div style={{align:'center', width:'90%',
				   marginLeft:'auto', marginRight:'auto', textAlign:'center'}}>
				<Titulo tit={this.props.msg.contest+" - "+this.props.dat.contest.title}
						dat={this.props.dat}/>
				<p dangerouslySetInnerHTML={{__html: desHtml}}></p>
				<div className="fb-like"
					 data-href={"contest.php?cid="+this.props.contest.id}
					 data-layout="button_count"
					 data-action="like" data-show-face="true" data-share="true" ></div>
				<Labelcontesttime now={this.props.contest.now}
								  start={this.props.contest.start}
								  end={this.props.contest.end}
								  tipo={this.props.contest.private}/>
				<div className="row">
				  <a className={"btn waves-effect offset-s4 col s4"+
					 this.props.dat.st4[localStorage.getItem("skin")]}
					 href={"contestrank.xls.php?cid="+this.props.contest.id}>Download
					<i className="material-icons right">file_download</i>
				  </a>
				</div>
				<Tabla dat={dat} tabla={this.props.contest.ranking.tabla}/>
			  </div>
			  <Footer dat={dat} msg={msg}/>
			</div>	
		);
	}
}
