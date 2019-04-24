import { NgModule, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { CardComponent } from './card.component';
import { CardService } from './card.service';

import {
  MatButtonModule,
  MatInputModule,
  MatRippleModule,
  MatFormFieldModule,
  MatTooltipModule,
  MatSelectModule,
  MatDialogModule
} from '@angular/material';
import { DialogContent } from '../dialog/dialog-content.component';


@NgModule({
  imports: [
    CommonModule,
    MatButtonModule,
    MatInputModule,
    MatRippleModule,
    MatFormFieldModule,
    MatTooltipModule,
    MatSelectModule,
    MatDialogModule,
    RouterModule,
  ],
  declarations: [
    CardComponent,
    DialogContent,
  ],
  exports: [
    CardComponent,
    DialogContent,
  ],
  providers: [CardService],
  schemas: [CUSTOM_ELEMENTS_SCHEMA],
  entryComponents: [DialogContent]
})
export class CardModule { }
