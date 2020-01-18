import { Component, OnInit } from '@angular/core';
import { Router } from "@angular/router";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { AdminserviceService } from 'app/service/adminservice.service';
declare var $: any;
@Component({
  selector: 'app-addimport',
  templateUrl: './addimport.component.html',
  styleUrls: ['./addimport.component.scss']
})
export class AddimportComponent implements OnInit {

  addForm: FormGroup;
  constructor(private formBuilder: FormBuilder, private router: Router,
    private adminservice: AdminserviceService) {

  }

  ngOnInit() {
    this.addForm = this.formBuilder.group({
      id: ['', Validators.required],
      order_id: ['', Validators.required],
      product_name: ['', Validators.required],
      booking_date: ['', Validators.required],
      user_name: ['', Validators.required],
      user_mob: ['', Validators.required],
      user_email: ['', Validators.required],
      boarding_point: ['', Validators.required],
      quantity: ['', Validators.required],
      price: ['', Validators.required],
      subtotal: ['', Validators.required],
      gst: ['', Validators.required],
      payment_type: ['', Validators.required],
      total: ['', Validators.required]
    });

  }

  onSubmit() {
    console.log('Got it', this.addForm.value);
    this.adminservice.addUserTrek(this.addForm.value).subscribe((data: any[]) => {
      console.log(data);
      if (data['status'] === 200) {
        this.showNotification('top', 'right', data['message'], true);
        this.router.navigate(['import']);
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
