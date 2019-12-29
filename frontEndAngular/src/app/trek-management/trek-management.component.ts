import { Component, OnInit } from '@angular/core';
import { AdminserviceService } from 'app/service/adminservice.service';

@Component({
  selector: 'app-trek-management',
  templateUrl: './trek-management.component.html',
  styleUrls: ['./trek-management.component.scss']
})
export class TrekManagementComponent implements OnInit {

  trekList = [];
  dateValue = new Date('');
  postData = {};

  constructor(private adminservice: AdminserviceService) { }

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
}
