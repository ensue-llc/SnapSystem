# Nico System
Underlying architecture for laravel project

## Getting Started
### Package addition
Since our package is not hosted in packagist. Open composer.json file and update the following object.
```
"repositories": [
    {
        "type": "vcs",
        "url": "https://bitbucket.org/LYWL/nicosystem.git"
    }
]
```

### Install package
Now composer will also look into this repository for any installable package. Execute the following command to install the package:
```
composer require ensue/nicosystem
```
Authenticate the installation process by providing username and password.

### Exception handler
In **Handler.php** located in app/Exceptions/Handler.php 
1. Remove all code in it
2. Extend class with **BaseExceptionHandler**
3. Override if required

### Publish config file [Optional]
```
php artisan vendor:publish --tag=nicosystem
```

### Publish lang file [Optional]
```
php artisan vendor:publish --tag=appconstant
```

### Generate module
Run command:
```
php artisan generate:nicoModule <module-name>
```

### Extend Model
Extend model form the AppBaseModel
