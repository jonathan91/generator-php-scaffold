<?php
namespace App\Service\Command\<%= _.startCase(className).replace(' ', '') %>;

use App\Service\Command\AbstractCommand;
use Symfony\Component\Validator\Constraints as Assert;

class <%= attributs.type %>Command extends AbstractCommand
{
    /**
	 * @var int
     * <% if (attributs.type === 'Delete') { %>@Assert\NotBlank() <% } %>
  	 */
	public $id;
    <% if (attributs.type !== 'Delete') { attributs.fields.forEach(function(element){ %>
    /**
    * @var <% if (element.fieldType !== 'class') { if (element.fieldType !== 'integer') { %><%= element.fieldType %> <% } else { %>int<% }} else { %><%= element.otherEntityName %><% } %>
    * <% if (element.fieldValidateRules.includes('required')) { %>
    * @Assert\NotBlank()
    * <% } if (element.fieldValidateRules.includes('pattern')) { %> 
    * @Assert\Regex(pattern="<%= element.fieldValidateRulesPattern %>", match=false, message="Your regular expression validation message")
    * <% } if (element.fieldValidateRules.includes('minlength') || element.fieldValidateRules.includes('maxlength')) { %>
    * @Assert\Length(
    *    min = <% if (element.fieldValidateRulesMin !== undefined) { %><%= element.fieldValidateRulesMin %><% } else { %><%=0%><% } %>, 
    *    max = <% if (element.fieldValidateRulesMax !== undefined) { %><%= element.fieldValidateRulesMax %><% } else { %><%=60%><% } %>))
    * <% } if (element.fieldValidateRules.includes('pattern')) { %>
    * @Assert\Regex(
    *     pattern="<%= element.fieldValidateRulesPattern %>",
    *     match=false,
    *     message="Your regular expression validation message"
    * )
    * <% } if (element.fieldValidateRules.includes('minlength') || element.fieldValidateRules.includes('maxlength')) { %>
    * @Assert\Range(
    *      min = <% if (element.fieldValidateRulesMin !== undefined){ %><%= element.fieldValidateRulesMin %><% } else { %><%=0%><% } %>,
    *      max = <% if (element.fieldValidateRulesMax !== undefined){ %><%= element.fieldValidateRulesMax %><% } else { %><%=60%><% } %>,
    *      minMessage = "The min value required is {{ limit }}",
    *      maxMessage = "The max value required is {{ limit }}"
    * )
    * <% } %>
    */
    public $<%= _.camelCase(element.fieldName).replace(' ','') %>;
    <% }); } %>
}