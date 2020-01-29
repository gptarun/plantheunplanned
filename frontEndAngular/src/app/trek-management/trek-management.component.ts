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
  newTrekList = {};
  treks = [];
  constructor(private router: Router, private adminservice: AdminserviceService) { }

  ngOnInit() {
  }


  changeDate(eventDate) {
    this.postData = {
      from: this.fromDateValue,
      to: this.toDateValue
    }
    this.adminservice.getManageTreks(this.postData).subscribe((responseData: any[]) => {
      //this.trekList = responseData;
      this.filterTrekData(responseData);
    })
  }

  changeFromDate(eventDate) {
    this.postData = {
      from: this.fromDateValue,
      to: this.toDateValue
    }
    this.adminservice.getManageTreks(this.postData).subscribe((responseData: any[]) => {
      //this.trekList = responseData;
      this.filterTrekData(responseData);
    })
  }

  filterTrekData(responseData) {
    this.newTrekList = {};
    var id = '';
    this.treks = [];
    responseData.forEach((element, index) => {
      let last: any = responseData[responseData.length - 1];
      console.log(last);
      if (id == '') {
        id = element.post_id;
        this.newTrekList['ID'] = element.post_id;
      } else {
        if (id != element.post_id) {
          this.treks.push(this.newTrekList);
          id = element.post_id;
          this.newTrekList = {};
          this.newTrekList['ID'] = element.post_id;
        }
      }

      if (element.meta_key == "_regular_price" || element.meta_key == "_sale_price" || element.meta_key == "_price"
        || element.meta_key == "total_sales" || element.meta_key == "tour_booking_periods" || element.meta_key == "name") {
        if (element.meta_key == "tour_booking_periods") {

        } else {
          this.newTrekList[element.meta_key] = element.meta_value;
        }
      }

      if (element.meta_id == last.meta_id) {
        this.treks.push(this.newTrekList);
      }
    });
  }
  editTrek(trek) {
    window.localStorage.removeItem("editTrekId");
    window.localStorage.setItem("editTrekId", trek.id.toString());
    this.router.navigate(['edittrek']);
  }
}
