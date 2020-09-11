# Project
This project is a code generator for the Zend Framework or Symfony, to generate the project skeleton the Resful architecture was used and the following packages are used to create the backend:
* zend framework / Symfony
* league / oauth2-client 2.3
* doctrine / doctrine-module-0,10
* doctrine / doctrine-module 1.1

In creating the frontend was used Angular 7 together as the Material Design the link to the layout can be found here:
* https://www.creative-tim.com/product/material-dashboard-angular2  

# What the `php-scaffold` do?
When started the `php-scaffold` generator suggests creating a project or pointing to an existing project, after that you can create your `Modules` and their `Entities`, and all the necessary files for the application to work will be created as based on the data provided.

## Instalation
You need to install Yeoman and the generator.
Yeoman and generator installation is required with the following commands:
```
npm i -g yo
```
```
npm i -g generator-php-scaffold
```
## Usage
To use the generator you must enter the folder where you want the application to be created.

```
cd ~/home/Documents/my_projects
```
To start the generator execution the following command must be executed.
```
yo php-scaffold
```

After the construction of the application structure for the creation of entities simply access the folder defined for the project and rerun the call to the generator.

```
cd ~/Documents/proects/my_project
```
```
yo php-scaffold
```

To Run the application yo need to execute the line command following.

```
sudo docker-compose up -d
```
```
sudo docker ps -a
```
Get the container id and runner the following line command to update the database

```
sudo docker exec -it <container-id> bash
```

```
vendor/bin/doctrine orm:schema-tool:update --force
```

>Obs. To import the jdl files you need first convert to JSON file

```
npm install -g generator-jhipster
```
```
jhipster import-jdl ./data/my-jdl-file.jdl --json-only
```