import { Component, OnInit } from '@angular/core';
import { AdminserviceService } from 'app/service/adminservice.service';
import { FormControl } from '@angular/forms';
import { Observable } from 'rxjs';
import { map, startWith } from 'rxjs/operators';
import { Key, element } from 'protractor';
declare var $: any;

@Component({
  selector: 'app-email-management',
  templateUrl: './email-management.component.html',
  styleUrls: ['./email-management.component.scss']
})
export class EmailManagementComponent implements OnInit {
  //Trek listing autocomplete
  trekValue = "";
  myTrekControl = new FormControl();
  trekOptions: any[] = [];
  filteredTrekOptions: Observable<any[]>;


  //Trek Leader autocomplete
  leaderValue = {};
  myControl = new FormControl();
  options: any[] = [];
  filteredOptions: Observable<any[]>;

  userList = [];
  selectFilter = '';
  searchValue = '';
  dateValue = new Date('');
  previousPageIndex = 0;
  pageIndex = 0;
  pageSize = 10;
  length = 0;
  postData = {};

  checkListId = [];

  trekList = [];
  emailList = [];
  emailText = '';
  emailTemplate = '';

  constructor(private adminservice: AdminserviceService) { }

  ngOnInit() {
    this.adminservice.getTrekLeaders().subscribe((responseData: any[]) => {
      this.options = responseData;
      this.filteredOptions = this.myControl.valueChanges
        .pipe(
          startWith(''),
          map(value => typeof value === 'string' ? value : value.name),
          map(name => name ? this._filter(name) : this.options.slice())
        );
    }
    );

    this.adminservice.getEmailTemplates().subscribe((responseData: any[]) => {
      this.emailList = responseData;
    });

    this.postData = {
      offset: this.pageIndex,
      limit: this.pageSize,
      filter: this.selectFilter,
      search: this.searchValue,
      date: ''
    }
    this.callUserApi(this.postData);
  }

  //These will be use for auto complete dropdown in Front end -> Trek Leader list
  //User means Trek Leader
  displayFn(user?: any): string | undefined {
    console.log(user);
    this.leaderValue = user;
    return user ? user.name : undefined;
  }

  //These will be use for auto complete dropdown in Front end -> Trek Leader list
  private _filter(name: string): any[] {
    const filterValue = name.toLowerCase();
    return this.options.filter(option => option.name.toLowerCase().indexOf(filterValue) === 0);
  }

  //These will be use for auto complete dropdown in Front end -> Trek list
  displayFnTrek(trek?: any): string | undefined {
    console.log(trek);
    return trek ? trek.post_title : undefined;
  }

  //These will be use for auto complete dropdown in Front end -> Trek list
  private _filterTrek(post_title: string): any[] {
    const filterValue = post_title.toLowerCase();
    return this.trekOptions.filter(trekOptions => trekOptions.post_title.toLowerCase().indexOf(filterValue) === 0);
  }

  //Check & Uncheck user
  checkUser(event, id) {
    this.userList.forEach(element => {
      if (id == element.ID) {
        if (event.target.checked) {
          this.checkListId.push(element.user_email);
          element.selected = event.target.checked;
        }
        else {
          var position = this.checkListId.indexOf(element.user_email);
          if (position === -1) {
            return null;
          }
          this.checkListId.splice(position, 1);
          element.selected = event.target.checked;
        }
      }
    });
    console.log(this.checkListId);
  }

  checkUserAll($event) {
    if ($event.target.checked) {
      this.checkListId = [];
      const checked = $event.target.checked;
      console.log(checked);
      this.userList.forEach(item => { item.selected = checked; this.checkListId.push(item.user_email); });

    } else {
      const checked = $event.target.checked;
      console.log(checked);
      this.userList.forEach(item => item.selected = checked);
      this.checkListId = [];
    }
    console.log(this.checkListId);
  }

  searchUser() {
    //As date is comming in diffrent time zone which is giving one less day in IST (+5:30)
    this.dateValue.setDate(this.dateValue.getDate() + 1);

    this.postData = {
      offset: this.pageIndex,
      limit: this.pageSize,
      filter: this.selectFilter,
      search: this.searchValue,
      date: this.dateValue
    }
    this.callUserApi(this.postData);
  }

  selectEmail(event) {
    this.postData = {
      emailId: event.value
    }
    this.adminservice.getEmailText(this.postData).subscribe((responseData: any[]) => {
      this.emailText = responseData[0].email_text;
    });
  }

  sendMail() {

    this.postData = {
      users: this.checkListId,
      emailBody: this.emailText,
      leaderEmail: this.leaderValue['email']
    }
    this.adminservice.sendMail(this.postData).subscribe((responseData: any[]) => {     
      if (responseData['status'] === 200) {
        this.showNotification('top', 'right', responseData['message'], true);
      } else {
        this.showNotification('top', 'right', responseData['message'], false);
      }
    },
      error => {
        this.showNotification('top', 'right', 'Error!', false);
      }
    );
  }
  clearFilter() {
    this.selectFilter = '';
    this.searchValue = '';
    this.dateValue = new Date('');
    this.postData = {
      offset: this.pageIndex,
      limit: this.pageSize,
      filter: this.selectFilter,
      search: this.searchValue,
      date: ''
    }
    this.trekValue = '';
    this.trekOptions = [];
    this.callUserApi(this.postData);
  }

  //Creating manual pagination to load data faster
  setPaginaton(event) {
    this.postData = {
      offset: event.pageIndex,
      limit: event.pageSize,
      filter: this.selectFilter,
      search: this.searchValue,
      date: this.dateValue
    }
    this.callUserApi(this.postData);
  }

  changeDate(eventDate) {

    this.dateValue.setDate(eventDate.getDate() + 1);
    this.postData = {
      date: this.dateValue
    }
    this.adminservice.getTreksByDate(this.postData).subscribe((responseData: any[]) => {
      this.trekOptions = responseData;
      this.trekList = responseData;
      this.filteredTrekOptions = this.myTrekControl.valueChanges
        .pipe(
          startWith(''),
          map(value => typeof value === 'string' ? value : value.post_title),
          map(post_title => post_title ? this._filterTrek(post_title) : this.trekOptions.slice())
        );
    })
  }

  //Calling API to get user list with pagination
  callUserApi(postData) {
    postData.offset = postData.offset * postData.limit;
    this.checkListId = [];
    this.adminservice.getUsersCount(postData).subscribe((responseData: any[]) => {
      this.length = responseData[0].total;
    }
    );

    this.adminservice.getUsers(postData).subscribe((responseData: any[]) => {
      this.userList = responseData;
      this.userList.forEach(element => {
        element.selected = false;
      });
    }
    );

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
