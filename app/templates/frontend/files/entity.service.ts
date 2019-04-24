import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Response } from '@angular/http';
import { Observable } from 'rxjs/Rx';
import { map } from 'rxjs/operators';
import { environment } from 'environments/environment';
import { <%= className %> } from '../models/<%= _.toLower(className) %>.model';

@Injectable()
export class <%= className %>Service {

  resourceUrl = environment.apiUrl + '/<%= _.toLower(className) %>';

  searchUrl = environment.apiUrl + '/<%= _.toLower(className) %>';

  constructor(private http: HttpClient) { }

  create(<%= _.toLower(className) %>: <%= className %>): Observable<<%= className %>> {
    return this.http.post(this.resourceUrl, pessoa).pipe(
      map((response: Response) => {
        return this.convertItemFromServer(response);
      })
    );
  }

  update(<%= _.toLower(className) %>: <%= className %>): Observable<<%= className %>> {
    return this.http.put(`${this.resourceUrl}/${<%= _.toLower(className) %>.id}`, pessoa).pipe(
      map((response: Response) => {
        return this.convertItemFromServer(response);
      })
    );
  }

  find(id: number): Observable<<%= className %>> {
    return this.http.get(`${this.resourceUrl}/${id}`).pipe(
    	map((response: Response) => {
      		return this.convertItemFromServer(response);
    	}
	  ));
  }

  query(req?: any): Observable<<%= className %>[]> {
    return this.http.get<<%= className %>[]>(this.resourceUrl, { params: req });
  }
  
  delete(id: number) {
    return this.http.delete(`${this.resourceUrl}/${id}`);
  }

  private convertItemFromServer(response: any): <%= className %> {
    const entity: <%= className %> = Object.assign(new <%= className %>(), response.data);
    return entity;
  }
}
