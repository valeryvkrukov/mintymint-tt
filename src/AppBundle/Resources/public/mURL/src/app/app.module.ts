import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { RouterModule, Routes } from '@angular/router';

import { AppConfiguration } from './app.configuration';
import { HttpService } from './http.service';

import { AppComponent } from './app.component';
import { HeaderComponent } from './header/header.component';
import { SidebarComponent } from './sidebar/sidebar.component';
import { ContentsComponent } from './contents/contents.component';
import { PieChartComponent } from './pie-chart/pie-chart.component';
import { ConfirmationModalComponent } from './confirmation-modal/confirmation-modal.component';

const routes: Routes = [
  { path: '', component: ContentsComponent },
  { path: 'edit', component: ContentsComponent },
  { path: '**', redirectTo: '' }
];

@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    SidebarComponent,
    ContentsComponent,
    PieChartComponent,
    ConfirmationModalComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    FormsModule,
    NgbModule.forRoot(),
    RouterModule.forRoot(routes, { enableTracing: false })
  ],
  entryComponents: [
    ConfirmationModalComponent
  ],
  providers: [
    AppConfiguration,
    HttpService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
