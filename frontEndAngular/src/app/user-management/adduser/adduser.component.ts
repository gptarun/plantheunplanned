import { Component, OnInit } from '@angular/core';
import { Router } from "@angular/router";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { first } from "rxjs/operators";
import { AdminserviceService } from 'app/service/adminservice.service';
declare var $: any;

@Component({
  selector: 'app-adduser',
  templateUrl: './adduser.component.html',
  styleUrls: ['./adduser.component.scss']
})
export class AdduserComponent implements OnInit {

  addForm: FormGroup;
  constructor(private formBuilder: FormBuilder, private router: Router,
    private adminservice: AdminserviceService) {

  }

  ngOnInit() {
    this.addForm = this.formBuilder.group({
      ID: ['', Validators.required],
      user_login: ['', Validators.required],
      user_pass: ['', Validators.required],
      user_nicename: ['', Validators.required],
      user_email: ['', Validators.required],
      user_url: ['', Validators.required],
      //user_registered: ['', Validators.required],
      user_activation_key: ['', Validators.required],
      user_status: ['', Validators.required],
      display_name: ['', Validators.required]
    });

  }

  onSubmit() {
    console.log('Got it', this.addForm.value);
    this.adminservice.addUser(this.addForm.value).subscribe((data: any[]) => {
      console.log(data);
      if (data['status'] === 200) {
        this.showNotification('top', 'right', data['message'], true);
        this.router.navigate(['user']);
      } else {
        this.showNotification('top', 'right', data['message'], false);
      }
    },
      error => {
        alert(error);
      });
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
