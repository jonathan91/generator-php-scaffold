import { Component, OnInit, OnDestroy, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { MatDialog } from '@angular/material';
import { DialogContent } from 'app/shared/dialog/dialog-content.component';
import { NotificationService } from 'app/shared/services/notification.service';
import { CardComponent } from 'app/shared/card/card.component';
import { <%= className %>Service } from './services/<%= _.toLower(className) %>.service';
import { <%= className %> } from './models/<%= _.toLower(className) %>.model';

@Component({
  selector: 'app-<%= _.toLower(className) %>',
  templateUrl: './<%= _.toLower(className) %>.component.html'
})
export class <%= className %>Component implements OnInit, OnDestroy {

  @ViewChild(CardComponent)
  card: CardComponent;

  searchUrl: string = this.<%= _.toLower(className) %>Service.resourceUrl;

  constructor(
    private router: Router,
    private <%= _.toLower(className) %>Service: <%= className %>Service,
    public dialog: MatDialog,
    private notification: NotificationService
  ) {}

  click(event: any) {
    this.card.click.subscribe(result =>{
    if (result.button === 'view') 
      this.view(result.row);

    if (result.button === 'edit') 
      this.edit(result.row);
    
    if (result.button === 'delete') 
      this.delete(result.row);
    });
  }

  view(<%= _.toLower(className) %>: <%= className %>) {
    this.router.navigate([`/<%= _.toLower(className) %>/${<%= _.toLower(className) %>.id}`]);
  }

  edit(<%= _.toLower(className) %>: <%= className %>) {
   this.router.navigate([`/<%= _.toLower(className) %>/${<%= _.toLower(className) %>.id}/edit`]);
  }

  delete(<%= _.toLower(className) %>: <%= className %>) {
    this.dialog.open(DialogContent).beforeClose().subscribe(
      res => {
        if(res){
          this.<%= _.toLower(className) %>Service.delete(<%= _.toLower(className) %>.id).subscribe(
            response => {
              this.notification.showNotification(this.notification.msgSuccess, 'success');
            },
            error => {
              this.notification.showNotification(error.message, 'danger');
            });
        }
      }
    );
  }

  ngOnInit() {
  }

  ngOnDestroy() {
  }

}