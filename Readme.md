# Patito Online Judge — cliente web

Cliente web en PHP para JV Patito Online Judge.

## Inicio rápido con datos de demostración

El directorio [`docker/`](docker/) contiene un entorno de prueba:

- Esquema de MariaDB sin información real
- Problemas demo
- Concursos demo
- Envíos
- Cuenta de estudiante
- Cuenta administrador
- Todo esta dockerizado

### Requisitos

- Docker 24 o superior
- Docker Compose

### Instalación

```bash
docker compose -f docker/compose.yml up --build
```

Después abre:

- Aplicación: <http://localhost:8082/oj/>

### Cuentas de prueba

| Rol | Usuario | Contraseña |
|---|---|---|
| Estudiante | `patito` | `patito` |
| Administrador | `patitoAdmin` | `patitoAdmin` |

## Estructura de carpetas

```text
client-web/
├── PatitoOnlineJudge/
│   ├── Config/                 # Configuración de la aplicación y base de datos
│   ├── Core/
│   │   ├── Application/        # Servicios y casos de uso
│   │   └── Domain/             # Contratos y objetos del dominio
│   ├── Infrastructure/         # Repositorios e integraciones externas
│   └── Presentation/
│       ├── Controller/         # Controladores HTTP
│       ├── Middleware/         # Autenticación y validaciones de acceso
│       ├── Utils/              # Utilidades de presentación
│       └── Views/
│           ├── Modules/        # Componentes compartidos entre templates
│           ├── patito/         # Template público predeterminado
│           ├── itboliviamar/   # Template institucional
│           ├── juezvirtual/    # Template institucional
│           └── jvbo/           # Template institucional
├── Legacy/
│   └── Include/                # Constantes y traducciones heredadas
├── public/
│   ├── Routing/                # Definición de rutas HTTP
│   └── assets/                 # CSS, JavaScript, imágenes y editores
├── docker/                     # Entorno local, esquema y datos de demostración
├── vendor/                     # Dependencias instaladas por Composer
├── composer.json
└── Readme.md
```

## Templates

Los templates se encuentran en `PatitoOnlineJudge/Presentation/Views/`. La variable `THEME_TEMPLATE` determina cuál de ellos utiliza la aplicación:

```dotenv
THEME_TEMPLATE=patito
```

## Configuración

La aplicación busca primero `.env.local` y utiliza `.env` como alternativa..

---

## Historia del proyecto

Patito Online Judge tiene sus orígenes documentados en 2012, cuando comenzaron las primeras pruebas e instalaciones de sistemas de juez en línea orientados al entrenamiento y enseñanza de programación competitiva en la Universidad Mayor de San Andrés (UMSA).

Durante las primeras etapas del proyecto se evaluaron distintas alternativas para disponer de una plataforma de envío y evaluación automática de soluciones. Finalmente se adoptó HUSTOJ como base, sobre la cual se realizaron trabajos de instalación, configuración, traducción, adaptación y desarrollo de nuevas funcionalidades para adecuarla a las necesidades académicas y competitivas de la universidad.

Las primeras pruebas e instalaciones fueron realizadas en servidores de la UMSA, permitiendo la creación de concursos, el entrenamiento de estudiantes y la utilización de la plataforma en distintas asignaturas relacionadas con programación y algoritmos.

A lo largo de los años el sistema evolucionó mediante numerosas modificaciones en la interfaz, el sistema de evaluación, la administración de concursos, la gestión de problemas, la infraestructura y las herramientas de soporte. Una parte importante de esta evolución ocurrió antes de la adopción del repositorio Git actual, por lo que la historia registrada en Git representa únicamente una fracción del trabajo realizado desde los inicios del proyecto.

Actualmente la plataforma continúa siendo utilizada para cursos, entrenamientos y competencias de programación, manteniendo el objetivo original de facilitar el aprendizaje y la práctica de algoritmos y estructuras de datos.

## Personas y contribuciones

Diversas personas participaron en distintas etapas del desarrollo, despliegue y uso de la plataforma. Las fechas indicadas son aproximadas.

Debido a que gran parte de la historia temprana del proyecto no fue documentada y ocurrió antes de la adopción de las herramientas actuales de control de versiones, esta lista puede ser incompleta o contener imprecisiones. **Si alguna persona que contribuyó al proyecto no aparece mencionada, o si existe información que deba corregirse o ampliarse, agradecezco que me lo hagan saber para actualizar este registro histórico. o crear un PR**

### Jorge Terán (2014–presente)

Impulsor académico de la iniciativa. Promovió el uso de la plataforma en actividades de enseñanza, entrenamiento y concursos de programación, además de contribuir con la creación, selección y organización de problemas utilizados en cursos y competencias.

### Samuel Loza (2012-presente)

Responsable de las primeras instalaciones, adaptación de HUSTOJ, despliegue de servidores, personalización de la plataforma y mantenimiento continuo del sistema. Ha participado activamente en la evolución técnica y operativa del proyecto desde sus primeras etapas hasta la actualidad.

### Jhonatan Castro (2013–2014)

Apoyo en infraestructura, coordinación y tareas relacionadas con las etapas iniciales de despliegue y puesta en funcionamiento de la plataforma.

### Branimir Espinoza (2012–2014)

Colaborador en pruebas técnicas, validación de instalaciones y experimentación con distintas configuraciones durante las primeras etapas de desarrollo.

### Oscar Gauss (2018)

Participó en el desarrollo y evaluación de una propuesta de modernización de la interfaz web (rect) y ampliación de funcionalidades durante 2018. El trabajo incluyó prototipos relacionados con APIs, páginas de usuario, rankings, concursos y soporte para expresiones matemáticas mediante MathJax.

Parte de estas propuestas fueron utilizadas como experimentos de desarrollo y evaluación, aunque no llegaron a incorporarse de forma permanente en las versiones posteriores de la plataforma.

## Línea de tiempo

* **2012**: Primeras pruebas documentadas con sistemas de juez en línea. Instalación, configuración y experimentación con HUSTOJ, incluyendo validación de lenguajes, compilación del judge, ajustes de configuración y adaptación inicial de la plataforma.

* [Pruebas de código y configuración en HUSTOJ - Registro 1](https://ideone.com/yhu8Yc)
* [Pruebas de código y configuración en HUSTOJ - Registro 2](https://ideone.com/13cHHw)
  
* **2013**: Continuación de pruebas técnicas, evaluación de alternativas y preparación de infraestructura para el uso académico y de entrenamiento en programación competitiva.

* **2014**: Despliegue de las primeras instancias utilizadas por estudiantes y docentes de la UMSA. Personalización de la plataforma, traducción de componentes, adaptación a requerimientos locales e incorporación progresiva en actividades académicas.

* **2014-2017**: Consolidación de la plataforma como herramienta de apoyo para cursos, entrenamientos y competencias de programación. Desarrollo continuo de mejoras funcionales y operativas.

* **2018**: Desarrollo y evaluación de propuestas de modernización de la interfaz web, nuevos componentes para concursos y rankings, experimentos con APIs y soporte para expresiones matemáticas mediante MathJax.

* **2019-presente**: Evolución continua de la plataforma mediante mejoras en infraestructura, administración de concursos, gestión de problemas, soporte para nuevos entornos de ejecución, herramientas académicas y mantenimiento general del sistema.

* **Actualidad**: Uso continuo de la plataforma en cursos, entrenamientos y competencias de programación.

Agradecemos a la comunidad de HUSTOJ por proporcionar la base tecnológica que hizo posible el inicio de este proyecto.

---

## Mantenimiento Actual

* Samuel Loza - Mantenedor principal y desarrollador activo.

La lista se actualizará conforme se incorporen nuevas contribuciones
