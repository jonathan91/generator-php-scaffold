<?php declare(strict_types=1);
namespace App\Command\<%= _.startCase(className).replace(' ', '') %>;

use App\Command\AbstractCommand;
use Webmozart\Assert\Assert;

class <%= attributs.type %>Command extends AbstractCommand
{
    /**
     * @var int
     */
    public $id;
    <% if (attributs.type !== 'Delete') { attributs.fields.forEach(function(element){ %>
    /**
    * @var <% if (element.fieldType !== 'class') { if (element.fieldType !== 'integer') { %><%= element.fieldType %> <% } else { %>int<% }} else { %><%= element.otherEntityName %><% } %>
    */
    public $<%= _.camelCase(element.fieldName).replace(' ','') %>;
    <% }); } %>

    public function validate()
    {
        <% if (attributs.type === 'Delete' || attributs.type === 'Put') { %>Assert::notEmpty($this->id);
        <% } attributs.fields.forEach(function(element){ if (attributs.type !== 'Delete') {  %>
        <% if (element.fieldValidateRules.includes('required')) { %>Assert::notEmpty($this-><%= _.camelCase(element.fieldName).replace(' ','') %>);
        <% } if (element.fieldValidateRules.includes('pattern')) { %>Assert::regex($this-><%= _.camelCase(element.fieldName).replace(' ','') %>, <%= element.fieldValidateRulesPattern %>);
        <% } if (element.fieldValidateRules.includes('minlength')) { %>Assert::minLength($this-><%= _.camelCase(element.fieldName).replace(' ','') %>, <% if (element.fieldValidateRulesMin !== undefined) { %><%= element.fieldValidateRulesMin %><% } else { %><%=0%><% } %>);
        <% } if (element.fieldValidateRules.includes('maxlength')) { %>Assert::maxLength($this-><%= _.camelCase(element.fieldName).replace(' ','') %>, <% if (element.fieldValidateRulesMax !== undefined) { %><%= element.fieldValidateRulesMax %><% } else { %><%=60%><% } %>);
        <% }}}); %>
        return true;
    }
}