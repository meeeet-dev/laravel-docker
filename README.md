# Initialize a Docker for Laravel projects

#### Help you to create `docker-compose.yml` and `Dockerfile` as well as `Database Volume` and `Network`

[![Latest Version on Packagist](https://img.shields.io/packagist/v/meeeet-dev/laravel-docker.svg?style=flat-square)](https://packagist.org/packages/meeeet-dev/laravel-docker)
[![Total Downloads](https://img.shields.io/packagist/dt/meeeet-dev/laravel-docker.svg?style=flat-square)](https://packagist.org/packages/meeeet-dev/laravel-docker)
![GitHub Actions](https://github.com/meeeet-dev/laravel-docker/actions/workflows/main.yml/badge.svg)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require meeeet-dev/laravel-docker
```

## Usage

1. Simply Run the `docker:install` command with your `image` name and your bridge `network` name

    ```shell
    # You can run php artisan docker:install --help to see all the options available
    php artisan docker:install yourimagename yournetworkname
    ```

    - It will ask you to choose your php version and then it will ask you to create the bridge network and create the database volume for the database.

    -  Note:
        - *Do not delete the `docker-compose.yml` file and `.docker` folder.*
        -  *After successfully publishing all the docker config, the command will ask you whether to uninstall itself since its work is done. If you have no further use of it, you can proceed to uninstall it.*

<!-- 2. COPY all the .env variables published in a file named `.env.docker` to your current env file, just below `APP_URL`. Modify the variables as necessary. -->

2. Change Database and Docker Variables in `.env` according to your need.

    ```bash
    # Sample Database Variables
    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=secret
    DB_PASSWORD=secret
    ```

3. Now you can run `docker-compose build app` to build your image

4. Then `docker-compose up -d` to run your services.
    - Do not forget to run following commands on installation to get started: 
        `docker-compose exec app composer install`
        `docker-compose exec app php artisan migrate` to run the migrations
        `docker-compose exec app php artisan storage:link` to link storage
        `docker-compose exec app npm install` to install npm packages
        

    *Note: run `docker-compose down` to stop your services*

5. Done. Happy Coding!

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email meeeet.dev@gmail.com instead of using the issue tracker.

## Credits

-   [Meeeet Dev](https://github.com/meeeet-dev) - Creator and Maintainer of the new package
-   [Sam Maosa](https://github.com/savannabits) - Creator of the original package
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
