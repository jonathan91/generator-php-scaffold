import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Response } from '@angular/http';
import { Observable, Subscription } from 'rxjs/Rx';
import { NotificationService } from 'app/shared/services/notification.service';
import { <%= className %> } from './models/<%= _.toLower(className) %>.model';
import { <%= className %>Service } from './services/<%= _.toLower(className) %>.service';

@Component({
  selector: 'app-<%= _.toLower(className) %>-form',
  templateUrl: './<%= _.toLower(className) %>-form.component.html'
})
export class <%= className %>FormComponent implements OnInit, OnDestroy {
  <%= _.toLower(className) %>: <%= className %>;
  isSaving: boolean;
  isEdit = false;
  private routeSub: Subscription;

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private notification: NotificationService,
    private <%= _.toLower(className) %>Service: <%= className %>Service,
  ) {}

  ngOnInit() {
    this.isSaving = false;
    this.routeSub = this.route.params.subscribe(params => {
      let title = 'Create';
      this.<%= _.toLower(className) %> = new <%= className %>();
      if (params['id']) {
        this.isEdit = true;
        this.<%= _.toLower(className) %>Service.find(params['id']).subscribe(<%= _.toLower(className) %> => this.<%= _.toLower(className) %> = <%= _.toLower(className) %>);
        title = 'Edit';
      }
    });
  }

  save() {
    this.isSaving = true;
    if (this.<%= _.toLower(className) %>.id !== undefined) {
      this.subscribeToSaveResponse(this.<%= _.toLower(className) %>Service.update(this.<%= _.toLower(className) %>));
    } else {
      this.subscribeToSaveResponse(this.<%= _.toLower(className) %>Service.create(this.<%= _.toLower(className) %>));
    }
  }

  private subscribeToSaveResponse(result: Observable<<%= className %>>) {
    result.subscribe(
      (<%= _.toLower(className) %>: <%= className %>) => {
      this.isSaving = false;
      this.router.navigate(['/<%= _.toLower(className) %>']);
      this.notification.showNotification(this.notification.msgSuccess, 'success');
    },
    (response: Response) => {
      this.isSaving = false;
    });
  }

  ngOnDestroy() {
    this.routeSub.unsubscribe();
  }
}
