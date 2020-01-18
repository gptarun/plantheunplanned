import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddimportComponent } from './addimport.component';

describe('AddimportComponent', () => {
  let component: AddimportComponent;
  let fixture: ComponentFixture<AddimportComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddimportComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddimportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
