import { Component, OnInit, EventEmitter, Input, Output } from '@angular/core';

import { AppConfiguration } from '../app.configuration';

import { HttpService } from '../http.service';
import { Minified } from '../_model/minified';

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.css']
})
export class SidebarComponent implements OnInit {
	@Input() selectedItem;
	@Output() onSelected = new EventEmitter<Minified>();
	@Output() onCreateNew = new EventEmitter<boolean>();
	items;
	loading: boolean = true;

	constructor(private httpService: HttpService) {}

	ngOnInit() {
	}

	loadAllRecords() {
		this.loading = true;
		this.httpService.getAllLinks().subscribe((items) => this.items = items, error => {
			//console.log(error);
		}, () => {
			this.loading = false;
		});
	}

	selectItem(selected: Minified) {
		this.onSelected.emit(selected);
	}

	pushNewUrl(newUrl: Minified) {
		this.items.push(newUrl);
	}

	createNewItem(state: boolean) {
		this.onCreateNew.emit(state);
	}
}
