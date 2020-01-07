import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';

@Component({
  selector: 'app-settings',
  templateUrl: './settings.component.html',
  styleUrls: ['./settings.component.scss']
})
export class SettingsComponent implements OnInit {
  newpass;
  retypepass;
  hide = true;
  reHide = true;
  constructor() { }

  changepass: FormGroup = new FormGroup({
    pass: new FormControl('', [Validators.required, Validators.required]),
    newpass: new FormControl('', [Validators.required, Validators.required])
  });

  get passwordInput() { return this.changepass.get('pass'); }
  get repasswordInput() { return this.changepass.get('newpass'); }

  ngOnInit() {
  }

  onSubmit() {
    console.log(this.passwordInput.value);
    console.log(this.repasswordInput.value);

  }
}