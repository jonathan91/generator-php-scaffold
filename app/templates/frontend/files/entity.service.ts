import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs/Rx';
import { environment } from 'environments/environment';
import { <%= _.startCase(className).replace(' ', '') %> } from '../models/<%= _.kebabCase(className).toLowerCase() %>.model';

@Injectable()
export class <%= _.startCase(className).replace(' ', '') %>Service {

  resourceUrl = environment.apiUrl + '/<%= _.toLower(className) %>';

  constructor(private http: HttpClient) { }

  create(<%= _.camelCase(className).replace(' ', '') %>: <%= _.startCase(className).replace(' ', '') %>): Observable<<%= _.startCase(className).replace(' ', '') %>> {
    return this.http.post<<%= _.startCase(className).replace(' ', '') %>>(this.resourceUrl, <%= _.camelCase(className).replace(' ', '') %>);
  }

  update(<%= _.camelCase(className).replace(' ', '') %>: <%= _.startCase(className).replace(' ', '') %>): Observable<<%= _.startCase(className).replace(' ', '') %>> {
    return this.http.put<<%= _.startCase(className).replace(' ', '') %>>(`${this.resourceUrl}/${<%= _.camelCase(className).replace(' ', '') %>.id}`, <%= _.camelCase(className).replace(' ', '') %>);
  }

  find(id: number): Observable<<%= _.startCase(className).replace(' ', '') %>> {
    return this.http.get<<%= _.startCase(className).replace(' ', '') %>>(`${this.resourceUrl}/${id}`);
  }

  query(req?: any): Observable<<%= _.startCase(className).replace(' ', '') %>[]> {
    return this.http.get<<%= _.startCase(className).replace(' ', '') %>[]>(this.resourceUrl, { params: req });
  }
  
  delete(id: number) {
    return this.http.delete(`${this.resourceUrl}/${id}`);
  }
}
