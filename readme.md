Small Business Hackathon 2018

The following are the major technologies and their respective versions which are used in this project;


| Technology                                | Version    |
|-------------------------------------------|------------|
| [PHP](http://www.php.net/)                | 7.2.x      |
| [PostgreSQL](https://www.postgresql.org/) | 10.4       |
| [Laravel ](https://laravel.com/)          | 5.6.x      |
| [Laravel API Boilerplate](https://github.com/specialtactics/l5-api-boilerplate) | 0.0.x-dev  |


# Setup instructions

This project uses docker for local development. Use the following instructions to set it up.

## Copy over .env file
First you will need to copy over the default env file so that docker can pick up our project configurations. You can do that using this command;

```bash
composer run post-root-package-install
```

Or if you do not have PHP installed, you can manually copy the file `env/.env.local` to `.env` in the project root.

## Docker Environment Setup

If you do not already have an up-to-date docker set up on your local system, please follow the docker instructions for your OS and install the latest stable version.

 * [Install Docker](https://docs.docker.com/install/linux/docker-ce/fedora/#install-docker-ce-1)
 * [Install Docker Compose](https://docs.docker.com/compose/install/#install-compose)

### Install Docker Proxy

Ensure that you have Docker Proxy installed and running. This can be achieved with the following command;

```bash
docker run -d --name proxy -p 80:80 -v /var/run/docker.sock://tmp/docker.sock:ro jwilder/nginx-proxy
```

Note that to start docker proxy in the future, you can use this command;

```bash
docker start proxy
```

### Build the docker environment for this project

Run the following command in the project root;

```bash
docker-compose up
```

Note that you can specify a "-d" flag if you wish to run it in the background.

The first time you do this, your containers will build, and then start. Hereafter, you will only need to start them (using the same command).

Once all containers start, you should have your environment ready to go!

### Add the project's local url to your hosts file

Add your project's domain to your system hosts file. For example in linux;

```bash
echo '127.0.0.1 api.socialise.local' >> /etc/hosts
```

### Using Workspace

To SSH into the project's workspace, execute the following command from the project root;

```bash
./env/workspace.sh
```

From there you can use artisan and composer commands, inside your development environment.


### Using Docker

To run this project in docker hereafter, execute the following commands to bring the project up;

```bash
docker start proxy
docker-compose up -d
```

And the following commands to take it down;

```bash
docker-compose down
```

If you are running docker-compose in the foreground (ie. without the "-d" flag), just terminate it normally to stop the docker containers for the project.

Some other useful commands;

- `docker-compose restart` - Restart the containers
- `docker-compose exec nginx bash` - Get into a container (nginx in this case)

## Installation instructions

Once your environment is set up, run the following commands in the workspace;

```bash
composer install
php artisan key:generate
php artisan jwt:secret
```

You should now be able to go to <http://api.socialise.local> and see a welcome message for the API. 

## Database

The current MariaDB configuration uses the following details:

 * Host: localhost
 * User: laradock
 * Password: secret
 * Database: socialise

To connect to the DB locally, use localhost as the hostname.

# Development 

## Documentation

To work on the project, ensure that you are familiar with the following documentation; 

 * [Laravel Docs](https://laravel.com/docs/5.6/readme)
 * [API Boilerplate Docs](https://github.com/specialtactics/l5-api-boilerplate#boilerplate-documentation)

## Coding Standards
The PHP code sniffer coding standards are defined in phpcs.xml.

Please set them up in PHPStorm, you can find information about how to do that [documented by JetBrains](https://confluence.jetbrains.com/display/PhpStorm/PHP+Code+Sniffer+in+PhpStorm#PHPCodeSnifferinPhpStorm-1.EnablePHPCodeSnifferintegrationinPhpStorm). 

## Building
To build the project, run the following command:

```bash
composer build
```

## Automated Testing
This project is using phpunit with Laravel's testing helpers.

To run tests, use the following command;

```bash
composer test
```