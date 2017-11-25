import { Injectable } from '@angular/core';

@Injectable()
export class AppConfiguration {
	host = 'http://mintymint.dev/app_dev.php/';

	getHost() {
		return this.host;
	}

	getFullPath(path) {
		return this.host + path;
	}
}