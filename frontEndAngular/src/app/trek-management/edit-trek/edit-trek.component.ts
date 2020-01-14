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
    let userId = window.localStorage.getItem("editUserId");
    if (!userId) {
      alert("Invalid action.")
      this.router.navigate(['user']);
      return;
    }

    this.editForm = this.formBuilder.group({
      ID: ['', Validators.required],
      user_login: ['', Validators.required],
      user_pass: ['', Validators.required],
      user_nicename: ['', Validators.required],
      user_email: ['', Validators.required],
      user_url: ['', Validators.required],
      user_registered: ['', Validators.required],
      user_activation_key: ['', Validators.required],
      user_status: ['', Validators.required],
      display_name: ['', Validators.required]
    });

    this.adminservice.getUserById(userId)
      .subscribe(data => {
        this.editForm.setValue(data[0]);
      });
  }

}
