const chalk = require('chalk');
const memFs = require('mem-fs');
const editor = require('mem-fs-editor');
const fs = require('fs');
const _ = require('lodash');
const util = require('../util/util');
const Generator = require('yeoman-generator');
var index = 0;
var itensFields = [];

module.exports = class extends Generator {

    askForField(answers) {
        const done = this.async();
        const prompts = [{
                type: 'confirm',
                name: 'fieldAdd',
                message: 'Do you want to add a field to your entity?',
                default: true
            },
            {
                when: response => response.fieldAdd === true,
                type: 'input',
                name: 'fieldName',
                validate: (input) => {
                    if (!(/^([a-zA-Z0-9_]*)$/.test(input))) {
                        return 'Your field name cannot contain special characters';
                    } else if (input === '') {
                        return 'Your field name cannot be empty';
                    } else if (input.charAt(0) === input.charAt(0).toUpperCase()) {
                        return 'Your field name cannot start with an upper case letter';
                    }
                    return true;
                },
                message: 'What is the name of your field?',
            },
            {
                when: response => response.fieldAdd === true,
                type: 'list',
                name: 'fieldType',
                message: 'What is the type of your field?',
                choices: [{
                        value: 'string',
                        name: 'String'
                    },
                    {
                        value: 'integer',
                        name: 'Integer'
                    },
                    {
                        value: 'float',
                        name: 'Float'
                    },
                    {
                        value: 'decimal',
                        name: 'Decimal'
                    },
                    {
                        value: 'binary',
                        name: 'Binary'
                    },
                    {
                        value: 'bool',
                        name: 'Boolean'
                    },
                    {
                        value: 'string',
                        name: 'Text'
                    },
                    {
                        value: 'date',
                        name: 'Date'
                    },
                    {
                        value: 'time',
                        name: 'Time'
                    },
                    {
                        value: 'datetime',
                        name: 'Date Time'
                    },
                    {
                        value: 'class',
                        name: 'Class field to relationship'
                    },
                    {
                        value: 'datetimetz',
                        name: 'Date Timetz'
                    },
                    {
                        value: 'array',
                        name: 'Array'
                    },
                    {
                        value: 'simple_array,',
                        name: 'Simple Array,'
                    },
                    {
                        value: 'json_array',
                        name: 'Json Array'
                    },
                    {
                        value: 'object',
                        name: 'Object'
                    },
                    {
                        value: 'blob',
                        name: 'Blob'
                    },
                    {
                        value: 'guid',
                        name: 'Guid'
                    },
                    {
                        value: 'smallint',
                        name: 'Smallint'
                    },
                    {
                        value: 'bigint',
                        name: 'bigint'
                    },

                ],
                default: 0
            },
            {
                when: (response) => response.fieldAdd === true && response.fieldType === 'class',
                type: 'list',
                name: 'relationshipType',
                message: 'What is the type of the relationship?',
                choices: (response) => {
                    const opts = [{
                            value: 'many-to-one',
                            name: 'Many To One'
                        },
                        {
                            value: 'many-to-many',
                            name: 'Many To Many'
                        },
                        {
                            value: 'one-to-one',
                            name: 'One to One'
                        }
                    ];
                    return opts;
                },
                default: 'one-to-one'
            },
            {
                when: (response) => response.fieldAdd === true && response.fieldType === 'class',
                type: 'input',
                name: 'otherEntityName',
                validate: (input) => {
                    if (input === '') {
                        return 'Your class name cannot be empty.';
                    }
                    if (!/^[A-Za-z0-9_]*$/.test(input)) {
                        return 'Your class name cannot contain special characters (allowed characters: A-Z, a-z, 0-9 and _)';
                    }
                    return true;
                },
                message: 'What is the class name of your field?'
            },
            {
                when: (response) => response.fieldAdd === true && response.fieldType === 'decimal',
                type: 'input',
                name: 'fieldPrecision',
                validate: (input) => {
                    if (input === '') {
                        return 'Your class precision cannot be empty.';
                    }
                    if (!/^[0-9]*$/.test(input)) {
                        return 'Your precision cannot contain letters or special characters (allowed characters: 0-9)';
                    }
                    return true;
                },
                message: 'What is the precision of field?',
                default: 10,
            },
            {
                when: (response) => response.fieldAdd === true && response.fieldType === 'decimal',
                type: 'input',
                name: 'fieldScale',
                validate: (input) => {
                    if (input === '') {
                        return 'Your class scale cannot be empty.';
                    }
                    if (!/^[0-9]*$/.test(input)) {
                        return 'Your scale cannot contain letters or special characters (allowed characters: 0-9)';
                    }
                    return true;
                },
                message: 'What is the scale of field?'
            },
            {
                when: response => response.fieldAdd === true,
                type: 'confirm',
                name: 'fieldValidate',
                message: 'Do you want to add validation rules to your field?',
                default: false
            },
            {
                when: response => response.fieldAdd === true && response.fieldValidate === true,
                type: 'checkbox',
                name: 'fieldValidateRules',
                message: 'Which validation rules do you want to add?',
                choices: (response) => {
                    const opts = [{
                            name: 'Required',
                            value: 'required'
                        }, {
                            name: 'Unique',
                            value: 'unique'
                        }, {
                            name: 'Minimum length',
                            value: 'minlength'
                        }, {
                            name: 'Maximum length',
                            value: 'maxlength'
                        }
                        /*, {
                            name: 'Regular expression pattern',
                            value: 'pattern'
                        }*/
                    ];
                    return opts;
                },
                default: 0
            },
            {
                when: response => response.fieldAdd === true &&
                    response.fieldValidate === true &&
                    response.fieldValidateRules.includes('minlength'),
                type: 'input',
                name: 'fieldValidateRulesMin',
                validate: input => (_.isNumber(parseInt(input)) ? true : 'Minimum length must be a positive number'),
                message: 'What is the minimum length of your field?',
                default: 0
            },
            {
                when: response => response.fieldAdd === true &&
                    response.fieldValidate === true &&
                    response.fieldValidateRules.includes('maxlength'),
                type: 'input',
                name: 'fieldValidateRulesMax',
                validate: input => (_.isNumber(parseInt(input)) ? true : 'Maximum length must be a positive number'),
                message: 'What is the maximum length of your field?',
                default: 60
            },
            {
                when: response => response.fieldAdd === true &&
                    response.fieldValidate === true &&
                    response.fieldValidateRules.includes('pattern'),
                type: 'input',
                name: 'fieldValidateRulesPattern',
                message: 'What is the regular expression pattern you want to apply on your field?',
                default: '^[a-zA-Z0-9]*$'
            }
        ];
        this.prompt(prompts).then((props) => {
            if (props.fieldAdd) {
                this.addFields(props);
                this.askForField.call(this, done);
            } else {
                this.askToContinue();
            }
        });
    }

    addFields(props) {
        itensFields[index].fields.push({
            fieldName: props.fieldName,
            fieldType: _.lowerCase(props.fieldType),
            relationshipType: _.upperFirst(_.camelCase(props.relationshipType)),
            otherEntityName: _.upperFirst(_.camelCase(props.otherEntityName)),
            fieldValidate: props.fieldValidate,
            fieldValidateRules: ((props.fieldValidate === true) ? props.fieldValidateRules : []),
            fieldValidateRulesPattern: props.fieldValidateRulesPattern,
            fieldValidateRulesMin: props.fieldValidateRulesMin,
            fieldValidateRulesMax: props.fieldValidateRulesMax
        });
    }

    askForNewEntity() {
        this.prompt([{
            type: 'list',
            name: 'typeCreation',
            message: 'What do you like, create entity or import file',
            choices: [{
                name: 'Create new entity',
                value: 'create'
            }, {
                name: 'Import file\'s jdl',
                value: 'import'
            }]
        }, {
            when: response => response.typeCreation === 'create',
            type: 'input',
            name: 'className',
            message: 'What is the class\'s name: ',
            validate: function(input) {
                if (input.charAt(0) !== input.charAt(0).toUpperCase()) {
                    return "Invalid Class Name! The first character must be uppercase.";
                }
                return true;
            }
        }, {
            when: response => response.typeCreation === 'import',
            type: 'input',
            name: 'pathName',
            message: 'What is the path of jdl file: ',
            default: 'data/json-files'
        }]).then((answers) => {
            if (answers.typeCreation === 'create') {
                itensFields[index] = {
                    entity: 'App.' + answers.className,
                    fields: []
                };
                this.askForField.call(this);
            } else {
                var store = memFs.create();
                var fsr = editor.create(store);
                var listFiles = fs.readdirSync(answers.pathName);
                if (fs.existsSync(answers.pathName)) {
                    listFiles.forEach(file => {
                        var contentFile = fsr.read(answers.pathName + '/' + file);
                        this.mapJsonEntity('App', JSON.parse(contentFile));
                    });
                    var values = '';
                    itensFields.forEach(element => {
                        values = element.entity.split('.')
                        this.writeBackendFiles(element, values);
                        this.writeFrontendFiles(element);
                    });
                } else {
                    console.log(chalk.red('Erro: The files path has not exist.'));
                }
            }
        });
    }

    mapJsonEntity(bundle, entity) {
        itensFields[index] = {
            entity: bundle + '.' + _.upperFirst(_.camelCase(entity.entityTableName)),
            fields: []
        };

        entity.fields.forEach(element => {
            itensFields[index].fields.push({
                fieldName: element.fieldName,
                fieldType: _.lowerCase(element.fieldType),
                otherEntityName: '',
                fieldValidateRules: (element.fieldValidateRules) ? element.fieldValidateRules : [],
            });
        });
        entity.relationships.forEach(element => {
            itensFields[index].fields.push({
                fieldName: element.relationshipName,
                fieldType: 'class',
                relationshipType: _.upperFirst(_.camelCase(element.relationshipType)),
                otherEntityName: _.upperFirst(_.camelCase(element.otherEntityName)),
                fieldValidateRules: (element.fieldValidateRules) ? element.fieldValidateRules : [],
            });
        });
        index++;
    }

    askToContinue() {
        this.prompt([{
            type: 'confirm',
            name: 'check',
            message: 'Do you want create a new entity: ',
        }]).then((answers) => {
            if (answers.check) {
                index++;
                this.askForNewEntity.call(this);
            } else {
                var values = '';
                this.log('Creating files...');
                itensFields.forEach(element => {
                    values = element.entity.split('.');
                    this.writeBackendFiles(element, values);
                    this.writeFrontendFiles(element);
                });
                this.log('After execution do you have execute composer dump-autoload to update your modules');
            }
        });
    }

    writeBackendFiles(props, answers) {
        const me = this;
        fs.readFile('.yo-rc.json', 'utf-8', function(error, content) {
            let path = JSON.parse(content);
            if (_.toUpper(path.config.appFramework) === 'ZEND') {
                me.writeBackendZend(props);
                me.writeConfigServiceZend(answers[0], answers[1]);
            } else {
                me.writeBackendSymfony(props);
                me.writeConfigServiceSymfony(answers[1]);
            }
        });
    }

    writeBackendZend(props) {
        var values = props.entity.split('.');
        var types = [{
                'type': 'Post'
            },
            {
                'type': 'Put'
            },
            {
                'type': 'Delete'
            }
        ];
        var values = props.entity.split('.');
        types.forEach(element => {
            props.type = element.type;
            values[1] = _.startCase(values[1]).replace(' ', '');
            this.writeFile(
                'backend/zend/files/Command.php',
                'api/src/App/Command/' + values[1] + '/' + element.type + 'Command.php',
                values[0],
                values[1],
                props
            );
            this.writeFile(
                'backend/zend/files/Handler.php',
                'api/src/App/Handler/' + values[1] + '/' + element.type + 'Handler.php',
                values[0],
                values[1],
                props
            );
            this.writeFile(
                'backend/zend/files/MiddlewareFactory.php',
                'api/src/App/Factory/' + values[1] + '/' + element.type + 'MiddlewareFactory.php',
                values[0],
                values[1],
                props
            );
            this.writeFile(
                'backend/zend/files/Middleware.php',
                'api/src/App/Middleware/' + values[1] + '/' + element.type + 'Middleware.php',
                values[0],
                values[1],
                props
            );
        });
        this.writeFile(
            'backend/zend/files/GetMiddlewareFactory.php',
            'api/src/App/Factory/' + values[1] + '/GetMiddlewareFactory.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/zend/files/GetMiddleware.php',
            'api/src/App/Middleware/' + values[1] + '/GetMiddleware.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/zend/files/Entity.php',
            'api/src/App/Entity/' + values[1] + '.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/zend/files/Query.php',
            'api/src/App/Query/' + values[1] + 'Query.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/zend/files/Repository.php',
            'api/src/App/Repository/' + values[1] + 'Repository.php',
            values[0],
            values[1],
            props
        );
    }

    writeBackendSymfony(props) {
        var types = [{
                'type': 'Post'
            },
            {
                'type': 'Put'
            },
            {
                'type': 'Delete'
            }
        ];
        var values = props.entity.split('.');
        values[1] = _.startCase(values[1]).replace(' ', '');
        types.forEach(element => {
            props.type = element.type;
            this.writeFile(
                'backend/symfony/files/Command.php',
                'api/src/Service/Command/' + values[1] + '/' + element.type + 'Command.php',
                values[0],
                values[1],
                props
            );
            this.writeFile(
                'backend/symfony/files/Handler.php',
                'api/src/Service/Handler/' + values[1] + '/' + element.type + 'Handler.php',
                values[0],
                values[1],
                props
            );
        });
        this.writeFile(
            'backend/symfony/files/Query.php',
            'api/src/Service/Query/' + _.upperFirst(_.camelCase(values[1])) + 'Query.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/symfony/files/Repository.php',
            'api/src/Repository/' + _.upperFirst(_.camelCase(values[1])) + 'Repository.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/symfony/files/Controller.php',
            'api/src/Controller/' + _.upperFirst(_.camelCase(values[1])) + 'Controller.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/symfony/files/Entity.php',
            'api/src/Entity/' + _.upperFirst(_.camelCase(values[1])) + '.php',
            values[0],
            values[1],
            props
        );
    }

    writeFrontendFiles(props) {
        const me = this;
        fs.readFile('.yo-rc.json', 'utf-8', function(error, content) {
            let path = JSON.parse(content);
            let config = path.config;
            var values = props.entity.split('.');
            values[1] = _.kebabCase(values[1]).toLowerCase();
            me.writeFile(
                'frontend/files/entity-detail.component.html',
                config.frontendDir + '/src/app/' + _.toLower(values[1]) + '/' + _.toLower(values[1]) + '-detail.component.html',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity-detail.component.ts',
                config.frontendDir + '/src/app/' + _.toLower(values[1]) + '/' + _.toLower(values[1]) + '-detail.component.ts',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity-form.component.html',
                config.frontendDir + '/src/app/' + _.toLower(values[1]) + '/' + _.toLower(values[1]) + '-form.component.html',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity-form.component.ts',
                config.frontendDir + '/src/app/' + _.toLower(values[1]) + '/' + _.toLower(values[1]) + '-form.component.ts',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity.component.html',
                config.frontendDir + '/src/app/' + _.toLower(values[1]) + '/' + _.toLower(values[1]) + '.component.html',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity.component.ts',
                config.frontendDir + '/src/app/' + _.toLower(values[1]) + '/' + _.toLower(values[1]) + '.component.ts',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity.model.ts',
                config.frontendDir + '/src/app/' + _.toLower(values[1]) + '/models/' + _.toLower(values[1]) + '.model.ts',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity.module.ts',
                config.frontendDir + '/src/app/' + _.toLower(values[1]) + '/' + _.toLower(values[1]) + '.module.ts',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity.route.ts',
                config.frontendDir + '/src/app/' + _.toLower(values[1]) + '/' + _.toLower(values[1]) + '.route.ts',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity.service.ts',
                config.frontendDir + '/src/app/' + _.toLower(values[1]) + '/services/' + _.toLower(values[1]) + '.service.ts',
                values[0],
                values[1],
                props
            );
            me.writeConfigFrontend(values[1]);
        });
    }

    writeConfigFrontend(entity) {
        const me = this;
        fs.readFile('.yo-rc.json', 'utf-8', function(error, content) {
            let path = JSON.parse(content);
            let config = path.config;
            util.rewriteFile({
                path: config.frontendDir + '/src/app/layouts/admin-layout/',
                file: 'admin-layout.routing.ts',
                needle: 'needle-add-router',
                splicable: [
                    `{ path: '${_.kebabCase(entity).toLowerCase()}', loadChildren: '../../${_.kebabCase(entity).toLowerCase()}/${_.kebabCase(entity).toLowerCase()}.module#${_.startCase(entity).replace(' ', '')}Module' },`
                ]
            }, me);

            util.rewriteFile({
                path: config.frontendDir + '/src/app/components/sidebar/',
                file: 'sidebar.component.ts',
                needle: 'needle-menu-item',
                splicable: [
                    `{ path: '/${_.kebabCase(entity).toLowerCase()}', title: '${_.upperFirst(entity)}',  icon: 'bookmark', class: '' },`
                ]
            }, me);
        });
    }

    writeFile(origin, destination, bundle, entity, props) {
        var fs = require('fs');
        if (!fs.existsSync(destination)) {
            this.fs.copyTpl(
                this.templatePath(origin),
                this.destinationPath(destination), {
                    packageName: bundle,
                    className: values[1] = _.startCase(entity).replace(' ', ''),
                    attributs: props,
                    _: _
                }
            );
        } else {
            console.log(chalk.yellow('Warning the file: ') + destination + chalk.yellow(' already exist'));
        }
    }

    writeConfigServiceSymfony(entity) {
        util.rewriteFile({
            path: 'api/config/',
            file: 'services.yaml',
            needle: 'Map-Services-Entity',
            splicable: [
                `# ${entity}
    App\\Service\\Query\\${entity}Query:
        class: App\\Service\\Query\\${entity}Query
        arguments:
           - '@doctrine.orm.entity_manager'
    App\\Service\\Handler\\${entity}\\PostHandler:
        class: App\\Service\\Handler\\${entity}\\PostHandler
        arguments:
           - '@doctrine.orm.entity_manager'
        tags:
           - { name: tactician.handler, command: App\\Service\\Command\\${entity}\\PostCommand }
    App\\Service\\Handler\\${entity}\\PutHandler:
        class: App\\Service\\Handler\\${entity}\\PutHandler
        arguments:
           - '@doctrine.orm.entity_manager'
        tags:
           - { name: tactician.handler, command: App\\Service\\Command\\${entity}\\PutCommand }
    App\\Service\\Handler\\${entity}\\DeleteHandler:
        class: App\\Service\\Handler\\${entity}\\DeleteHandler
        arguments:
           - '@doctrine.orm.entity_manager'
        tags:
           - { name: tactician.handler, command: App\\Service\\Command\\${entity}\\DeleteCommand }`
            ]
        }, this);
    }

    writeConfigServiceZend(module, entity) {
        util.rewriteFile({
            path: 'api/config/autoload',
            file: 'doctrine.global.php',
            needle: 'new-entity-mapper',
            splicable: [
                `'App\\Entity\\${_.startCase(entity).replace(' ', '')}' => 'annotation_driver',`,
            ]
        }, this);
        util.rewriteFile({
            path: 'api/src/App',
            file: 'ConfigProvider.php',
            needle: 'new-factories-mapper',
            splicable: [
                `\\App\\Middleware\\${_.startCase(entity).replace(' ', '')}\\GetMiddleware::class => \\App\\Factory\\${_.startCase(entity).replace(' ', '')}\\GetMiddlewareFactory::class,
                \\App\\Middleware\\${_.startCase(entity).replace(' ', '')}\\PostMiddleware::class => \\App\\Factory\\${_.startCase(entity).replace(' ', '')}\\PostMiddlewareFactory::class,
                \\App\\Middleware\\${_.startCase(entity).replace(' ', '')}\\PutMiddleware::class => \\App\\Factory\\${_.startCase(entity).replace(' ', '')}\\PutMiddlewareFactory::class,
                \\App\\Middleware\\${_.startCase(entity).replace(' ', '')}\\DeleteMiddleware::class => \\App\\Factory\\${_.startCase(entity).replace(' ', '')}\\DeleteMiddlewareFactory::class,`,
            ]
        }, this);
        util.rewriteFile({
            path: 'api/config',
            file: 'routes.php',
            needle: `new-route-mapper`,
            splicable: [
                `$app->post('/api/${ _.kebabCase(entity) }', App\\Middleware\\${_.startCase(entity).replace(' ', '')}\\PostMiddleware::class, '${ _.kebabCase(entity) }.post');
    $app->get('/api/${ _.kebabCase(entity) }[/{id:[0-9]{1,99}}]',  App\\Middleware\\${_.startCase(entity).replace(' ', '')}\\GetMiddleware::class, '${ _.kebabCase(entity) }.get');
    $app->put('/api/${ _.kebabCase(entity) }[/{id:[0-9]{1,99}}]',  App\\Middleware\\${_.startCase(entity).replace(' ', '')}\\PutMiddleware::class, '${ _.kebabCase(entity) }.put');
    $app->delete('/api/${ _.kebabCase(entity) }[/{id:[0-9]{1,99}}]',  App\\Middleware\\${_.startCase(entity).replace(' ', '')}\\DeleteMiddleware::class, '${ _.kebabCase(entity) }.delete');`
            ]
        }, this);
    }

    writeFile(origin, destination, bundle, entity, props) {
        var fs = require('fs');
        if (!fs.existsSync(destination)) {
            this.fs.copyTpl(
                this.templatePath(origin),
                this.destinationPath(destination), {
                    packageName: bundle,
                    className: entity,
                    attributs: props,
                    _: _
                }
            );
        } else {
            console.log(chalk.yellow('Warning the file: ') + destination + chalk.yellow(' already exist'));
        }
    }
}