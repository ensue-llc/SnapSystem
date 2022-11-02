# Nico System
Underlying architecture for laravel project

## Getting Started
### 1. Package addition
Since our package is not hosted in packagist. Open composer.json file and update the following object.
```
"repositories": [
    {
        "type": "vcs",
        "url": "https://bitbucket.org/LYWL/nicosystem.git"
    }
]
```

### 2. Install package
Now composer will also look into this repository for any installable package. Execute the following command to install the package:
```
composer require ensue/nicosystem
```
Authenticate the installation process by providing username and password.

## Optional setup
### Publish nicosystem config file
```
php artisan vendor:publish --tag=nicosystem
```
