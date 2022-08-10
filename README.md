# QRCodeReader

QRCodeReader is an application with the objective of interpreting a file in PDF format, identifying a QR Code and translating it.
It also has a protected administration area, where you can view the files sent in the application.

## Technologies
* PHP 8
* Laravel 9
* MySQL

## Prerequisites
* [Docker](https://www.docker.com/community-edition)
* [Docker Compose](https://docs.docker.com/compose/install)

## Setup
* Build and start the service containers:

    ```sh
    $ docker-compose up -d --build
    ```

### Testing
* Command to run tests

    ```sh
    $ docker exec -it qrcodereader-php php artisan test
    ```

### Shut down
* Turn off service containers:

    ```sh
    $ docker-compose down
    ```