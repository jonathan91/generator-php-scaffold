import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Subscription } from 'rxjs/Rx';
import { <%= className %> } from './models/<%= _.toLower(className) %>.model';
import { <%= className %>Service } from './services/<%= _.toLower(className) %>.service';

@Component({
  selector: 'app-<%= _.toLower(className) %>-detail',
  templateUrl: './<%= _.toLower(className) %>-detail.component.html'
})
export class <%= className %>DetailComponent implements OnInit, OnDestroy {

  <%= _.toLower(className) %>: <%= className %>;
  private subscription: Subscription;

  constructor(
    private <%= _.toLower(className) %>Service: <%= className %>Service,
    private route: ActivatedRoute
  ) {}

  ngOnInit() {
    this.<%= _.toLower(className) %> = new <%= className %>();
    this.subscription = this.route.params.subscribe((params) => {
      this.load(params['id']);
    });
  }

  load(id: number) {
    this.<%= _.toLower(className) %>Service.find(id).subscribe((<%= _.toLower(className) %>) => {
      this.<%= _.toLower(className) %> = <%= _.toLower(className) %>;
    });
  }

  ngOnDestroy() {
    this.subscription.unsubscribe();
  }
}
