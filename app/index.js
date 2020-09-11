'use strict';
var EntityPrompt = require('./entity/prompts');
const chalk = require('chalk');
const _ = require('lodash');
const fs = require('fs');

class GeneratorSymfony extends EntityPrompt {

    askClass() {
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

    askForNewApp() {
        var fs = require('fs');
        this.prompt([{
                type: 'input',
                name: 'appName',
                message: 'What is the application\'s name: ',
                validate: function(input) {
                    if (!/^[a-zA-Z\-0-9_]+$/.exec(input)) {
                        return "Invalid Directory Name!";
                    }
                    if (fs.existsSync('./' + input)) {
                        return "Directory already exists!";
                    }
                    return true;
                }
            }, {
                type: 'list',
                name: 'appFramework',
                message: 'What is the Framework:',
                choices: [{
                    name: 'Symfony Framework',
                    value: 'symfony'
                }, {
                    name: 'Zend Expressive',
                    value: 'zend'
                }]
            }, {
                when: response => response.appName !== '',
                type: 'list',
                name: 'dbType',
                message: 'What is the database used: ',
                default: 'postgresql',
                choices: [{
                    name: 'Mysql',
                    value: 'PDOMySql'
                }, {
                    name: 'PostgreSQL',
                    value: 'PDOPgSql'
                }]
            },
            {
                type: 'input',
                name: 'dbPort',
                message: 'What is database Port: ',
                default: function(response) {
                    if (response.dbType === 'PDOMySql') {
                        return '3306';
                    } else if (response.dbType === 'PDOPgSql') {
                        return '5432';
                    }
                    return '';
                }
            },
            {
                type: 'input',
                name: 'dbName',
                message: 'What is database Name: ',
            },
            {
                type: 'input',
                name: 'dbUser',
                message: 'What is database User: ',
            },
            {
                type: 'password',
                name: 'dbPassword',
                message: 'What is database Password: ',
            }
        ]).then((answers) => {
            var context = this;
            console.log('Wait creating skeloton zend application...');
            if (answers.dbType === 'PDOMySql') {
                answers.dbPortDefault = '3306';
            } else if (answers.dbType === 'PDOPgSql') {
                answers.dbPortDefault = '5432';
            }
            if (answers.appFramework === 'zend') {
                this.createSkelotonApplitaionZend(answers);
            } else {
                this.createSkelotonApplitaionSymfony(answers);
            }
        });
    }

    createSkelotonApplitaionSymfony(props) {
        var fs = require('fs');
        var apiPath = "/api";
        if (!fs.existsSync(props.appName)) {
            props.appType = 'symfony';
            this.writeDockerConfig(props, apiPath);
            this.writeFileSkeleton('backend/symfony/skeleton', props.appName + apiPath, { props: props });
            this.writeFileSkeleton('frontend/skeleton', props.appName + '/client', { props: props });
            this.writeFileSkeleton('backend/symfony/docker', props.appName + '/docker', { props: props });
            this.writeFileSkeleton('backend/symfony/files/.env', props.appName + apiPath + '/.env', { props: props, _: _ });
            this.writeFileSkeleton('backend/symfony/files/.env', props.appName + apiPath + '/.env.test', { props: props, _: _ });
            this.writeFileSkeleton('backend/symfony/files/.htaccess', props.appName + apiPath + '/public/.htaccess', { props: props, _: _ });
            //this.spawnCommandSync('mkdir', ['-p', props.frontendRoot]);
            console.log('\n Now you need follow the steps for de run your application symfony');
            console.log('\n Step - 1: Entry into the directory ' + props.appName + ' with command ' + chalk.blue('cd ' + props.appName));
            console.log('\n Step - 2: Execute the download of the dependencies with the command ' + chalk.blue('composer install'));
            console.log('\n Step - 3: Create or import your entities with the command ' + chalk.blue(' yo symfony-command-bus'));
        }
    }

    createSkelotonApplitaionZend(props) {
        var fs = require('fs');
        var apiPath = "/api";
        if (!fs.existsSync(props.appName)) {
            props.appType = 'zend';
            this.writeDockerConfig(props, "");
            this.writeFileSkeleton('backend/zend/skeleton', props.appName + apiPath, { props: props });
            this.writeFileSkeleton('frontend/skeleton', props.appName + '/client', { props: props });
            this.writeFileSkeleton('backend/zend/docker', props.appName + '/docker', { props: props });
            this.writeFileSkeleton('backend/zend/skeleton/config/autoload/doctrine.global.php', props.appName + apiPath + '/config/autoload/doctrine.global.php', { props: props });
            this.writeFileSkeleton('backend/zend/files/_.htaccess', props.appName + apiPath + '/public/.htaccess', { props: props });
            //this.spawnCommandSync('mkdir', ['-p', props.frontendRoot]);
            console.log('\n Now you need follow the steps for de run your application symfony');
            console.log('\n Step - 1: Entry into the directory ' + props.appName + ' with command ' + chalk.blue('cd ' + props.appName));
            console.log('\n Step - 2: Execute the download of the dependencies with the command ' + chalk.blue('composer install'));
            console.log('\n Step - 3: Create or import your entities with the command ' + chalk.blue(' yo zf-restful-crud'));
        }
    }

    writeDockerConfig(props, apiPath) {
        //docker-compose
        if (props.dbType === 'PDOMySql') {
            props.db = 'mysql';
            this.writeFileSkeleton('backend/docker/mysql/docker-compose.yml', props.appName + '/docker-compose.yml', { props: props });
            this.writeFileSkeleton('backend/docker/mysql/Dockerfile', props.appName + '/Dockerfile', { props: props });
        } else if (props.dbType === 'PDOPgSql') {
            props.db = 'pgsql';
            this.writeFileSkeleton('backend/docker/postgres/docker-compose.yml', props.appName + '/docker-compose.yml', { props: props });
            this.writeFileSkeleton('backend/docker/postgres/Dockerfile', props.appName + '/Dockerfile', { props: props });
        }
        this.writeFileSkeleton('backend/docker/.yo-rc.json', props.appName + '/.yo-rc.json', { props: props });
    }

    writeFileSkeleton(to, from, params) {
        this.fs.copyTpl(
            this.templatePath(to),
            this.destinationPath(from),
            params
        );
    }
}

module.exports = class extends GeneratorSymfony {
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