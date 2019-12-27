import { Component, OnInit } from '@angular/core';
import { AdminserviceService } from 'app/service/adminservice.service';
import { FormControl } from '@angular/forms';
import { Observable } from 'rxjs';
import { map, startWith } from 'rxjs/operators';
import { Key, element } from 'protractor';

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
  leaderValue = "";
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

  isUser: boolean = false;
  checkAllUser: boolean = false;
  checkListId = [];

  trekList = [];

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
  displayFn(user?: any): string | undefined {
    console.log(user);
    return user ? user.name : undefined;
  }

  //These will be use for auto complete dropdown in Front end -> Trek Leader list
  private _filter(name: string): any[] {
    const filterValue = name.toLowerCase();
    return this.options.filter(option => option.name.toLowerCase().indexOf(filterValue) === 0);
  }

  //These will be use for auto complete dropdown in Front end -> Trek Leader list
  displayFnTrek(trek?: any): string | undefined {
    console.log(trek);
    return trek ? trek.post_title : undefined;
  }

  //These will be use for auto complete dropdown in Front end -> Trek Leader list
  private _filterTrek(post_title: string): any[] {
    const filterValue = post_title.toLowerCase();
    return this.trekOptions.filter(trekOptions => trekOptions.post_title.toLowerCase().indexOf(filterValue) === 0);
  }

  //Check & Uncheck user
  checkUser(event) {
    //logic to remove id from checklistid
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
    }
    );

  }
}
