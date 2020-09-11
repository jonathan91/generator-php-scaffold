export class <%= _.startCase(className).replace(' ', '') %> {
  
  constructor(
    public id?: number,<% attributs.fields.forEach(function(element){ %>
    public <%= element.fieldName %>?: <% if (element.fieldType !== 'class') { if (element.fieldType !== 'integer' && element.fieldType !== 'decimal' && element.fieldType !== 'float') { %><%= element.fieldType %><% } else { %>number<% }} else { %>any<% } %>,<% }); %>
  ) {}
}
