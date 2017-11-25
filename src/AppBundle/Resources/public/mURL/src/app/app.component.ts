import { Component, OnInit } from '@angular/core';
import { HttpService } from './http.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
	title = 'mURL';
	minified = [];

	constructor() {
	}

	ngOnInit() {
		
	}

	getMinified() {
		/*let path = this._configuration.getHost() + '/minified';
		return this.apiService.getData(path);*/
	}
}
