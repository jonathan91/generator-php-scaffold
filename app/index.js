'use strict';
var EntityPrompt = require('./entity/prompts');
const chalk = require('chalk');
const _ = require('lodash');

class GeneratorSymfony extends EntityPrompt {

    askClass() 
    {
        this.prompt([{
            type: 'confirm',
            name: 'newClass',
            message: 'Do you want create a new entity: ',
            store: true
        }]).then((answers) => {
            if (answers.newClass) {
                this.askForNewEntity.call(this);
            }
        });
    }

    askForNewApp() 
    {
        var fs = require('fs');
        this.prompt([{
            type: 'input',
            name: 'appName',
            message: 'What is the application\'s name: ',
            validate: function (input) {
                if (!/^[a-zA-Z\-0-9_]+$/.exec(input)) {
                    return "Invalid Directory Name!";
                }
                if (fs.existsSync('./' + input)) {
                    return "Directory already exists!";
                }
                return true;
            }
        },{
            type: 'list',
            name: 'appFramework',
            message: 'What is the Framework:',
            choices:[{
                name: 'Symfony Framework',
                value: 'symfony'
            },{
                name: 'Zend Framework',
                value: 'zend'
            }]
        },{
            when: response => response.appName !== '',
            type: 'list',
            name: 'dbType',
            message: 'What is the database used: ',
            default: 'postgresql',
            choices:[{
                name: 'Mysql',
                value: 'PDOMySql'
            },{
                name: 'PostgreSQL',
                value: 'PDOPgSql'
            },{
                name: 'Oracle',
                value: 'PDOOracle'
            },{
                name: 'MS SQL',
                value: 'PDOSqlsrv'
            }]
        },{
            type: 'input',
            name: 'dbPort',
            message: 'What is database Port: ',
            default: function (response){
                if(response.dbType === 'PDOMySql'){
                    return '3306';
                }else if(response.dbType === 'PDOPgSql'){
                    return '5432';
                }else if(response.dbType === 'PDOOracle'){
                    return '1521';
                }else if(response.dbType === 'PDOSqlsrv'){
                    return '1433';
                }
                return '';
            }
        },{
            type: 'input',
            name: 'dbName',
            message: 'What is database Name: ',
        },{
            type: 'input',
            name: 'dbUser',
            message: 'What is database User: ',
        },{
            type: 'password',
            name: 'dbPassword',
            message: 'What is database Password: ',
        },/* {
            type: 'text',
            name: 'frontendRoot',
            message: 'What is frontend path(path absolute): ',
        } */

    ]).then((answers) => {
            var context = this;
            console.log('Wait creating skeloton zend application...');
            if(answers.dbType === 'PDOMySql'){
                answers.dbPortDefault = '3306';
            }else if(answers.dbType === 'PDOPgSql'){
                answers.dbPortDefault = '5432';
            }else if(answers.dbType === 'PDOOracle'){
                answers.dbPortDefault = '1521';
            }else if(answers.dbType === 'PDOSqlsrv'){
                answers.dbPortDefault = '1433';
            }
            if(answers.appFramework === 'zend'){
                this.createSkelotonApplitaionZend(answers);
            }else{
                this.createSkelotonApplitaionSymfony(answers);
            }
        });
    }

    createSkelotonApplitaionSymfony(props)
    {
        var fs = require('fs');
        if (!fs.existsSync(props.appName)) {
            this.writeDockerConfig(props);
            this.writeFileSkeleton('backend/symfony/skeleton', props.appName, {props: props});
            this.writeFileSkeleton('frontend/skeleton', props.appName+'/client', {props: props});
            this.writeFileSkeleton('backend/symfony/skeleton/data/.yo-rc.json', props.appName+'/data/.yo-rc.json', {});
            this.writeFileSkeleton('backend/symfony/files/parameters.yml', props.appName+'/app/config/parameters.yml', {props: props, _: _});
            this.writeFileSkeleton('backend/symfony/files/parameters.yml', props.appName+'/app/config/parameters.yml.dist', {props: props, _: _});
            this.writeFileSkeleton('backend/.yo-rc.json', props.appName+'/app/config/.yo-rc.json', {props: props});
            //this.spawnCommandSync('mkdir', ['-p', props.frontendRoot]);
            console.log('\n Now you need follow the steps for de run your application symfony');
            console.log('\n Step - 1: Entry into the directory '+props.appName+' with command '+chalk.blue('cd '+props.appName));
            console.log('\n Step - 2: Execute the download of the dependencies with the command '+chalk.blue('composer install'));
            console.log('\n Step - 3: Create or import your entities with the command '+chalk.blue(' yo symfony-command-bus'));
        }
    }

    createSkelotonApplitaionZend(props)
    {
        var fs = require('fs');
        if (!fs.existsSync(props.appName)) {
            this.writeDockerConfig(props);
            this.writeFileSkeleton('backend/zend/skeleton', props.appName, {props: props});
            this.writeFileSkeleton('frontend/skeleton', props.appName+'/client', {props: props});
            this.writeFileSkeleton('backend/zend/skeleton/config/autoload/doctrine_orm.global.php', props.appName+'/config/autoload/doctrine_orm.global.php', {props: props});
            this.writeFileSkeleton('backend/zend/files/_.htaccess', props.appName+'/public/.htaccess', {props: props});
            this.writeFileSkeleton('backend/.yo-rc.json', props.appName+'/config/.yo-rc.json', {props: props});
            //this.spawnCommandSync('mkdir', ['-p', props.frontendRoot]);
            console.log('\n Now you need follow the steps for de run your application symfony');
            console.log('\n Step - 1: Entry into the directory '+props.appName+' with command '+chalk.blue('cd '+props.appName));
            console.log('\n Step - 2: Execute the download of the dependencies with the command '+chalk.blue('composer install'));
            console.log('\n Step - 3: Create or import your entities with the command '+chalk.blue(' yo zf-restful-crud'));
        }
    }

    writeDockerConfig(props){
        //docker-compose
        if(props.dbType === 'PDOMySql'){
            props.db = 'mysql';
            this.writeFileSkeleton('backend/docker/mysql/docker-compose.yml', props.appName+'/docker-compose.yml', {props: props});
            this.writeFileSkeleton('backend/docker/mysql/Dockerfile', props.appName+'/Dockerfile', {props: props});
        }else if(props.dbType === 'PDOPgSql'){
            props.db = 'postgresql';
            this.writeFileSkeleton('backend/docker/postgres/docker-compose.yml', props.appName+'/docker-compose.yml', {props: props});
            this.writeFileSkeleton('backend/docker/postgres/Dockerfile', props.appName+'/Dockerfile', {props: props});
        }else if(props.dbType === 'PDOSqlsrv'){
            props.db = 'mssql';
            this.writeFileSkeleton('backend/docker/sqlserver/docker-compose.yml', props.appName+'/docker-compose.yml', {props: props});
            this.writeFileSkeleton('backend/docker/sqlserver/Dockerfile', props.appName+'/Dockerfile', {props: props});
        }else {
            props.db = 'oracle';
            this.writeFileSkeleton('backend/docker/oracle/docker-compose.yml', props.appName+'/docker-compose.yml', {props: props});
            this.writeFileSkeleton('backend/docker/oracle/Dockerfile', props.appName+'/Dockerfile', {props: props});
        }
        this.writeFileSkeleton('backend/docker/.yo-rc.json', props.appName+'/data/.yo-rc.json', {props: props});
    }

    writeFileSkeleton(to, from, params)
    {
        this.fs.copyTpl(
            this.templatePath(to),
            this.destinationPath(from), 
            params
        );
    }
}

module.exports = class extends GeneratorSymfony 
{    
    constructor(args, opts) {
        super(args, opts);
        this.log('Initializing...');
    }
    
    start() {
        const done = this.async();
        this.prompt([{
            type: 'confirm',
            name: 'checkApp',
            message: 'The application already exist: ',
        }]).then((answers) => {
            if (answers.checkApp) {
                this.askForNewEntity.call(this);
            } else {
                this.askForNewApp();
            }
            done();
        });
    }
};