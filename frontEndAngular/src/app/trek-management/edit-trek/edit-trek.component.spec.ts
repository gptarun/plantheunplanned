import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EditTrekComponent } from './edit-trek.component';

describe('EditTrekComponent', () => {
  let component: EditTrekComponent;
  let fixture: ComponentFixture<EditTrekComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EditTrekComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EditTrekComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
