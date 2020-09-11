import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable()
export class CardService {

    constructor(private http: HttpClient) {}

    public query(url: string, params?: any) {
        return this.http.get<{data: Array<Object>, total: number}>(`${url}`, { params: params });
    }
}
