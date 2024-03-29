/*!
<!--

=========================================================
* Material Dashboard Angular - v2.3.0
=========================================================

* Product of Accordify Solutions: https://accordify.co.in/

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

-->
*/
import { enableProdMode } from '@angular/core';
import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';

import { AppModule } from './app/app.module';
import { environment } from './environments/environment';
import 'hammerjs';

if (environment.production) {
  enableProdMode();
}

platformBrowserDynamic().bootstrapModule(AppModule);
