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
  dateValue = new Date('');
  postData = {};

  constructor(private router: Router, private adminservice: AdminserviceService) { }

  ngOnInit() {
  }


  changeDate(eventDate) {

    this.dateValue.setDate(eventDate.getDate() + 1);
    this.postData = {
      date: this.dateValue
    }
    this.adminservice.getTreksByDate(this.postData).subscribe((responseData: any[]) => {
      this.trekList = responseData;
    })
  }

  editTrek(trek){
    window.localStorage.removeItem("editTrekId");
    window.localStorage.setItem("editTrekId", trek.ID.toString());
    this.router.navigate(['edittrek']);
  }
}
