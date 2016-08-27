# Prado Starter Project
A Project skeleton to build PRADO Framework based application

`composer create-project enimiste/prado-project-starter project_name`

## Database
For using sqlite database `data/app.db` you should have Sqlite client installed.
To use Mysql Database uncomment :
`<database ConnectionString="mysql:host=localhost;dbname=test"
                                   username="dbuser" password="dbpass" />`
                                   in config file `config/database.xml`.
## After install
- Change the parameter `base_url` in the file `config/parameters.xml`
- Update database information in the config file `config/database.xml`

## Notation in project structure
- fo : Front office
- bo : Back office