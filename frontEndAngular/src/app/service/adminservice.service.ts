import { Injectable } from '@angular/core';
import { environment } from 'environments/environment';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})

export class AdminserviceService {

  postData;

  constructor(private router: Router, private http: HttpClient) { }

  //return this.http.get(environment.apiTarget + `/store/store/getAll`);
  login(usernameUi: string, passwordUi: string) {
    this.postData = {
      'username': usernameUi,
      'password': passwordUi
    }
    this.http.post(environment.apiTarget + `/home/login`, this.postData).subscribe(
      data => {
        console.log("Login is successful ");
        this.router.navigate(["dashboard"]);
      },
      error => {
        console.log("Error", error);
      }
    );
  }

}
