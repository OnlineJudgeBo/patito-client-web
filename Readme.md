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

## Licencia

Este proyecto se distribuye bajo la **Apache License 2.0**, una licencia de código abierto que permite usar, modificar y distribuir el software, incluso con fines comerciales.

## Contribuidores

A continuación se listan las personas que han contribuido al proyecto:

- **Samuel Loza** - Mantenedor original - [github.com/samueelloza](https://github.com/samueelloza)

La lista se actualizará conforme se incorporen nuevas contribuciones
