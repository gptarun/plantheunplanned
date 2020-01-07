import { Component, OnInit, Inject } from '@angular/core';
import { AdminserviceService } from 'app/service/adminservice.service';
import { Router } from "@angular/router";
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
declare var $: any;

@Component({
  selector: 'app-user-management',
  templateUrl: './user-management.component.html',
  styleUrls: ['./user-management.component.scss']
})
export class UserManagementComponent implements OnInit {

  constructor(private router: Router, private adminservice: AdminserviceService, public dialog: MatDialog) { }

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

  dialogDesc;
  dialogAction;

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

  deletUser() {
    const dialogRef = this.dialog.open(DeleteDialog, {
      width: '350px',
      data: { description: this.checkListId, action: this.dialogAction }
    });

    dialogRef.afterClosed().subscribe(result => {
      console.log('The dialog was closed');
      this.dialogAction = true;
      this.adminservice.deleteUser(this.checkListId).subscribe((data: any[]) => {
        console.log(data);
        if (data['status'] === 200) {
          this.showNotification('top', 'right', data['message'], true);
          this.router.navigate(['user']);
        } else {
          this.showNotification('top', 'right', data['message'], false);
        }
      },
        error => {
          this.showNotification('top', 'right', 'Error!', false);
        });
    });
  }
  //Check & Uncheck user
  checkUser(event, id) {
    this.userList.forEach(element => {
      if (id == element.ID) {
        if (event.target.checked) {
          this.checkListId.push(element.ID);
          element.selected = event.target.checked;
        }
        else {
          var position = this.checkListId.indexOf(element.ID);
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
      this.userList.forEach(item => { item.selected = checked; this.checkListId.push(item.ID); });

    } else {
      const checked = $event.target.checked;
      console.log(checked);
      this.userList.forEach(item => item.selected = checked);
      this.checkListId = [];
    }
    console.log(this.checkListId);
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

@Component({
  selector: 'user-dialog',
  templateUrl: 'user-dialog.html',
})
export class DeleteDialog {

  constructor(
    public dialogRef: MatDialogRef<DeleteDialog>,
    @Inject(MAT_DIALOG_DATA) public data: DialogData) { }

  onNoClick(): void {
    this.dialogRef.close();
  }

}

export interface DialogData {
  action: boolean;
  description: string;
}