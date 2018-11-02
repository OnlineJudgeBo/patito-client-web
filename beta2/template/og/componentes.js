const colori = {
			sw: 1,
			a: 'orange',
			b: 'green'
};

function estFoot(colori){
		return "page-footer "+colori.a+" darken-4";
}

 var myvar = "Hey Buddy";

'<%Session["temp"] = "' + myvar +'"; %>' ;

alert('<%=Session["temp"] %>');


function Pie(){

		return (
						<footer class={estFoot(colori)}>
          <div class="container">
            <div class="row">
              <div class="col l6 s12">
                <h5 class="white-text">Juez Virtual</h5>
                <p class="grey-text text-lighten-4">Juez Virtual de la Universidad Mayor de San Andrés</p>
              </div>
              <div class="col l4 offset-l2 s12">
                <h5 class="white-text">Jueces</h5>
                <ul>
                  <li><a class="grey-text text-lighten-3" href="http://codeforces.com/">CodeForeces</a></li>
                  
                </ul>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container">
Hola Mundo!
            <a class="grey-text text-lighten-4 right" href="https://www.facebook.com/JuezPatito">Facebook</a>
            </div>
          </div>
        </footer>


		);
}

ReactDOM.render(
				<Pie />,
    document.getElementById('Piecito')
);





class Toggle extends React.Component {
			constructor(props) {
			super(props);
			this.state = {isToggleOn: true};

			// This binding is necessary to make `this` work in the callback
			this.handleClick = this.handleClick.bind(this);
			}

			handleClick() {
			if(colori.sw==1){
			colori.a='green'; colori.sw=0;
			}else{colori.a='orange'; colori.sw =1;}
			this.setState(prevState => ({
      isToggleOn: !prevState.isToggleOn
			}));
			this.cargar();
			}
			cargar(){
			
			  ReactDOM.render(
      <h1 class={colori.a}>Hola Mundo!</h1>,
      document.getElementById('root')
      );
			}

			render() {
			return (
      <button onClick={this.handleClick}>
        {this.state.isToggleOn ? 'ON' : 'OFF'}
				{colori.a}{colori.sw}
      </button>
			);
			}
			}

			ReactDOM.render(
			<Toggle />,
			document.getElementById('togle')
			);
