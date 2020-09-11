import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Subscription } from 'rxjs/Rx';
import { <%= _.startCase(className).replace(' ', '') %> } from './models/<%= _.kebabCase(className).toLowerCase() %>.model';
import { <%= _.startCase(className).replace(' ', '') %>Service } from './services/<%= _.kebabCase(className).toLowerCase() %>.service';

@Component({
  selector: 'app-<%= _.kebabCase(className).toLowerCase() %>-detail',
  templateUrl: './<%= _.kebabCase(className).toLowerCase() %>-detail.component.html'
})
export class <%= _.startCase(className).replace(' ', '') %>DetailComponent implements OnInit, OnDestroy {

  <%= _.camelCase(className) %>: <%= _.startCase(className).replace(' ', '') %>;
  private subscription: Subscription;
  
  constructor(
    private <%= _.camelCase(className) %>Service: <%= _.startCase(className).replace(' ', '') %>Service,
    private route: ActivatedRoute
  ) {}

  ngOnInit() {
    this.<%= _.camelCase(className) %> = new <%= _.startCase(className).replace(' ', '') %>();
    this.subscription = this.route.params.subscribe((params) => {
      this.load(params['id']);
    });
  }

  load(id: number) {
    this.<%= _.camelCase(className) %>Service.find(id).subscribe((<%= _.camelCase(className) %>) => {
      this.<%= _.camelCase(className) %> = <%= _.camelCase(className) %>;
    });
  }

  ngOnDestroy() {
    this.subscription.unsubscribe();
  }
}
