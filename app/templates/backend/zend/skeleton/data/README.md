# Configuration
For entity generation, you need to add a __database.jh__ file in the __data__ directory, from these files will be generated the __JSON__ entities in the directory.   

The __.yo-rc.json__ file may receive the configuration of the following database:
* mysql
* mariadb
* postgresql
* oracle
* mssql
* mongodb
* couchbase
* cassandra

The __JSON__ generator files from jdl must be contained within the __(my-project)/data/json-files__ folder which is set as the default folder for importing the files.

```html
Info: By default the file configuration is for postgresql base.
```

# Generate file

To generate the __JSON__ files it is necessary to previously have jhipster installed.

```
npm i -g generator-jhipster
```

Files can be generated with the following command:

```
cd (my-project/data)

jhipster import-jdl my-file-name.jh --json-only
``` 