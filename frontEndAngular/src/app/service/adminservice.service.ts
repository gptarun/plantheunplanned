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
        if (data['Success'] == true) {
          console.log("Login is successful ");
          this.router.navigate(["dashboard"]);
        } else {
          alert(data['Message']);
        }
      },
      error => {
        console.log("Error", error);
      }
    );
  }

  getUsers(data) {
    return this.http.post(environment.apiTarget + `/home/searchUser`, data);
  }

  getUserById(id) {
    this.postData = {
      'id': id
    }
    return this.http.post(environment.apiTarget + `/home/getUserById`, this.postData);
  }

  getUsersCount(data) {
    return this.http.post(environment.apiTarget + `/home/searchUserCount`, data);
  }

  addUser(userData) {
    this.postData = {
      'data': userData
    }
    return this.http.post(environment.apiTarget + `/home/addUser`, this.postData);
  }
  updateUser(userData) {
    console.log("in service");
    this.postData = {
      'data': userData
    }
    return this.http.post(environment.apiTarget + `/home/updateUser`, this.postData);
  }

  deleteUser(userList) {
    this.postData = {
      'data': userList
    }
    return this.http.post(environment.apiTarget + `/home/deletUser`, this.postData);
  }

  getTrekLeaders() {
    return this.http.get(environment.apiTarget + `/home/getTrekLeaders`);
  }
  getTreksByDate(data) {
    return this.http.post(environment.apiTarget + `/home/getTreksByDate`, data);
  }
  getEmailTemplates() {
    return this.http.get(environment.apiTarget + `/home/getEmailTemplates`);
  }
  getEmailText(postData) {
    return this.http.post(environment.apiTarget + `/home/getEmailText`, postData);
  }
  sendMail(postData) {
    return this.http.post(environment.apiTarget + `/emailController/send_email`, postData);
  }
}
