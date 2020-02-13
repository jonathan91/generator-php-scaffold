<?php
namespace App\Service\Command\<%= className %>;

use Symfony\Component\Validator\Constraints as Assert;

class <%= attributs.type %>Command
{
    /**
	 * @var int
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
    public $<%= element.fieldName %>;
    <% }); } %>
}