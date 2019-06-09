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

    askForField(answers) 
    {
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
                    } /*else if (input === 'id' || !itensFields.filter(function(itensFields){return itensFields === input})) {
                        return 'Your field name cannot use an already existing field name';
                    }*/
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
                        },{
                            name: 'Unique',
                            value: 'unique'
                        },{
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

    addFields(props)
    {
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

    askForNewEntity() 
    {
        this.prompt([{
            type: 'input',
            name: 'bundleName',
            message: 'What is the module\'s name: ',
            store: true
        },{
            type: 'list',
            name: 'typeCreation',
            message: 'What do you like, create entity or import file',
            choices: 
                [{
                    name: 'Create new entity',
                    value:'create'
                },{
                    name: 'Import file\'s jdl',
                    value:'import'
                }]
        },{
            when: response => response.typeCreation === 'create',
            type: 'input',
            name: 'className',
            message: 'What is the class\'s name: '
        },{
            when: response => response.typeCreation === 'import',
            type: 'input',
            name: 'pathName',
            message: 'What is the path of jdl file: ',
            default: 'data/json-files'
        }]).then((answers) => {
            if(answers.typeCreation === 'create'){
                itensFields[index] = {
                    entity: answers.bundleName+'.'+answers.className,
                    fields: []
                };
                this.askForField.call(this);
            } else {  
                var store = memFs.create();
                var fsr = editor.create(store);
                var listFiles = fs.readdirSync(answers.pathName);
                if (fs.existsSync(answers.pathName)) {
                    listFiles.forEach(file => {
                        var contentFile = fsr.read(answers.pathName+'/'+file);
                        this.mapJsonEntity(answers.bundleName, JSON.parse(contentFile));
                    });
                    var values = ''; 
                    itensFields.forEach(element => {
                        values = element.entity.split('.')
                        this.writeBackendFiles(element, values);
                        this.writeFrontendFiles(element);
                    });
                }else{
                    console.log(chalk.red('Erro: The files path has not exist.'));
                }
            }
        });
    }

    mapJsonEntity(bundle, entity)
    {
        itensFields[index] = {
            entity: bundle+'.'+_.upperFirst(_.camelCase(entity.entityTableName)),
            fields: []
        };
        
        entity.fields.forEach(element =>{
            itensFields[index].fields.push({
                fieldName: element.fieldName,
                fieldType: _.lowerCase(element.fieldType),
                otherEntityName: '',
                fieldValidateRules: (element.fieldValidateRules) ?  element.fieldValidateRules : [],
            });
        });
        entity.relationships.forEach(element =>{
            itensFields[index].fields.push({
                fieldName: element.relationshipName,
                fieldType: 'class',
                relationshipType: _.upperFirst(_.camelCase(element.relationshipType)),
                otherEntityName: _.upperFirst(_.camelCase(element.otherEntityName)),
                fieldValidateRules: (element.fieldValidateRules) ?  element.fieldValidateRules : [],
            });
        });
        index++;
    }

    askToContinue() 
    {
        this.prompt([{
            type: 'confirm',
            name: 'check',
            message: 'Do you want create a new entity: ',
        }]).then((answers) => {
            if (answers.check) {
                index++;
                this.askForNewEntity.call(this);
            }else{
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

    writeBackendFiles(props, answers) 
    {
        const me = this;
        fs.readFile('.yo-rc.json', 'utf-8', function(error, content){
            let path = JSON.parse(content);
            if (_.toUpper(path.appFramework) === 'ZEND') {
                me.writeBackendZend(props);
                me.writeConfigServiceZend(answers[0], answers[1]);
            } else {
                me.writeBackendSymfony(props);
                me.writeConfigServiceSymfony(answers[0], answers[1]);
            }
        }); 
    }

    writeBackendZend(props)
    {
        var values = props.entity.split('.');
        this.writeFile(
            'backend/zend/files/Controller.php',
            'module/' + values[0]+'/src/Controller/'+values[1]+'Controller.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/zend/files/Entity.php',
            'module/' + values[0]+'/src/Entity/'+values[1]+'.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/zend/files/Repository.php',
            'module/' + values[0]+'/src/Repository/'+values[1]+'Repository.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/zend/files/Service.php',
            'module/' + values[0]+'/src/Service/'+values[1]+'Service.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/zend/files/ControllerFactory.php',
            'module/' + values[0]+'/src/Controller/Factory/'+values[1]+'ControllerFactory.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/zend/files/ServiceFactory.php',
            'module/' + values[0]+'/src/Service/Factory/'+values[1]+'ServiceFactory.php',
            values[0],
            values[1],
            props
        );
    }

    writeBackendSymfony(props)
    {
        var types = [
            {
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
            this.writeFile(
                'backend/symfony/files/Command.php', 
                'src/'+values[0]+'/Service/Command/'+values[1]+'/'+element.type+_.upperFirst(_.camelCase(values[1]))+'Command.php',
                values[0],
                values[1],
                props
            );
            this.writeFile(
                'backend/symfony/files/'+props.type+'Handler.php', 
                'src/'+values[0]+'/Service/Handler/'+values[1]+'/'+element.type+_.upperFirst(_.camelCase(values[1]))+'Handler.php',
                values[0],
                values[1],
                props
            );
        });
        this.writeFile(
            'backend/symfony/files/Query.php', 
            'src/'+values[0]+'/Service/Query/'+_.upperFirst(_.camelCase(values[1]))+'Query.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/symfony/files/Repository.php', 
            'src/'+values[0]+'/Repository/'+_.upperFirst(_.camelCase(values[1]))+'Repository.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/symfony/files/Controller.php', 
            'src/'+values[0]+'/Controller/'+_.upperFirst(_.camelCase(values[1]))+'Controller.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/symfony/files/Entity.php', 
            'src/'+values[0]+'/Entity/'+_.upperFirst(_.camelCase(values[1]))+'.php',
            values[0],
            values[1],
            props
        );
        //Test
        this.writeFile(
            'backend/symfony/tests/AbstractIntegrationTestCase.php', 
            'tests/Integration/AbstractIntegrationTestCase.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/symfony/tests/JWTCreatedListenerTest.php', 
            'tests/Unit/AbstractUnitTestCase.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/symfony/tests/EntityTest.php', 
            'tests/Unit/'+values[0]+'/Entity/'+values[1]+'EntityTest.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/symfony/tests/JWTCreatedListenerTest.php', 
            'tests/Unit/'+values[0]+'/EventListener/JWTCreatedListenerTest.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/symfony/tests/ResponseBagTest.php', 
            'tests/Unit/'+values[0]+'/Http/ResponseBagTest.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/symfony/tests/HandlerTest.php', 
            'tests/Unit/'+values[0]+'/Service/Handler/'+values[1]+'HandlerTest.php',
            values[0],
            values[1],
            props
        );
        this.writeFile(
            'backend/symfony/tests/QueryTest.php', 
            'tests/Unit/'+values[0]+'/Service/Query/'+values[1]+'QueryTest.php',
            values[0],
            values[1],
            props
        );
    }
    
    writeFrontendFiles(props) 
    {
        const me = this;
        fs.readFile('.yo-rc.json', 'utf-8', function(error, content){
            let path = JSON.parse(content);
            let config = path.config;
            var values = props.entity.split('.');
            me.writeFile(
                'frontend/files/entity-detail.component.html',
                config.frontendDir+'/src/app/'+ _.toLower(values[1]) + '/' +  _.toLower(values[1])+'-detail.component.html',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity-detail.component.ts',
                config.frontendDir+'/src/app/'+ _.toLower(values[1]) + '/' +  _.toLower(values[1])+'-detail.component.ts',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity-form.component.html',
                config.frontendDir+'/src/app/'+ _.toLower(values[1]) + '/' +  _.toLower(values[1])+'-form.component.html',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity-form.component.ts',
                config.frontendDir+'/src/app/'+ _.toLower(values[1]) + '/' +  _.toLower(values[1])+'-form.component.ts',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity.component.html',
                config.frontendDir+'/src/app/'+ _.toLower(values[1]) + '/' +  _.toLower(values[1])+'.component.html',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity.component.ts',
                config.frontendDir+'/src/app/'+ _.toLower(values[1]) + '/' +  _.toLower(values[1])+'.component.ts',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity.model.ts',
                config.frontendDir+'/src/app/'+ _.toLower(values[1]) + '/models/' +  _.toLower(values[1])+'.model.ts',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity.module.ts',
                config.frontendDir+'/src/app/'+ _.toLower(values[1]) + '/' +  _.toLower(values[1])+'.module.ts',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity.route.ts',
                config.frontendDir+'/src/app/'+ _.toLower(values[1]) + '/' +  _.toLower(values[1])+'.route.ts',
                values[0],
                values[1],
                props
            );
            me.writeFile(
                'frontend/files/entity.service.ts',
                config.frontendDir+'/src/app/'+ _.toLower(values[1]) + '/services/' +  _.toLower(values[1])+'.service.ts',
                values[0],
                values[1],
                props
            );
            me.writeConfigFrontend(values[1]); 
        });
    }

    writeConfigFrontend(entity)
    {
        util.rewriteFile({
            path: 'client/src/app/layouts/admin-layout/',
            file: 'admin-layout.routing.ts',
            needle: 'needle-add-router',
            splicable: [
                `{ path: '${_.toLower(entity)}', loadChildren: '../../${_.toLower(entity)}/${_.toLower(entity)}.module#${entity}Module' },`
            ]
        },this);

        util.rewriteFile({
            path: 'client/src/app/components/sidebar/',
            file: 'sidebar.component.ts',
            needle: 'needle-menu-item',
            splicable: [
                `{ path: '/${_.toLower(entity)}', title: '${_.upperFirst(entity)}',  icon: 'bookmark', class: '' },`
            ]
        },this);
    }

    writeFile(origin, destination, bundle, entity, props) 
    {
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
        }else{
            console.log(chalk.yellow('Warning the file: ')+destination+chalk.yellow(' already exist'));
        }
    }
    
    writeConfigServiceSymfony(bundle, entity)
    {
        util.rewriteFile({
            path: 'src/'+bundle+'/Resources/config/',
            file: 'services.yml',
            needle: 'Map-Services-Entity',
            splicable: [
    `# ${entity}
 ${_.toLower(_.replace(bundle,"Bundle",""))}.${ _.toLower(entity)}_query:
  class: ${bundle}\\Service\\Query\\${entity}Query
  arguments:
   - '@doctrine.orm.entity_manager'
 ${_.toLower(_.replace(bundle,"Bundle",""))}.post_${_.toLower(entity)}_handler:
  class: ${bundle}\\Service\\Handler\\${entity}\\Post${entity}Handler
  arguments:
   - '@doctrine.orm.entity_manager'
  tags:
   - { name: tactician.handler, command: ${bundle}\\Service\\Command\\${entity}\\Post${entity}Command }
 ${_.toLower(_.replace(bundle,"Bundle",""))}.put_${ _.toLower(entity)}_handler:
  class: ${bundle}\\Service\\Handler\\${entity}\\Put${entity}Handler
  arguments:
   - '@doctrine.orm.entity_manager'
  tags:
   - { name: tactician.handler, command: ${bundle}\\Service\\Command\\${entity}\\Put${entity}Command }
 ${_.toLower(_.replace(bundle,"Bundle",""))}.delete_${ _.toLower(entity)}_handler:
  class: ${bundle}\\Service\\Handler\\${entity}\\Delete${entity}Handler
  arguments:
   - '@doctrine.orm.entity_manager'
  tags:
   - { name: tactician.handler, command: ${bundle}\\Service\\Command\\${entity}\\Delete${entity}Command }`
        ]
    },this);
    }

    writeConfigServiceZend(module, entity) 
    {
        this.writeFile(
            'backend/zend/files/Module.php',
            'module/' + module + '/src/Module.php',
            module,
            entity,
            {}
        );

        this.writeFile(
            'backend/zend/files/module.config.php',
            'module/' + module + '/config/module.config.php',
            module,
            entity,
            {}
        );

        util.rewriteFile({
            path: 'config',
            file: 'modules.config.php',
            needle: 'module-name-mapper',
            splicable: [
            `'${module}',`
            ]
        },this);
        
        util.rewriteFile({
            path: 'config/autoload',
            file: 'doctrine_orm.global.php',
            needle: 'entity-name-mapper',
            splicable: [
                `getcwd() . "/module/${module}/src/Entity",`
            ]
        },this);

        util.rewriteFile({
            path: 'config/autoload',
            file: 'doctrine_orm.global.php',
            needle: 'namespace-name-mapper',
            splicable: [
            `'${module}\\Entity\\${entity}' => 'annotation_driver',`
            ]
        },this);

        util.rewriteFile({
            path: '.',
            file: 'composer.json',
            needle: `new-module-mapper`,
            splicable: [
                `"${module+'\\'}\\": "module/${module}/src/",`
            ]
        },this);
    }

    writeFile(origin, destination, bundle, entity, props) 
    {
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
        }else{
            console.log(chalk.yellow('Warning the file: ')+destination+chalk.yellow(' already exist'));
        }
    }
}