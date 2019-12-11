import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-user-management',
  templateUrl: './user-management.component.html',
  styleUrls: ['./user-management.component.scss']
})
export class UserManagementComponent implements OnInit {

  constructor() { }

  userList = [
    {
      id: '1',
      name: 'Tarun',
      email: 'tarun@plan.com',
      mobile: '098765432',
      membershipDate: '22-12-2019',
      membershipType: 'Musafir'
    },
    {
      id: '2',
      name: 'Tarun2',
      email: 'tarun2@plan.com',
      mobile: '0987625432',
      membershipDate: '02-12-2019',
      membershipType: 'Musafir2'
    },
    {
      id: '3',
      name: 'Tarun2',
      email: 'tarun2@plan.com',
      mobile: '0987625432',
      membershipDate: '02-12-2019',
      membershipType: 'Musafir2'
    },
    {
      id: '4',
      name: 'Tarun2',
      email: 'tarun2@plan.com',
      mobile: '0987625432',
      membershipDate: '02-12-2019',
      membershipType: 'Musafir2'
    },
    {
      id: '5',
      name: 'Tarun2',
      email: 'tarun2@plan.com',
      mobile: '0987625432',
      membershipDate: '02-12-2019',
      membershipType: 'Musafir2'
    },
    {
      id: '6',
      name: 'Tarun2',
      email: 'tarun2@plan.com',
      mobile: '0987625432',
      membershipDate: '02-12-2019',
      membershipType: 'Musafir2'
    },
    {
      id: '7',
      name: 'Tarun2',
      email: 'tarun2@plan.com',
      mobile: '0987625432',
      membershipDate: '02-12-2019',
      membershipType: 'Musafir2'
    },
    {
      id: '8',
      name: 'Tarun2',
      email: 'tarun2@plan.com',
      mobile: '0987625432',
      membershipDate: '02-12-2019',
      membershipType: 'Musafir2'
    },
    {
      id: '9',
      name: 'Tarun2',
      email: 'tarun2@plan.com',
      mobile: '0987625432',
      membershipDate: '02-12-2019',
      membershipType: 'Musafir2'
    },
    {
      id: '10',
      name: 'Tarun2',
      email: 'tarun2@plan.com',
      mobile: '0987625432',
      membershipDate: '02-12-2019',
      membershipType: 'Musafir2'
    },
    {
      id: '11',
      name: 'Tarun2',
      email: 'tarun2@plan.com',
      mobile: '0987625432',
      membershipDate: '02-12-2019',
      membershipType: 'Musafir2'
    }
  ];

  ngOnInit() {
  }

}
