## GDT Cashier

GDT Cashier backend app and API resources.

## Installation

- Make .env file:

```shell
$ cp -r .env.example .env
```

- Specific Docker ports:

```shell
DOCKER_PMA_PORT=7080
DOCKER_WEB_PORT=8080
DOCKER_WEB_HOST="gdt-cashier.local"
```

- Update database credentials:

```shell
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_ROOT_PASSWORD=root
DB_DATABASE=gdt_cashier_db
DB_USERNAME=gdt_admin
DB_PASSWORD=gdt_admin_password
```

- Update app information:

```shell
APP_NAME="GDT Cashier"
APP_URL="http://gdt-cashier.local:8080"
```

- Generate app key:

```shell
$ php artisan key:generate
```

- Start Docker containers:

```shell
$ docker-compose up -d
```
