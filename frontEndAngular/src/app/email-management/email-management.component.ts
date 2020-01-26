import { Component, OnInit } from '@angular/core';
import { AdminserviceService } from 'app/service/adminservice.service';
import { FormControl } from '@angular/forms';
import { Observable, empty } from 'rxjs';
import { map, startWith } from 'rxjs/operators';
import { Key, element } from 'protractor';
declare var $: any;

@Component({
  selector: 'app-email-management',
  templateUrl: './email-management.component.html',
  styleUrls: ['./email-management.component.scss']
})
export class EmailManagementComponent implements OnInit {

  //Stores the selected trek leaders
  selectedLeaders = [];
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

  //Table data
  newUserList = {};
  users = [];
  selectFilter = '';
  searchValue = '';
  toDateValue = new Date('');
  fromDateValue = new Date('');
  previousPageIndex = 0;
  pageIndex = 0;
  pageSize = 10;
  length = 0;
  postData = {};
  trekDate = 0;

  checkListId = [];

  //Email Data
  trekList = [];
  emailList = [];
  emailText = '';
  emailTemplate = 0;
  meet_your_fellow_trekkers = "";
  your_point_of_contact = "";
  checked_user_email = "";
  driveLink = "";
  whatsappLink = "";
  emailSubject = "";
  boardingPoints = [];
  trekImageUrl = "";
  fileData = "";

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
    //need to fetch user details
    return trek ? trek.post_title : undefined;
  }

  getBillingInfo(ID: any, from, to) {
    this.newUserList = {};
    var count = 0;
    this.users = [];
    console.log(ID, from, to);
    this.adminservice.getBillingInfo(ID, from, to).subscribe((responseData: any[]) => {
      this.trekDate = responseData[0];
      responseData[1].forEach((element, index) => {

        if (element.meta_key == "_billing_first_name" || element.meta_key == "_billing_last_name" || element.meta_key == "_billing_email"
          || element.meta_key == "_billing_phone") {
          this.newUserList[element.meta_key] = element.meta_value;
          //console.log(element.meta_value);
        }
        if (element.meta_key == "_transaction_id") {
          this.newUserList['ID'] = count;
          this.newUserList['selected'] = false;
          this.users.push(this.newUserList);
          this.newUserList = {};
          count++;
        }

      });

      console.log(this.users);
    });

  }

  //These will be use for auto complete dropdown in Front end -> Trek list
  private _filterTrek(post_title: string): any[] {
    const filterValue = post_title.toLowerCase();
    return this.trekOptions.filter(trekOptions => trekOptions.post_title.toLowerCase().indexOf(filterValue) === 0);
  }

  //Check & Uncheck user
  checkUser(event, id) {
    this.users.forEach(element => {
      if (id == element.ID) {
        if (event.target.checked) {
          this.checkListId.push(element);
          element.selected = event.target.checked;

          if (this.checked_user_email) {
            this.updateTemplate();
          }

        }
        else {
          var position = this.checkListId.indexOf(element);
          if (position === -1) {
            return null;
          }
          this.checkListId.splice(position, 1);
          element.selected = event.target.checked;

          if (this.checked_user_email) {
            this.updateTemplate();
          }

        }
      }
    });
    console.log(this.checkListId);
  }

  checkUserAll($event) {
    if ($event.target.checked) {
      this.checkListId = [];
      const checked = $event.target.checked;
      this.users.forEach(item => { item.selected = checked; this.checkListId.push(item); });

      if (this.checked_user_email) {
        this.updateTemplate();
      }

    } else {
      const checked = $event.target.checked;
      this.users.forEach(item => item.selected = checked);
      this.checkListId = [];

      if (this.checked_user_email) {
        this.updateTemplate();
      }

    }
    console.log(this.checkListId);
  }

  selectEmail(event) {
    this.postData = {
      emailId: event.value
    }
    this.checked_user_email = event.value;
    this.updateTemplate();
  }

  sendMail() {
    if (this.emailList[this.emailTemplate - 1].email_name == 'Bon Voyage') {
      this.emailSubject = this.emailList[this.emailTemplate - 1].email_name + '! ' + this.trekValue['post_title'];
    } else {
      this.emailSubject = this.emailList[this.emailTemplate - 1].email_name + ' Back!' + this.trekValue['post_title'];
    }
    var userEmails = [];
    this.checkListId.forEach(element => {
      userEmails.push(element.user_email);
    });
    var leadersList = [];
    this.selectedLeaders.forEach(element => {
      leadersList.push(element.email);
    })

    //Type will help in indentify which mail is coming from Angular and Wordpress
    this.postData = {
      users: userEmails,
      emailBody: this.emailText,
      leaderEmail: leadersList,
      subject: this.emailSubject,
      type: "custom"
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


  getSelectedTrekLeaders() {
    this.updateTemplate();
  }
  onDriveChange(value) {
    if (value == '') {
      this.driveLink = '';
    } else {
      this.driveLink = "href='" + value + "'";
    }
    this.updateTemplate();
  }
  onWasupChange(value) {
    if (value == '') {
      this.whatsappLink = '';
    } else {
      this.whatsappLink = "href='" + value + "'";
    }
    this.updateTemplate();
  }

  onFileChange(event) {

    if (event.target.files.length > 0) {
      var reader: FileReader = new FileReader();
      reader.readAsDataURL(event.target.files[0]); // read file as data url

      reader.onload = (event1) => { // called once readAsDataURL is completed
        this.fileData = "src = '" + reader.result + "'";
      }
      this.updateTemplate();
    } else {
      this.fileData = "src = ''";
      this.updateTemplate();
    }
  }

  updateTemplate() {
    this.adminservice.getEmailText({ emailId: this.checked_user_email }).subscribe((responseData: any[]) => {
      this.meet_your_fellow_trekkers = "";
      this.meet_your_fellow_trekkers += "<table border='1' cellpadding='0' cellspacing='0' dir='ltr'><colgroup><col width='50'><col width='145'><col width='200'><col width='150'></colgroup><tr><th>Sr.No</th><th>Name</th><th>Boarding Point</th><th>Contact</th></tr>";
      this.checkListId.forEach((element, $index) => {
        $index++;
        this.meet_your_fellow_trekkers += "<tr><td>" + $index + "</td><td>" + element._billing_first_name + ' ' + element._billing_last_name + "</td><td>" + element.user_email + "</td><td>" + element._billing_email + "</td></tr>";
      });
      this.meet_your_fellow_trekkers += "</table>";

      if (this.selectedLeaders.length != 0) {
        this.your_point_of_contact = "";
        this.your_point_of_contact += "<table border='1' cellpadding='0' cellspacing='0' dir='ltr'><colgroup><col width='50'><col width='145'><col width='200'><col width='403'></colgroup><tr><th>Sr.No</th><th>Name</th><th>Contact</th><th>Bio</th></tr>";

        this.selectedLeaders.forEach((element, $index) => {
          $index++;
          this.your_point_of_contact += "<tr><td>" + $index + "</td><td>" + element.name + "</td><td>" + element.mobile + "</td><td>" + element.bio + "</td></tr>";
        });
        this.your_point_of_contact += "</table>";
      }
      this.emailText = responseData[0].email_text.replace("{{your_point_of_contact}}", this.your_point_of_contact).replace("{{meet_your_fellow_trekkers}}", this.meet_your_fellow_trekkers).replace("{{your_drive_link}}", this.driveLink).replace("{{your_whatsapp_url}}", this.whatsappLink).replace("{{your_trek_image}}", this.fileData);
    });
  }

  clearFilter() {
    this.selectFilter = '';
    this.searchValue = '';
    this.toDateValue = new Date('');
    this.users = [];
    this.postData = {
      offset: this.pageIndex,
      limit: this.pageSize,
      filter: this.selectFilter,
      search: this.searchValue,
      date: ''
    }
    this.trekValue = '';
    this.trekOptions = [];
  }

  //Creating manual pagination to load data faster
  // setPaginaton(event) {
  //   this.postData = {
  //     offset: event.pageIndex,
  //     limit: event.pageSize,
  //     filter: this.selectFilter,
  //     search: this.searchValue,
  //     date: this.toDateValue
  //   }
  //   this.callUserApi(this.postData);
  // }

  changeDate(eventDate) {
    this.toDateValue.setDate(eventDate.getDate() + 1);
    this.fromDateValue.setDate(eventDate.getDate() + 1);
    this.postData = {
      from: this.fromDateValue,
      to: this.toDateValue
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

  changeFromDate(eventDate) {
    this.fromDateValue.setDate(eventDate.getDate() + 1);
    this.postData = {
      from: this.fromDateValue
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
  // //Calling API to get user list with pagination
  // callUserApi(postData) {
  //   postData.offset = postData.offset * postData.limit;
  //   this.checkListId = [];
  //   this.adminservice.getUsersCount(postData).subscribe((responseData: any[]) => {
  //     this.length = responseData[0].total;
  //   }
  //   );

  //   this.adminservice.getUsers(postData).subscribe((responseData: any[]) => {
  //     this.userList = responseData;
  //     this.userList.forEach(element => {
  //       element.selected = false;
  //     });
  //   }
  //   );

  // }


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
