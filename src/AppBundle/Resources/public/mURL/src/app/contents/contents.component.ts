import { Component, AfterViewInit, ViewChild } from '@angular/core';
import { Location, LocationStrategy, PathLocationStrategy } from '@angular/common';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';

import { HttpService } from '../http.service';
import { Minified } from '../_model/minified';
import { SidebarComponent } from '../sidebar/sidebar.component';
import { ConfirmationModalComponent } from '../confirmation-modal/confirmation-modal.component';

@Component({
  selector: 'app-contents',
  providers: [ Location, { provide: LocationStrategy, useClass: PathLocationStrategy } ],
  templateUrl: './contents.component.html',
  styleUrls: ['./contents.component.css']
})
export class ContentsComponent implements AfterViewInit {
	selectedItem: Minified;
	longUrl: string;
	shortCode: string;
	currentShortUrl: string;
	location: Location;
	isNewUrl: boolean = false;
	isEditMode: boolean = false;
	@ViewChild(SidebarComponent) private sidebarComponent: SidebarComponent;

	constructor(private httpService: HttpService, location: Location, private modalService: NgbModal) {
		this.location = location;
	}

	ngAfterViewInit() {
		this.sidebarComponent.loadAllRecords();
		this.isNewUrl = true;
	}

	onSelected(selected: Minified) {
		this.selectedItem = selected;
		this.longUrl = selected.longUrl;
		this.isNewUrl = false;
		return false;
	}

	onCreateNew(state: boolean) {
		this.isNewUrl = true;
		this.selectedItem = null;
		this.longUrl = '';
		this.currentShortUrl = '';
	}

	getCurrentShortUrl(shortCode: string) {
		if (this.selectedItem && shortCode) {
			return window.location.protocol + '//' + window.location.hostname + this.location.prepareExternalUrl(this.location.normalize(shortCode));
		}
		return false;
	}

	getMinifiedUrl(event: any) {
		console.log(event);
		this.selectedItem = new Minified();
		this.selectedItem.longUrl = this.longUrl;
		if (event.target) {
			this.selectedItem.shortCode = event.target.value;
			return event.target.value;
		}
		return null;
	}

	minify() {
		this.httpService.minifyUrl(this.longUrl).subscribe((minified: Minified) => this.selectedItem = minified, error => {
			console.log(error);
		}, () => {
			this.sidebarComponent.pushNewUrl(this.selectedItem);
		});
	}

	editUrl() {
		this.isEditMode = true;
		this.shortCode = this.selectedItem.shortCode;
	}

	updateUrlShortCode(id) {
		const modalRef = this.modalService.open(ConfirmationModalComponent)
                modalRef.componentInstance.title = 'Confirm your action';
                modalRef.componentInstance.confirmationText = 'Are you sure?';
                modalRef.componentInstance.yesBtn = 'Update';
                modalRef.componentInstance.noBtn = 'Cancel';
                modalRef.result.then((result) => {
                        if (result == true) {
				this.httpService.updateUrlShortCode(id, this.shortCode).subscribe((response: any) => status = response, error => {
					console.log(error);
					this.isEditMode = false;
				}, () => {
					console.log(status);
					this.selectedItem.shortCode = this.shortCode;
					this.shortCode = null;
					this.isEditMode = false;
				});
			} else {
				this.isEditMode = false;
			}
		});
	}

	cancelShortCodeEdit() {
		this.isEditMode = false;
	}

	delete() {
		const modalRef = this.modalService.open(ConfirmationModalComponent)
		modalRef.componentInstance.title = 'Confirm your action';
		modalRef.componentInstance.confirmationText = 'Are you sure?';
		modalRef.componentInstance.yesBtn = 'Delete';
		modalRef.componentInstance.noBtn = 'Cancel';
		modalRef.result.then((result) => {
			if (result == true) {
				this.httpService.deleteUrl(this.selectedItem.id).subscribe((response: any) => status = response, error => {
					console.log(error);
				}, () => {
					this.onCreateNew(true);
					this.sidebarComponent.loadAllRecords();
				});
			}
		});
	}

}
