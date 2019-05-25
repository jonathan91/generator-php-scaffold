import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs/Rx';
import { environment } from 'environments/environment';
import { <%= className %> } from '../models/<%= _.toLower(className) %>.model';

@Injectable()
export class <%= className %>Service {

  resourceUrl = environment.apiUrl + '/<%= _.toLower(className) %>';

  constructor(private http: HttpClient) { }

  create(<%= _.toLower(className) %>: <%= className %>): Observable<<%= className %>> {
    return this.http.post<<%= className %>>(this.resourceUrl, <%= _.toLower(className) %>);
  }

  update(<%= _.toLower(className) %>: <%= className %>): Observable<<%= className %>> {
    return this.http.put<<%= className %>>(`${this.resourceUrl}/${<%= _.toLower(className) %>.id}`, <%= _.toLower(className) %>);
  }

  find(id: number): Observable<<%= className %>> {
    return this.http.get<<%= className %>>(`${this.resourceUrl}/${id}`);
  }

  query(req?: any): Observable<<%= className %>[]> {
    return this.http.get<<%= className %>[]>(this.resourceUrl, { params: req });
  }
  
  delete(id: number) {
    return this.http.delete(`${this.resourceUrl}/${id}`);
  }
}
