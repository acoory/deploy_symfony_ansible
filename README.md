<p align="center">
    <img src="https://img.shields.io/badge/made%20with-SYMFONY-red?style=for-the-badge">
    <img src="https://img.shields.io/badge/deploy%20with-ansible-blue?style=for-the-badge">
</p>

# Ecommerce Back-End With deploy ansible

# Description 🗒️
As part of our apprenticeship, we worked as a team to automate the deployment of a Symfony 6.2 project with a MariaDB database on a Debian 11 server using Ansible. This experience gave us hands-on skills in configuration management, deployment and maintenance of IT systems, as well as working with a development team in an agile environment.

# Technologies 🛠

The following technologies were used in the development of this project:
#### Backend 📡

- [Symfony](https://symfony.com/) <img src="https://img.shields.io/badge/Symfony6.2-000000?style=for-the-badge&logo=Symfony6.2&logoColor=white" alt="Symfony6.2 logo">
- [MariaDB](https://mariadb.com/) <img src="https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white" alt="Mysql">

#### Outils 🔧

- [Postman](https://www.postman.com/) <img src="https://img.shields.io/badge/Postman-%23FF6600.svg?&style=for-the-badge&logo=postman&logoColor=white" alt="Postman logo">


#### Deployment 📡

- [Ansible](https://www.ansible.com/) <img src="https://img.shields.io/badge/Ansible-000000?style=for-the-badge&logo=ansible&logoColor=white" alt="Ansible logo">

# How to install 💻

PHP 8.2 or higher is required
####  Clone and install symfony
```
- Clone this repository on your machine.
- Install symfony on your computer https://symfony.com/doc/current/setup.html
- Create the .env file at the root of the project and add the following
- Configure your .env by modifying the following bases for your configuration 
- <YourUserDatabase> <YourPassword> <databaseName> <yourPassPhrase>
```
#### .ENV
```
###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=a41b7536a8cdcbd1f0e73432e4974646
###< symfony/framework-bundle ###

# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
DATABASE_URL="mysql://<YourUserDatabase>:<YourPassword>@127.0.0.1:3306/<databaseName>?serverVersion=8&charset=utf8mb4"
# DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=15&charset=utf8"
###< doctrine/doctrine-bundle ###

###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=<yourPassPhrase>
###< lexik/jwt-authentication-bundle ###
```


#### - install database and create
```
- Install mysql or mariadb
- Go to the root of the project and execute the following command :

$ php bin\console doctrine:schema:update --force
```

#### - Run project
```
$ symfony server:start
```

## Deployment
I to let you know that for the project deployment phase, I utilized Ansible. Ansible proved to be an efficient tool for automating the deployment process and ensuring consistency across multiple environments. 

If you want to deploy this project you will find the deploy folder inside it is inventory.ini mysql.yml and playbook.yml
Make sure you have a server with debian 11 for this

```bash
  ansible-playbook -i inventory.ini mysql.yml playbook.yml 
```
