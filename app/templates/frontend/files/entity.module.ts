import { NgModule, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpModule } from '@angular/http';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { CardModule } from '../shared/card/card.module';
import {
    MatButtonModule,
    MatInputModule,
    MatRippleModule,
    MatFormFieldModule,
    MatTooltipModule,
    MatSelectModule,
    MatDialogModule,
} from '@angular/material';

import { <%= _.startCase(className).replace(' ', '') %>FormComponent } from './<%= _.kebabCase(className).toLowerCase() %>-form.component';
import { <%= _.startCase(className).replace(' ', '') %>DetailComponent } from './<%= _.kebabCase(className).toLowerCase() %>-detail.component';
import { <%= _.startCase(className).replace(' ', '') %>Component } from './<%= _.kebabCase(className).toLowerCase() %>.component';
import { <%= _.camelCase(className).replace(' ','') %>Route } from './<%= _.kebabCase(className).toLowerCase() %>.route';
import { <%= _.startCase(className).replace(' ', '') %>Service } from './services/<%= _.kebabCase(className).toLowerCase() %>.service';

@NgModule({
  imports: [
    CommonModule,
    HttpModule,
    FormsModule,
    CardModule,
    RouterModule.forChild(<%= _.camelCase(className).replace(' ','') %>Route),
    MatButtonModule,
    MatInputModule,
    MatRippleModule,
    MatFormFieldModule,
    MatTooltipModule,
    MatSelectModule,
    MatDialogModule,
  ],
  declarations: [
    <%= _.startCase(className).replace(' ', '') %>Component,
    <%= _.startCase(className).replace(' ', '') %>DetailComponent,
    <%= _.startCase(className).replace(' ', '') %>FormComponent
  ],
  providers: [<%= _.startCase(className).replace(' ', '') %>Service],
  schemas: [CUSTOM_ELEMENTS_SCHEMA]
})
export class <%= _.startCase(className).replace(' ', '') %>Module {}
