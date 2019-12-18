import { Component, OnInit } from '@angular/core';
import { AdminserviceService } from 'app/service/adminservice.service';

@Component({
  selector: 'app-user-management',
  templateUrl: './user-management.component.html',
  styleUrls: ['./user-management.component.scss']
})
export class UserManagementComponent implements OnInit {

  constructor(private adminservice: AdminserviceService) { }

  userList = [];
  selectFilter = '';
  searchValue = '';
  dateValue = '';

  ngOnInit() {
    this.adminservice.getUsers().subscribe((responseData: any[]) => {
      this.userList = responseData;
    }
    );
  }

  searchUser() {
    this.adminservice.searchUser(this.selectFilter, this.searchValue).subscribe((responseData: any[]) => {
      this.userList = responseData;
    }
    );
  }

}
