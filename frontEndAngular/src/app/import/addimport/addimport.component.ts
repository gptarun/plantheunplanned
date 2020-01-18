import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { AdminserviceService } from 'app/service/adminservice.service';
import { Router } from "@angular/router";

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

}
