/* global describe, beforeEach, it*/

const path = require('path');
const fse = require('fs-extra');
const assert = require('yeoman-assert');
const helpers = require('yeoman-test');
const sinon = require('mocha-sinon');
const generators = require('yeoman-generator').generators;

describe('Symfony generator command bus architecture', () => {
    describe('Test create application\'s basic files', () => {
        beforeEach((done) => {
            helpers
                .run(path.join(__dirname, '../app'))
                .inTmpDir((dir) => {
                    fse.copySync(path.join(__dirname, '../test/templates/symfony'), dir);
                })
                .withOptions({
                    testmode: true
                })
                .withPrompts({
                    message: 'Files was created'
                })
                .on('end', done);
        });

        it('generate JWTCreatedListener.php file', () => {
            assert.file([
                'JWTCreatedListener.php',
            ]);
        });

        it('generate NotFoundException.php file', () => {
            assert.file([
                'NotFoundException.php',
            ]);
        });

        it('generate ResponseBag.php file', () => {
            assert.file([
                'ResponseBag.php',
            ]);
        });
    });
    /*describe('Test create application\'s skeleton', () => {
        before(function (done) {
            this.spy = this.sinon.spy();
            var Dummy = generators.Symfony.extend({
                exec: this.spy
            });
        
            helpers.run('composer create-project symfony/framework-standard-edition app-symfony "3.4.*"')
                .withGenerators([
                    [Dummy, 'foo:bar']
                ])
                .on('end', done);
        });
        it('run the sub-generator', function () {
            assert(this.spy.calledOnce);
        });
    });*/
});