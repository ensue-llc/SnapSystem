# Snap System
Snap System is the underlying architecture for Laravel projects, providing essential functionalities and features.

## Getting Started
### Install package
Install the package using Composer. Run the following command:
```
composer require ensue/snap
```
### Prerequisites
| Package | Minimum Version |
|---------|-----------------|
| Laravel | 10              |
| php     | 8.2             |

### Exception handler
In your **Handler.php** located at **app/Exceptions/Handler.php**, follow these steps: 
1. Remove all existing code within the file.
2. Extend class with **SnapExceptionHandler**
3. Override any methods as needed.

### Publish Config and Language Files (Optional)
You have the option to publish the configuration and language files. Use the following command:
```
php artisan vendor:publish --tag=snap
```

### Generate module
To generate a module, run the following command:
```
php artisan snap:generate <module-name>
```
Make sure to replace **<module-name>** with the actual name of the module you want to generate.

### Extend Model
Extend your model from the **SnapAppModel** class to inherit additional functionalities.

## Module structure
```
app
├── Modules
│   └── <module-name>
│       ├── Controllers
│       │   └── <module-name>Controller.php
│       ├── Database
│       │   └── Models
│       │       └── <module-name>.php
│       ├── Exceptions
│       ├── Filters
│       │   └── <module-name>Filter.php
│       ├── Interfaces
│       │   └── <module-name>Interface.php
│       ├── Middlewares
│       ├── Providers
│       ├── Repositories
│       │   └── <module-name>Repository.php
│       ├── Requests
│       │   ├── <module-name>CreateRequest.php
│       │   └── <module-name>UpdateRequest.php
│       ├── Routes
│       │   ├── api.php
│       │   └── web.php
│       ├── Views
│       └── ServiceProvider.php
└── System
    └── <module-name>
        └── Database
            ├── Exceptions
            ├── Models
            │   └── <module-name>.php
            └── Traits
```

Feel free to customize and expand upon the provided instructions as needed for your project. If you encounter any issues or need further assistance, please don't hesitate to contact us.

## Contributing
Feel free to contribute :)
