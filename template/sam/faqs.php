<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
  <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>

</head>
<body>
  <div id="wrapper">
   <?php require_once("oj-header.php");?>
   <section id="main">
    <hr>
    <p>
      Me ejecuto en <a href="http://www.debian.org/">Debian Linux</a>. 
      Utilizo <a href="http://gcc.gnu.org/">GNU GCC/G++</a> para C/C++ y <a href="http://www.oracle.com/technetwork/java/index.html">sun-java-jdk1.6</a> para Java, dentro de poco me actualizare a Java 8 o 7 . Mis opciones de compilacion son:<br>
    </p>
    <table border="1">
      <tr>
        <td>C:</td>
        <td><font color=blue>gcc Main.c -o Main -fno-asm -O2 -Wall -lm --static -std=c99 -DONLINE_JUDGE</font></td>
      </tr>
      <tr>
        <td>C++:</td>
        <td><font color=blue>g++ Main.cc -o Main -fno-asm -O2 -Wall -lm --static -std=c++11 -DONLINE_JUDGE</font></td>
      </tr>
      <tr>
        <td>Java:</td>
        <td><font color="blue">javac -J-Xms32m -J-Xmx256m Main.java</font>
          <br>
          <font size="-1" color="red">*Java usa 512M de memoria maxima</font>
        </td>
      </tr>
    </table>
    <hr>
    <font color=green>Solucion del problema 1000</font><br>
    <pre>Usando C<font color="blue">
      #include &lt;iostream&gt;
      using namespace std;
      int main(){
      int a,b;
      while(cin >> a >> b)
      cout << a+b << endl;
      return 0;
    }
  </font></pre>
  Usando C++:<br>
  <pre><font color="blue">
    #include &lt;stdio.h&gt;
    int main(){
    int a,b;
    while(scanf("%d %d",&amp;a, &amp;b) != EOF)
    printf("%d\n",a+b);
    return 0;
  }
</font></pre>
<br><br>
Usando Java:<br>
<pre><font color="blue">
  import java.util.*;
  public class Main{
  public static void main(String args[]){
  Scanner cin = new Scanner(System.in);
  int a, b;
  while (cin.hasNext()){
  a = cin.nextInt(); b = cin.nextInt();
  System.out.println(a + b);
}
}
}</font></pre>

<hr>
<font color=green> Mis respuestas son: </font><br>
<hr>

<p><font color=blue>Pending</font> : Estoy ocupado, en un momento revisare su codigo. </p>
<p><font color=blue>Pending Rejudge</font>: Los datos de prueba se actualizaron y volvere a revisarlos de nuevo :D.</p>
<p><font color=blue>Compiling</font> : Estoy compilando su codigo.<br>
</p>
<p><font color="blue">Running &amp; Judging</font>: Estoy evaluando tu codigo.<br>
</p>
<p><font color=blue>Accepted</font> : OK! todo esta super!.<br>
  <br>
  <font color=blue>Presentation Error</font> : Tienes un espacio en blanco o una linea en blanco al final.<br>
  <br>
  <font color=blue>Wrong Answer</font> : Tu codigo no corre para todos los casos, intenta de nuevo ;-).<br>
  <br>
  <font color=blue>Time Limit Exceeded</font> : Tu programa no corre dentro los limites de tiempo.<br>
  <br>
  <font color=blue>Memory Limit Exceeded</font> : Tu programa consume mucha memoria.  <br>
  <br>
  <font color=blue>Output Limit Exceeded</font>: Tu programa intento escribir demasiada informacion de salida<br>
  <br>
  <font color=blue>Runtime Error</font> :  Tu programa se desborda o hay división entre cero.<br>
</p>
<p>  <font color=blue>Compile Error</font> : Hay un error en la compilacion.<br>
  <br>
</p>
<hr>
<center>
  <font color=green size="+2">Sugerencias <a href="bbs.php">Foro</a></font>
</center>
<hr>
<center>
  <table width=100% border=0>
    <tr>
      <td align=right width=65%>
        <font color=green> Agradecimientos a :</font>
        <a href = "index.php"><font color=red>HUSTOJ</font></a> 
        <a href = "http://code.google.com/p/hustoj/source/detail?r=1980"><font color=red>R1980+</font></a></td>
      </tr>
    </table>
  </center>
</section>
</div>
<section id="foot">
  <?php require_once("oj-footer.php");?>
</section>
</body>
</html>