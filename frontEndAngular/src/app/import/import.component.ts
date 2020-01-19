import { Component, OnInit } from '@angular/core';
import { Router } from "@angular/router";
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { AdminserviceService } from 'app/service/adminservice.service';
declare var $: any;

@Component({
  selector: 'app-import',
  templateUrl: './import.component.html',
  styleUrls: ['./import.component.scss']
})
export class ImportComponent implements OnInit {

  formData: FormGroup;

  constructor(private formBuilder: FormBuilder, private router: Router,
    private adminservice: AdminserviceService) { }

  ngOnInit() {

    this.formData = this.formBuilder.group({
      csv: [''],
      file_name: ['', Validators.required],
    });
  }

  onFileChange(event) {
    if (event.target.files.length > 0) {
      const file = event.target.files[0];
      this.formData.get('csv').setValue(file);
    }
  }

  onSubmit() {

    var postFormData: any = new FormData();
    postFormData.append("file_name", this.formData.get('file_name').value);
    postFormData.append("csv", this.formData.get('csv').value);

    this.adminservice.addUserOrder(postFormData).subscribe((data: any[]) => {
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

  downloadTemplate(file_name) {
    let link = document.createElement('a');
    link.setAttribute('type', 'hidden');
    link.href = 'assets/template/' + file_name + ".csv";
    link.download = file_name + ".csv";
    document.body.appendChild(link);
    link.click();
    link.remove();
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
