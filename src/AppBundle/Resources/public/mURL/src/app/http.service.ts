import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import { AppConfiguration } from './app.configuration';
import { HttpClient, HttpEvent, HttpHandler, HttpHeaders, HttpInterceptor, HttpRequest } from '@angular/common/http';

import { ChartData } from './_model/chart-data';

@Injectable()
export class HttpService {
	chartData: ChartData[];
	dataSubject = new BehaviorSubject<ChartData[]>(this.chartData);
	linkStatistics = this.dataSubject.asObservable();

	constructor(private http: HttpClient, private _configuration: AppConfiguration) { }

	getAllLinks() {
		return this.http.get(this._configuration.getFullPath('url/all'));
	}

	minifyUrl(url: string) {
		return this.http.post(this._configuration.getFullPath('url/minify'), { 'url': url });
	}

	getLinkStatistics(id: number) {
	    return this.http.get(this._configuration.getFullPath('stat/' + id));
	}

	deleteUrl(id: number) {
		return this.http.post(this._configuration.getFullPath('url/delete'), { 'id': id });
	}

	updateUrlShortCode(id, shortCode) {
		return this.http.post(this._configuration.getFullPath('url/update'), { 'id': id, 'shortCode': shortCode });
	}
}
