import { Component, OnInit } from '@angular/core';
import { Router } from "@angular/router";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { first } from "rxjs/operators";
import { AdminserviceService } from 'app/service/adminservice.service';
declare var $: any;

@Component({
  selector: 'app-edit-trek',
  templateUrl: './edit-trek.component.html',
  styleUrls: ['./edit-trek.component.scss']
})
export class EditTrekComponent implements OnInit {

  constructor(private formBuilder: FormBuilder, private router: Router, private adminservice: AdminserviceService) { }
  editForm: FormGroup;
  ngOnInit() {
    let editTrekId = window.localStorage.getItem("editTrekId");
    if (!editTrekId) {
      alert("Invalid action.")
      this.router.navigate(['trek']);
      return;
    }

    this.editForm = this.formBuilder.group({
      ID: ['', Validators.required],
      post_author: ['', Validators.required],
      post_date: ['', Validators.required],
      post_date_gmt: ['', Validators.required],
      post_content: ['', Validators.required],
      post_title: ['', Validators.required],
      post_excerpt: ['', Validators.required],
      post_status: ['', Validators.required],
      comment_status: ['', Validators.required],
      ping_status: ['', Validators.required],
      post_password: ['', Validators.required],
      post_name: ['', Validators.required],
      to_ping: ['', Validators.required],
      pinged: ['', Validators.required],
      post_modified: ['', Validators.required],
      post_modified_gmt: ['', Validators.required],
      post_content_filtered: ['', Validators.required],
      post_parent: ['', Validators.required],
      guid: ['', Validators.required],
      menu_order: ['', Validators.required],
      post_type: ['', Validators.required],
      post_mime_type: ['', Validators.required],
      comment_count: ['', Validators.required],
    });

    this.adminservice.getTrekById(editTrekId)
      .subscribe(data => {
        this.editForm.setValue(data[0]);
      });
  }


  onSubmit() {
    console.log('Got it', this.editForm.value);
    this.adminservice.updateTrek(this.editForm.value).subscribe((data: any[]) => {
      console.log(data);
      if (data['status'] === 200) {
        this.showNotification('top', 'right', data['message'], true);
        this.router.navigate(['trek']);
      } else {
        this.showNotification('top', 'right', data['message'], false);
      }
    },
      error => {
        this.showNotification('top', 'right', 'Error!', false);
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
