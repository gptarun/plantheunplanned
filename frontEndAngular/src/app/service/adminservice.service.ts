import { Injectable } from '@angular/core';
import { environment } from 'environments/environment';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';

declare var $: any;

@Injectable({
  providedIn: 'root'
})

export class AdminserviceService {
  postData;

  constructor(private router: Router, private http: HttpClient) {
  }

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
          window.localStorage.removeItem("username");
          window.localStorage.setItem("username", usernameUi.toString());

          this.showNotification('top', 'right', data['Message'], true);
          this.router.navigate(["dashboard"]);
        } else {
          this.showNotification('top', 'right', data['Message'], false);
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

  getBillingInfo(trekID, from, to) {
    this.postData = {
      'id': trekID,
      'from': from,
      'to': to
    }
    return this.http.post(environment.apiTarget + `/home/getBillingInfo`, this.postData);
  }

  getTrekLeaders() {
    return this.http.get(environment.apiTarget + `/home/getTrekLeaders`);
  }
  getTreksByDate(data) {
    return this.http.post(environment.apiTarget + `/home/getBookingTreks`, data);
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
  changePassword(value) {
    this.postData = {
      'data': value
    }
    return this.http.post(environment.apiTarget + `/home/changePassword`, this.postData);
  }

  getTrekById(id) {
    this.postData = {
      'id': id
    }
    return this.http.post(environment.apiTarget + `/home/getTrekById`, this.postData);
  }


  updateTrek(trekData) {
    console.log("in service");
    this.postData = {
      'data': trekData
    }
    return this.http.post(environment.apiTarget + `/home/updateTrek`, this.postData);
  }

  addUserTrek(userTrek) {
    this.postData = {
      'data': userTrek
    }
    return this.http.post(environment.apiTarget + `/home/addUserTrek`, this.postData);
  }

  addUserOrder(userOrder) {
    return this.http.post(environment.apiTarget + `/home/csvImport`, userOrder);
  }

  showNotification(from, align, message, status) {
    const type = ['', 'info', 'success', 'warning', 'danger'];
    var color = 0;
    //const color = Math.floor((Math.random() * 4) + 1);
    if (status) {
      color = 2;
    } else {
      color = 4;
    }

    $.notify({
      icon: "notifications",
      message: message

    }, {
      type: type[color],
      timer: 4000,
      placement: {
        from: from,
        align: align
      },
      template: '<div data-notify="container" class="col-xl-4 col-lg-4 col-11 col-sm-4 col-md-4 alert alert-{0} alert-with-icon" role="alert">' +
        '<button mat-button  type="button" aria-hidden="true" class="close mat-button" data-notify="dismiss">  <i class="material-icons">close</i></button>' +
        '<i class="material-icons" data-notify="icon">notifications</i> ' +
        '<span data-notify="title">{1}</span> ' +
        '<span data-notify="message">{2}</span>' +
        '<div class="progress" data-notify="progressbar">' +
        '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
        '</div>' +
        '<a href="{3}" target="{4}" data-notify="url"></a>' +
        '</div>'
    });
  }
}
