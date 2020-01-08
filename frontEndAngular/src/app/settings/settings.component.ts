import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { AdminserviceService } from 'app/service/adminservice.service';
declare var $: any;

@Component({
  selector: 'app-settings',
  templateUrl: './settings.component.html',
  styleUrls: ['./settings.component.scss']
})
export class SettingsComponent implements OnInit {
  newpass;
  retypepass;
  hide = true;
  reHide = true;
  constructor(private adminservice: AdminserviceService) { }

  changepass: FormGroup = new FormGroup({
    pass: new FormControl('', [Validators.required, Validators.required]),
    newpass: new FormControl('', [Validators.required, Validators.required])
  });

  get passwordInput() { return this.changepass.get('pass'); }
  get repasswordInput() { return this.changepass.get('newpass'); }

  ngOnInit() {
  }

  onSubmit() {
    this.adminservice.changePassword(this.repasswordInput.value).subscribe((data: any[]) => {
      if (data['status'] === 200) {
        this.showNotification('top', 'right', data['message'], true);
      } else {
        this.showNotification('top', 'right', data['message'], false);
      }
    },
      error => {
        this.showNotification('top', 'right', 'Error!', false);
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