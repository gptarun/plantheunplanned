import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { NgModule, HostListener } from "@angular/core";
import { FormsModule, ReactiveFormsModule } from "@angular/forms";
import { HttpModule } from "@angular/http";
import { RouterModule } from "@angular/router";
import { AppRoutingModule } from "./app.routing";
import { ComponentsModule } from "./components/components.module";
import { HttpClientModule } from "@angular/common/http";
import { AppComponent } from "./app.component";

import { AgmCoreModule } from "@agm/core";
import { AdminLayoutComponent } from "./layouts/admin-layout/admin-layout.component";
import { LoginComponent } from "./login/login.component";
import { MatIconModule, MatFormFieldModule } from '@angular/material';
import { NgHttpLoaderModule } from 'ng-http-loader';

@NgModule({
  imports: [
    BrowserAnimationsModule,
    FormsModule,
    ReactiveFormsModule,
    HttpModule,
    HttpClientModule,
    ComponentsModule,
    RouterModule,
    MatIconModule,
    MatFormFieldModule,
    AppRoutingModule,
    NgHttpLoaderModule.forRoot(),
    AgmCoreModule.forRoot({
      apiKey: "YOUR_GOOGLE_MAPS_API_KEY"
    })
  ],
  declarations: [AppComponent, AdminLayoutComponent, LoginComponent],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule {
  @HostListener('window:beforeunload', ['$event'])
  public beforeunloadHandler($event) {
    window.localStorage.removeItem('username');
  }
}
