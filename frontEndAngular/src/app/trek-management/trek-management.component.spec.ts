import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TrekManagementComponent } from './trek-management.component';

describe('TrekManagementComponent', () => {
  let component: TrekManagementComponent;
  let fixture: ComponentFixture<TrekManagementComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TrekManagementComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TrekManagementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
