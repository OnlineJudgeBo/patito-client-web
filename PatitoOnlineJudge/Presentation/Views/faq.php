<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href='https://fonts.googleapis.com/css?family=Capriola' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/base.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
</head>

<body class="flex flex-col h-full">
    <?php require_once "oj-header.php" ?>
    <main class="container mx-auto p-4 grid grid-cols-0">

        <section id="respuestas-del-juez" class="max-w-6xl mx-auto px-5 py-10">
            <h1 class="text-2xl font-bold text-center mb-5">Preguntas Frecuentes (FAQ)</h1>
            <p class="text-lg text-gray-700 text-center mb-10">
                Bienvenido a la sección de Preguntas Frecuentes (FAQ) de nuestro Juez Online. Aquí encontrarás información detallada sobre los distintos estados de respuesta que podrás encontrarte al enviar tus soluciones de programación. Si tienes alguna duda sobre qué significa cada estado, este es el lugar indicado para aclararla.
            </p>
            <ul class="list-disc space-y-2 pl-5">
                <li><a href="#compiladores" class="text-blue-600 hover:text-blue-800">Versiones de Compiladores</a></li>
                <li><a href="#ejemplo-envio-solucion" class="text-blue-600 hover:text-blue-800">Ejemplo de Envío de Solución</a></li>
                <li><a href="#respuestas-del-juez" class="text-blue-600 hover:text-blue-800">Respuestas del Juez</a></li>
            </ul>
        </section>

        <hr>
        <section id="ejemplo-envio-solucion" class="max-w-6xl mx-auto px-5 py-10">
            <h2 class="text-2xl font-bold text-center mb-5">Versiones de Compiladores</h2>
            <div class="bg-white shadow-md rounded-lg p-6">
                <ul class="divide-y divide-gray-200">
                    <li class="py-4 flex justify-between items-center">
                        <span class="text-gray-600">Compilador de C++</span>
                        <span class="font-medium text-green-500">G++ 6.3.0</span>
                    </li>
                    <li class="py-4 flex justify-between items-center">
                        <span class="text-gray-600">Compilador de Java</span>
                        <span class="font-medium text-green-500">JDK 1.8.0_201</span>
                    </li>
                    <li class="py-4 flex justify-between items-center">
                        <span class="text-gray-600">Compilador de Python</span>
                        <span class="font-medium text-green-500">Python 3.12.0a1</span>
                    </li>
                </ul>
            </div>
        </section>

        <section id="ejemplo-envio-solucion" class="max-w-6xl mx-auto px-5 py-10">
            <h2 class="text-2xl font-bold text-center mb-5">Ejemplo de la Solución para el Problema A + B</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white shadow-md rounded p-4">
                    <h2 class="text-lg font-bold mb-2">Solución usando C</h2>
                    <pre class="bg-gray-100 p-3 overflow-auto">
#include &lt;iostream&gt;
using namespace std;
int main(){
  int a,b;
  while(cin >> a >> b)
    cout << a+b << endl;
  return 0;
}
            </pre>
                </div>

                <div class="bg-white shadow-md rounded p-4">
                    <h2 class="text-lg font-bold mb-2">Solución usando C++</h2>
                    <pre class="bg-gray-100 p-3 overflow-auto">
#include &lt;stdio.h&gt;

int main(){
  int a,b;
  while(scanf("%d %d",&a, &b) != EOF)
    printf("%d\n",a+b);
  return 0;
}
            </pre>
                </div>

                <div class="bg-white shadow-md rounded p-4">
                    <h2 class="text-lg font-bold mb-2">Solución usando Java</h2>
                    <pre class="bg-gray-100 p-3 overflow-auto">
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
}
            </pre>
                </div>

                <div class="bg-white shadow-md rounded p-4">
                    <h2 class="text-lg font-bold mb-2">Solución usando Python</h2>
                    <pre class="bg-gray-100 p-3 overflow-auto">
print("Hola mundo!")
            </pre>
                </div>
            </div>
        </section>
        <hr>

        <section class="max-w-6xl mx-auto px-5 py-10">
            <h2 class="text-2xl font-bold text-center mb-5">El juez proporcionará las siguientes respuestas</h2>
            <article>
                <div class="mb-8 p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <h2 class="text-xl font-semibold mb-2 text-blue-700">¿Qué significa el estado "Pending"?</h2>
                    <p class="text-gray-600">
                        El estado "Pending" indica que tu solución ha sido recibida y está en cola para ser procesada. Por favor, sé paciente mientras llega tu turno.
                    </p>
                </div>
            </article>

            <article>
                <div class="mb-8 p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <h2 class="text-xl font-semibold mb-2 text-blue-700">¿Qué significa el estado "Pending Rejudge"?</h2>
                    <p class="text-gray-600">
                        "Pending Rejudge" significa que los datos de prueba para el problema que resolviste se han actualizado, y tu solución será reevaluada con esta nueva información. Este proceso asegura la justicia y la precisión en la evaluación.
                    </p>
                </div>

            </article>

            <article>
                <div class="mb-8 p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <h2 class="text-xl font-semibold mb-2 text-blue-700">¿Qué significa el estado "Compiling"?</h2>
                    <p class="text-gray-600">
                        El estado "Compiling" indica que el sistema está compilando tu código. Este es un paso previo a la ejecución de tu solución para verificar su correcto funcionamiento.
                    </p>
                </div>
            </article>

            <article>
                <div class="mb-8 p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <h2 class="text-xl font-semibold mb-2 text-blue-700">¿Qué significa el estado "Accepted"?</h2>
                    <p class="text-gray-600">
                        Accepted! Si tu solución tiene el estado "Accepted", significa que ha pasado todas las pruebas y cumple con los requisitos del problema. Es el mejor resultado posible.
                    </p>
                </div>
            </article>

            <article>
                <div class="mb-8 p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <h2 class="text-xl font-semibold mb-2 text-blue-700">¿Qué significa el estado "Presentation Error"?</h2>
                    <p class="text-gray-600">
                        Un "Presentation Error" ocurre cuando tu solución es correcta en términos de lógica y resultado, pero el formato de salida no coincide exactamente con lo esperado. Revisa espacios y saltos de línea.
                    </p>
                </div>
            </article>

            <article>
                <div class="mb-8 p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <h2 class="text-xl font-semibold mb-2 text-blue-700">¿Qué significa el estado "Wrong Answer"?</h2>
                    <p class="text-gray-600">
                        El estado "Wrong Answer" indica que tu solución no produce el resultado correcto para los casos de prueba proporcionados. Te recomendamos revisar tu lógica y probar con diferentes entradas.
                    </p>
                </div>
            </article>

            <article>
                <div class="mb-8 p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <h2 class="text-xl font-semibold mb-2 text-blue-700">¿Qué significa el estado "Time Limit Exceeded"?</h2>
                    <p class="text-gray-600">
                        Si recibes un "Time Limit Exceeded", significa que tu solución no se ejecutó dentro del tiempo máximo permitido para el problema. Considera optimizar tu código para que sea más eficiente.
                    </p>
                </div>
            </article>

            <article>
                <div class="mb-8 p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <h2 class="text-xl font-semibold mb-2 text-blue-700">¿Qué significa el estado "Memory Limit Exceeded"?</h2>
                    <p class="text-gray-600">
                        "Memory Limit Exceeded" Este estado aparece cuando tu programa intenta usar más memoria de la que está permitida. Necesitas encontrar una manera de reducir el uso de memoria de tu solución.
                    </p>
            </article>

            <article>
                <div class="mb-8 p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <h2 class="text-xl font-semibold mb-2 text-blue-700">¿Qué significa el estado "Output Limit Exceeded"?</h2>
                    <p class="text-gray-600">
                        "Output Limit Exceeded" significa que tu programa ha intentado generar más salida de la esperada o permitida. Esto puede ocurrir en bucles infinitos o en la generación excesiva de datos.
                    </p>
            </article>

            <article>
                <div class="mb-8 p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <h2 class="text-xl font-semibold mb-2 text-blue-700">¿Qué significa el estado "Runtime Error"?</h2>
                    <p class="text-gray-600">
                        Un "Runtime Error" indica que tu programa ha fallado durante la ejecución debido a errores como divisiones por cero, acceso a memoria no válida, etc. Revisa tu código en busca de posibles errores de ejecución.
                    </p>
            </article>

            <article>
                <div class="mb-8 p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <h2 class="text-xl font-semibold mb-2 text-blue-700">¿Qué significa el estado "Compile Error"?</h2>
                    <p class="text-gray-600">
                        Un "Compile Error" significa que tu código tiene errores que impiden su compilación. Esto puede deberse a sintaxis incorrecta, tipos de datos incompatibles, etc. Revisa tu código fuente para corregir estos errores.
                    </p>
            </article>
        </section>

    </main>
    <?php require_once "oj-footer.php" ?>
</body>

</html>