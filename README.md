# Prado Starter Project
A Project skeleton to build [PRADO Framework][PRADO] based web application.  
_Prado3 is a Component Framework for PHP 5 (Like ASP .NET dose for C#)._

## Pre-requisites :
* php : >=5.3.3
* ext-ctype
* ext-dom
* ext-json
* ext-pcre
* ext-spl

## Installation :

Firstly run this command : 
```sh
$ cd path/to/web/server/www
$ composer create-project enimiste/prado-project-starter project_name
```
If all goes right open the new project in your favorite IDE. (ex: [PhpStorm])

### Setup the Database schema and data :
You can use many types of database as it is supported by [PDO Driver].  
In this section i will focus on two database types : **Sqlite** and **Mysql**.
* __Sqlite__ :  
  + Prequisites :
    - Installing Sqlite3 from [Sqlite3].
    - Enabling PHP Sqlite extension on `php.ini` file
  + The project is bundled with the database file located under `protected/data/app.db`.
  + This database is seeded with the needed data. So it is ready to use for next steps.
* __Mysql__ :  
  You have two options : *Mysql* extension or *Mysqli* extension  
  So you should be sure that the desired extension is enabled in `php.ini` file before using it.
  1. Open the `protected/config.database.xml` file.
  2. Comment the sqlite config : 
  
     ```xml
     <database ConnectionString="sqlite:protected/data/app.db"/>
     ```
  3. Uncomment the Mysql config (mysql or mysqli) : 
  
     ```xml
     <!-- <database ConnectionString="mysqli:host=localhost;dbname=test"
                      username="dbuser" password="dbpass" /> -->
     <!-- <database ConnectionString="mysql:host=localhost;dbname=test"
                          username="dbuser" password="dbpass" /> -->
     ```
  4. Create an empty database named as `;dbname=` in the step 3.
  5. Update **host**, **username** and **password** with the correct ones.
  6. Open your Mysql console and run the following statements in order :
     * The content of `protected/data/migrations` directory files (*.sql)
     * The content of `protected/data/seeds` directory files (*.sql)

### Parameters config :
Now its time to update our parameters config located in the file `protected/config/parameters.xml`.
Ex : 
 ```xml
   <parameter id="base_url" value="http://localhost/project_name/"/>
 ```
 This parameter is used to build assets urls (css, javascripts, ...) via the function `site_url($uri)`

## Test in browser :
Now you can test the project.  
1. Check if your web server under it the project is deployed is running.
2. Check if your RDBS is running if you use one.
3. Type the URL to your project in the browser. Ex : `http://localhost/project_name`.
4. The web app will show you the default page of Frontoffice.
5. To see the Backoffice, click on the link **"Espace d'administration"** (Backoffice space in frensh, sorry) :
   + A login page will show.
   + Type one of these credentials to access the web app as authenticated user : 
   
      |Username|Password|Role        |
      |--------|--------|------------|
      | sadmin |sadmin  |Super Admin |
      | admin  |admin   |Normal Admin|
      | user   |user    |Normal User |
      
   + And bingo you can manage : **users**, **site settings**  


[//]:# (These are the links used in this document.)

[PhpStorm]: https://www.jetbrains.com/phpstorm/
[PDO Driver]: http://php.net/manual/en/pdo.drivers.php
[Sqlite3]: https://www.sqlite.org/
[PRADO]: http://www.pradoframework.net/site/