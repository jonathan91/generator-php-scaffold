import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Observable, Subscription } from 'rxjs/Rx';
import { NotificationService } from 'app/shared/services/notification.service';
import { <%= _.startCase(className).replace(' ', '') %> } from './models/<%= _.kebabCase(className).toLowerCase() %>.model';
import { <%= _.startCase(className).replace(' ', '') %>Service } from './services/<%= _.kebabCase(className).toLowerCase() %>.service';

@Component({
  selector: 'app-<%= _.kebabCase(className).toLowerCase() %>-form',
  templateUrl: './<%= _.kebabCase(className).toLowerCase() %>-form.component.html'
})
export class <%= _.startCase(className).replace(' ', '') %>FormComponent implements OnInit, OnDestroy {
  <%= _.camelCase(className) %>: <%= _.startCase(className).replace(' ', '') %>;
  isSaving: boolean;
  isEdit = false;
  private routeSub: Subscription;

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private notification: NotificationService,
    private <%= _.camelCase(className) %>Service: <%= _.startCase(className).replace(' ', '') %>Service,
  ) {}

  ngOnInit() {
    this.isSaving = false;
    this.routeSub = this.route.params.subscribe(params => {
      let title = 'Create';
      this.<%= _.camelCase(className) %> = new <%= _.startCase(className).replace(' ', '') %>();
      if (params['id']) {
        this.isEdit = true;
        this.<%= _.camelCase(className) %>Service.find(params['id']).subscribe(<%= _.camelCase(className) %> => this.<%= _.camelCase(className) %> = <%= _.camelCase(className) %>);
        title = 'Edit';
      }
    });
  }

  save() {
    this.isSaving = true;
    if (this.<%= _.camelCase(className) %>.id !== undefined) {
      this.subscribeToSaveResponse(this.<%= _.camelCase(className) %>Service.update(this.<%= _.camelCase(className) %>));
    } else {
      this.subscribeToSaveResponse(this.<%= _.camelCase(className) %>Service.create(this.<%= _.camelCase(className) %>));
    }
  }

  private subscribeToSaveResponse(result: Observable<<%= _.startCase(className).replace(' ', '') %>>) {
    result.subscribe(
      (<%= _.camelCase(className) %>: <%= _.startCase(className).replace(' ', '') %>) => {
      this.isSaving = false;
      this.router.navigate(['/<%= _.kebabCase(className).toLowerCase() %>']);
      this.notification.showNotification(this.notification.msgSuccess, 'success');
    },
    (response: {error?: any}) => {
      this.notification.showNotification(response.error.detail, 'warning');
      this.isSaving = false;
    });
  }

  ngOnDestroy() {
    this.routeSub.unsubscribe();
  }
}
