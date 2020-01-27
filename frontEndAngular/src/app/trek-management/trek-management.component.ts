import { Component, OnInit } from '@angular/core';
import { AdminserviceService } from 'app/service/adminservice.service';
import { Router } from "@angular/router";

@Component({
  selector: 'app-trek-management',
  templateUrl: './trek-management.component.html',
  styleUrls: ['./trek-management.component.scss']
})
export class TrekManagementComponent implements OnInit {

  trekList = [];
  postData = {};
  toDateValue = new Date('');
  fromDateValue = new Date('');

  constructor(private router: Router, private adminservice: AdminserviceService) { }

  ngOnInit() {
  }


  changeDate(eventDate) {
    this.postData = {
      from: this.fromDateValue,
      to: this.toDateValue
    }
    this.adminservice.getTreksByDate(this.postData).subscribe((responseData: any[]) => {
      this.trekList = responseData;
    })
  }

  changeFromDate(eventDate) {
    this.postData = {
      from: this.fromDateValue,
      to: this.toDateValue
    }
    this.adminservice.getTreksByDate(this.postData).subscribe((responseData: any[]) => {
      this.trekList = responseData;
    })
  }

  editTrek(trek) {
    window.localStorage.removeItem("editTrekId");
    window.localStorage.setItem("editTrekId", trek.id.toString());
    this.router.navigate(['edittrek']);
  }
}
