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

import { <%= className %>FormComponent } from './<%= _.toLower(className) %>-form.component';
import { <%= className %>DetailComponent } from './<%= _.toLower(className) %>-detail.component';
import { <%= className %>Component } from './<%= _.toLower(className) %>.component';
import { <%= _.toLower(className) %>Route } from './<%= _.toLower(className) %>.route';
import { <%= className %>Service } from './services/<%= _.toLower(className) %>.service';

@NgModule({
  imports: [
    CommonModule,
    HttpModule,
    FormsModule,
    CardModule,
    RouterModule.forChild(<%= _.toLower(className) %>Route),
    MatButtonModule,
    MatInputModule,
    MatRippleModule,
    MatFormFieldModule,
    MatTooltipModule,
    MatSelectModule,
    MatDialogModule,
  ],
  declarations: [
    <%= className %>Component,
    <%= className %>DetailComponent,
    <%= className %>FormComponent
  ],
  providers: [<%= className %>Service],
  schemas: [CUSTOM_ELEMENTS_SCHEMA]
})
export class <%= className %>Module {}
