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
      post_title: ['', Validators.required],
      post_content: ['', Validators.required],
      post_status: ['', Validators.required],
      post_name: ['', Validators.required],
      post_modified: ['', Validators.required],
      post_date: ['', Validators.required],
      post_type: ['', Validators.required],     
    });

    this.adminservice.getUserById(userId)
      .subscribe(data => {
        this.editForm.setValue(data[0]);
      });
  }

}
