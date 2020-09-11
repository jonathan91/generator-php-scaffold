import { Routes } from '@angular/router';
import { <%= _.startCase(className).replace(' ', '') %>Component } from './<%= _.kebabCase(className).toLowerCase() %>.component';
import { <%= _.startCase(className).replace(' ', '') %>DetailComponent } from './<%= _.kebabCase(className).toLowerCase() %>-detail.component';
import { <%= _.startCase(className).replace(' ', '') %>FormComponent } from './<%= _.kebabCase(className).toLowerCase() %>-form.component';

export const <%= _.camelCase(className).replace(' ','') %>Route: Routes = [
  {
    path: '',
    component: <%= _.startCase(className).replace(' ', '') %>Component
  },
  {
    path: 'new',
    component: <%= _.startCase(className).replace(' ', '') %>FormComponent
  },
  {
    path: ':id/edit',
    component: <%= _.startCase(className).replace(' ', '') %>FormComponent
  },
  {
    path: ':id',
    component: <%= _.startCase(className).replace(' ', '') %>DetailComponent
  },
];
