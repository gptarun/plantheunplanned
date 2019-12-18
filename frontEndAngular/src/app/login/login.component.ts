import { Component, OnInit } from '@angular/core';

import { AdminserviceService } from 'app/service/adminservice.service';


@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  constructor(private adminservice: AdminserviceService) { }

  username: string;
  password: string;

  ngOnInit() {
  }

  login() {
    this.adminservice.login(this.username, this.password);
  }

}
