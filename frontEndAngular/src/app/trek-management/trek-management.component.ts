import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-trek-management',
  templateUrl: './trek-management.component.html',
  styleUrls: ['./trek-management.component.scss']
})
export class TrekManagementComponent implements OnInit {

  trekList = [
    {
      id: '1',
      trekName: 'Tarun',
      trekDate: 'tarun@plan.com',
      ticket: '098765432',
      regularPrice: '1500',
      earlyBird: '22-12-2019',
      dynamicPrice: 'Musafir',
      insurance: 'NA',
      plantTree: 'NA'
    },
    {
      id: '2',
      trekName: 'Tarun2',
      trekDate: 'tarun2@plan.com',
      ticket: '0987625432',
      regularPrice: '1500',
      earlyBird: '02-12-2019',
      dynamicPrice: 'Musafir2',
      insurance: 'NA',
      plantTree: 'NA'
    },
    {
      id: '3',
      trekName: 'Tarun2',
      trekDate: 'tarun2@plan.com',
      ticket: '0987625432',
      regularPrice: '1500',
      earlyBird: '02-12-2019',
      dynamicPrice: 'Musafir2',
      insurance: 'NA',
      plantTree: 'NA'
    },
    {
      id: '4',
      trekName: 'Tarun2',
      trekDate: 'tarun2@plan.com',
      ticket: '0987625432',
      regularPrice: '1500',
      earlyBird: '02-12-2019',
      dynamicPrice: 'Musafir2',
      insurance: 'NA',
      plantTree: 'NA'
    },
    {
      id: '5',
      trekName: 'Tarun2',
      trekDate: 'tarun2@plan.com',
      ticket: '0987625432',
      regularPrice: '1500',
      earlyBird: '02-12-2019',
      dynamicPrice: 'Musafir2',
      insurance: 'NA',
      plantTree: 'NA'
    },
    {
      id: '6',
      trekName: 'Tarun2',
      trekDate: 'tarun2@plan.com',
      ticket: '0987625432',
      regularPrice: '1500',
      earlyBird: '02-12-2019',
      dynamicPrice: 'Musafir2',
      insurance: 'NA',
      plantTree: 'NA'
    },
    {
      id: '7',
      trekName: 'Tarun2',
      trekDate: 'tarun2@plan.com',
      ticket: '0987625432',
      regularPrice: '1500',
      earlyBird: '02-12-2019',
      dynamicPrice: 'Musafir2',
      insurance: 'NA',
      plantTree: 'NA'
    },
    {
      id: '8',
      trekName: 'Tarun2',
      trekDate: 'tarun2@plan.com',
      ticket: '0987625432',
      regularPrice: '1500',
      earlyBird: '02-12-2019',
      dynamicPrice: 'Musafir2',
      insurance: 'NA',
      plantTree: 'NA'
    },
    {
      id: '9',
      trekName: 'Tarun2',
      trekDate: 'tarun2@plan.com',
      ticket: '0987625432',
      regularPrice: '1500',
      earlyBird: '02-12-2019',
      dynamicPrice: 'Musafir2',
      insurance: 'NA',
      plantTree: 'NA'
    },
    {
      id: '10',
      trekName: 'Tarun2',
      trekDate: 'tarun2@plan.com',
      ticket: '0987625432',
      regularPrice: '1500',
      earlyBird: '02-12-2019',
      dynamicPrice: 'Musafir2',
      insurance: 'NA',
      plantTree: 'NA'
    },
    {
      id: '11',
      trekName: 'Tarun2',
      trekDate: 'tarun2@plan.com',
      ticket: '0987625432',
      regularPrice: '1500',
      earlyBird: '02-12-2019',
      dynamicPrice: 'Musafir2',
      insurance: 'NA',
      plantTree: 'NA'
    }
  ];

  constructor() { }

  ngOnInit() {
  }

}
