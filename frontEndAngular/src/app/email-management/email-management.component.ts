import { Component, OnInit } from '@angular/core';
import { AdminserviceService } from 'app/service/adminservice.service';
import { FormControl } from '@angular/forms';
import { Observable } from 'rxjs';
import { map, startWith } from 'rxjs/operators';

@Component({
  selector: 'app-email-management',
  templateUrl: './email-management.component.html',
  styleUrls: ['./email-management.component.scss']
})
export class EmailManagementComponent implements OnInit {
  trekLeaders;
  leaderValue = "";
  showDropdown = false;
  myControl = new FormControl();
  options: any[] = [];
  filteredOptions: Observable<any[]>;

  constructor(private adminservice: AdminserviceService) { }

  ngOnInit() {
    this.adminservice.getTrekLeaders().subscribe((responseData: any[]) => {
      this.options = responseData;

      this.filteredOptions = this.myControl.valueChanges
        .pipe(
          startWith(''),
          map(value => typeof value === 'string' ? value : value.name),
          map(name => name ? this._filter(name) : this.options.slice())
        );
    }
    );
    console.log(this.filteredOptions);
  }

  displayFn(user?: any): string | undefined {
    console.log(user);
    return user ? user.name : undefined;
  }

  private _filter(name: string): any[] {
    const filterValue = name.toLowerCase();

    return this.options.filter(option => option.name.toLowerCase().indexOf(filterValue) === 0);
  }
}
