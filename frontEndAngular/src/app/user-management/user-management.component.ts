import { Component, OnInit } from '@angular/core';
import { AdminserviceService } from 'app/service/adminservice.service';
import {Router} from "@angular/router";

@Component({
  selector: 'app-user-management',
  templateUrl: './user-management.component.html',
  styleUrls: ['./user-management.component.scss']
})
export class UserManagementComponent implements OnInit {

  constructor(private router: Router, private adminservice: AdminserviceService) { }

  userList = [];
  selectFilter = '';
  searchValue = '';
  dateValue = new Date('');
  previousPageIndex = 0;
  pageIndex = 0;
  pageSize = 10;
  length = 0;
  postData = {};

  ngOnInit() {
    this.postData = {
      offset: this.pageIndex,
      limit: this.pageSize,
      filter: this.selectFilter,
      search: this.searchValue,
      date: ''
    }
    this.callUserApi(this.postData);
  }

  searchUser() {

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

  editUser(user) {
    console.log('yes');
    console.log(user);
    window.localStorage.removeItem("editUserId");
    window.localStorage.setItem("editUserId", user.ID.toString());
    this.router.navigate(['edituser']);
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
    this.callUserApi(this.postData);
  }

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

  callUserApi(postData) {
    postData.offset = postData.offset * postData.limit;
    this.adminservice.getUsersCount(postData).subscribe((responseData: any[]) => {
      this.length = responseData[0].total;
    }
    );

    this.adminservice.getUsers(postData).subscribe((responseData: any[]) => {
      this.userList = responseData;
    }
    );

  }
}
