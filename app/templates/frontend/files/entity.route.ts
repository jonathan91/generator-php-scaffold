import { Routes } from '@angular/router';
import { <%= className %>Component } from './<%= _.toLower(className) %>.component';
import { <%= className %>DetailComponent } from './<%= _.toLower(className) %>-detail.component';
import { <%= className %>FormComponent } from './<%= _.toLower(className) %>-form.component';

export const <%= _.toLower(className) %>Route: Routes = [
  {
    path: '',
    component: <%= className %>Component
  },
  {
    path: 'new',
    component: <%= className %>FormComponent
  },
  {
    path: ':id/edit',
    component: <%= className %>FormComponent
  },
  {
    path: ':id/aprove',
    component: <%= className %>Component
  },
  {
    path: ':id',
    component: <%= className %>DetailComponent
  },
];
