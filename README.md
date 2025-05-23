# Prado Starter Project

A Project skeleton to build [PRADO Framework][PRADO] based web application.  
_Prado3 is a Component Framework for PHP 5 (Like ASP .NET dose for C#)._

[![Join the chat at https://gitter.im/prado-project-starter/Lobby](https://badges.gitter.im/prado-project-starter/Lobby.svg)](https://gitter.im/prado-project-starter/Lobby?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)


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
1. Check that your web server is running (where the project is deployed).
2. Check that your RDBS is running (if you use one).
3. Type the URL of your project in the browser. Ex : `http://localhost/project_name`.
4. The web app will show you the default page of Frontoffice.
5. To see the Backoffice, click on the link **"Espace d'administration"** (Backoffice space in frensh, sorry :)) :
   + A login page will show.
   + Type one of these credentials to access the web app as authenticated user :
   
      Username | Password | Role        
      --- | --- | ---
       sadmin |sadmin  |Super Admin 
       admin  |admin   |Normal Admin
       user   |user    |Normal User 
   + And bingo you can manage : **users**, **site settings**  

## Documentation :
The project is divided in two parts : 
* FO : Front office
* BO : Back office

So `protected/pages`, `protected/pages` and `themes` directories are splited into two sub directories : **fo** and **bo**. 

The **themes** directory holde the css, js files needed to render the IHM of each side of you web app. Ex: `themes\bo` contains sb-admin css and js files used in backoffice IHM.

The theme used in the backoffice is the [SB-admin 2014 version](https://startbootstrap.com/theme/sb-admin-2). For the front office is not implemented for instance.

### Helpers functions :
I added a bunch of helper functions to help developper access most used features of Prado easily.  
Ex: 
```php
app();
//Shortcut to Prado::getApplication();
user();
//shortcut to Prado::getApplication()->getUser();
```
And so on ...  
Lets now take a tour of these functions :  

#### Request/Response :
Function | Return Type |Description
:--- | --- | :---
`session()` |`THttpSession`| Return the current user session.
`request()` |`THttpRequest`| Return the current request.
`response()` |`THttpResponse`| Return the current response. 
`input($name [, $default = null] )` |`string`| Retrieve a request input by its name or return a default value. 
`page()` | `TPage|NPage` | Return the current processed **Page**.
`redirect_page($page [, array $param])` || Redirect the user to a given page. **$page** is the namespace format to the page **dir_name.sub_dir_name.PageName**. For example : bo.users.ListPage_
`redirect_url($url)` || Redirect the user to a given url. 
`page_url($page [, array $param] )` |`string`| Contruct an URL pointing to a given page. **$page** is the namespace format to the page **dir_name.sub_dir_name.PageName**. For example : bo.users.ListPage_ 
`site_url($uri)` | `string` | Build an Url to a resource which is located under the root directory of the project. The param **base_url** is used here.


#### Security :
Function | Return Type |Description
:--- | --- | :---
`auth()` | `TAuthManager` | Return the Authentication Manager.
`user()` |`IUser|TDbUser|NDbUser`| Return the instance of the authenticated user.
`is_guest()` |`bool`| True if the user is not authenticated in the system. 
`is_admin()` |`bool`| True if the authenticated user has one of the roles : **Super Admin** or **Normal Admin** 
`is_super_admin()` |`bool`| True if the authenticated user has the role : **Super Admin**

#### General use :
Function | Return Type |Description
:--- | --- | :---
`app()` | `TApplication` | Return the current running Prado Application instance.
`module($id)` |`IModule`| Get a module by its id. 
`running_service()` |`IService`| Return the current running service that processing the request. For instance it returns the `TPageService`
`page_service()` |`TPageService`| If another service is processing the request, this function throw an error.
`param($id [, $default = null] )` | `string` | Retrieve a given parameter from the `config/parameters` file by its name. If not found, the `$default` value will be returned.
`using($namespace)` |  | Shortcut to `\Prado::using( $namespace );`. I think is more readable this way.
`site_info($name [, $default = ''] )` | `string` | Return a site setting by its name. If not found returns the default value.
`mysql_timestamp(int $time)` | `string` | Format the given time to the TIMESTAMP format requied by Mysql. Shortcut to `date( 'Y-m-d H:i:s', $time );`
`check_is_int(mixed $v)` | `bool` | True if the **$v** is numeric and (is int or is string and is only integer). See source code for more details.

#### Localisation :
Function | Return Type |Description
:--- | --- | :---
`localize($text [, array $parameters [, $catalogue = null [, $charset = null]]] )` |`string`| Translate and localize a given `$text` based on the i18n and i10l of your application. 


#### File System :
Function | Return Type |Description
:--- | --- | :---
`file_extension($filename)` | `string` | Returns the file extension.

## These are the links used in this document

[PhpStorm]: https://www.jetbrains.com/phpstorm/
[PDO Driver]: http://php.net/manual/en/pdo.drivers.php
[Sqlite3]: https://www.sqlite.org/
[PRADO]: http://www.pradoframework.net/site/

## LICENSE : 
[MIT](./LICENSE)
