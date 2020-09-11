import { Component, OnInit, OnDestroy, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { MatDialog } from '@angular/material';
import { DialogContent } from 'app/shared/dialog/dialog-content.component';
import { NotificationService } from 'app/shared/services/notification.service';
import { CardComponent } from 'app/shared/card/card.component';
import { <%= _.startCase(className).replace(' ', '') %>Service } from './services/<%= _.kebabCase(className).toLowerCase() %>.service';
import { <%= _.startCase(className).replace(' ', '') %> } from './models/<%= _.kebabCase(className).toLowerCase() %>.model';

@Component({
  selector: 'app-<%= _.kebabCase(className).toLowerCase() %>',
  templateUrl: './<%= _.kebabCase(className).toLowerCase() %>.component.html'
})
export class <%= _.startCase(className).replace(' ', '') %>Component implements OnInit, OnDestroy {

  @ViewChild(CardComponent)
  card: CardComponent;

  searchUrl: string = this.<%= _.camelCase(className).replace(' ', '') %>Service.resourceUrl;

  constructor(
    private router: Router,
    private <%= _.camelCase(className).replace(' ', '') %>Service: <%= _.startCase(className).replace(' ', '') %>Service,
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

  view(<%= _.camelCase(className).replace(' ', '') %>: <%= _.startCase(className).replace(' ', '') %>) {
    this.router.navigate([`/<%= _.toLower(className) %>/${<%= _.camelCase(className).replace(' ', '') %>.id}`]);
  }

  edit(<%= _.camelCase(className).replace(' ', '') %>: <%= _.startCase(className).replace(' ', '') %>) {
   this.router.navigate([`/<%= _.toLower(className) %>/${<%= _.camelCase(className).replace(' ', '') %>.id}/edit`]);
  }

  delete(<%= _.camelCase(className).replace(' ', '') %>: <%= _.startCase(className).replace(' ', '') %>) {
    this.dialog.open(DialogContent).beforeClose().subscribe(
      res => {
        if(res){
          this.<%= _.camelCase(className).replace(' ', '') %>Service.delete(<%= _.camelCase(className).replace(' ', '') %>.id).subscribe(
            response => {
              this.card.ngOnInit();
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