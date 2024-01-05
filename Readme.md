# New patito


After many years...  in that time I learned many things, and I decided to change the structure of the Patito.

I think that structure is easier than old Patito

Folder structure similar to MVC, but not is MVC


## composer.json
This file is used by Composer, a dependency manager for PHP. It defines the project's dependencies and other metadata.

## index.php
The main entry point for the web application. It typically initializes the application and handles requests.

## Legacy/Include
- **const.inc.php**: Likely contains constants used throughout the application.  
- **en.php**: Old translations.

## src/PatitoOnlineJudge
The source code of the application.

### Config
- **AppConfig.php**: Configuration settings for the application.

### Controller
- **IndexController.php**: The controller that handles the logic for the index or main page.

### Database
- **DatabaseConnector.php**: Manages database connections.

### Other Files
- **robots.txt**: A text file for web crawlers, specifying how to index the site.
- **SECURITY.md**: A markdown file containing security policies or guidelines.

### View/template/ZaDuckOJ
- **index.php**: The main template file for the application's user interface.
- **Modules/ContestList**: 
  - **ContestList.php**: Manages the logic for displaying contest lists.
  - **Contestsetlist_View.php**: The view file for displaying contest lists.
- **oj-footer.php**: The footer template for the application.
- **oj-header.php**: The header template for the application.

## statics/ZaDuckOJ
Contains static resources.
- **base.css**: The base CSS file for the application's styling.
- **logo.svg**: The application's logo in SVG format.

## vendor
Contains libraries and dependencies managed by Composer.
- **autoload.php**: Composer's autoloader script.
- **composer**: Various Composer-related files, including autoloaders, classmaps, and licenses.

## The new folder structure is:
```
composer.json
index.php
Legacy
└── Include
    ├── const.inc.php
    └── en.php
src
└── PatitoOnlineJudge
    ├── Config
    │   └── AppConfig.php
    ├── Controller
    │   └── IndexController.php
    ├── Database
    │   └── DatabaseConnector.php
    ├── robots.txt
    ├── SECURITY.md
    └── View
        └── template
            └── ZaDuckOJ
                ├── index.php
                ├── Modules
                │   └── ContestList
                │       ├── ContestList.php
                │       └── Contestsetlist_View.php
                ├── oj-footer.php
                └── oj-header.php
statics
└── ZaDuckOJ
    ├── base.css
    └── logo.svg
vendor
├── autoload.php
└── composer
    ├── autoload_classmap.php
    ├── autoload_files.php
    ├── autoload_namespaces.php
    ├── autoload_psr4.php
    ├── autoload_real.php
    ├── autoload_static.php
    ├── ClassLoader.php
    └── LICENSE
```

## Happy Hacking