import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { AdminLayoutRoutes } from './admin-layout.routing';
import { DashboardComponent } from '../../dashboard/dashboard.component';
import { UserManagementComponent, DeleteDialog } from 'app/user-management/user-management.component';
import { UserProfileComponent } from '../../user-profile/user-profile.component';
import { TableListComponent } from '../../table-list/table-list.component';
import { TypographyComponent } from '../../typography/typography.component';
import { IconsComponent } from '../../icons/icons.component';
import { MapsComponent } from '../../maps/maps.component';
import { NotificationsComponent } from '../../notifications/notifications.component';
import { EmailManagementComponent } from '../../email-management/email-management.component';
import { TrekManagementComponent } from '../../trek-management/trek-management.component';
import { MembershipManagementComponent } from '../../membership-management/membership-management.component';
import { SettingsComponent } from '../../settings/settings.component';
import { AdduserComponent } from '../../user-management/adduser/adduser.component';
import { EdituserComponent } from '../../user-management/edituser/edituser.component';

import {
  MatButtonModule,
  MatInputModule,
  MatRippleModule,
  MatFormFieldModule,
  MatTooltipModule,
  MatSelectModule,
  MatDatepickerModule,
  MatNativeDateModule,
  MatPaginatorModule,
  MatAutocompleteModule,
  MatDialogModule,
  MatIconModule
} from '@angular/material';
import { EditTrekComponent } from 'app/trek-management/edit-trek/edit-trek.component';

@NgModule({
  imports: [
    CommonModule,
    RouterModule.forChild(AdminLayoutRoutes),
    FormsModule,
    ReactiveFormsModule,
    MatButtonModule,
    MatRippleModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatTooltipModule,
    MatDatepickerModule,
    MatNativeDateModule,
    MatPaginatorModule,
    MatAutocompleteModule,
    MatDialogModule,
    MatIconModule
  ],
  providers: [
    MatDatepickerModule,
  ],
  declarations: [
    DashboardComponent,
    UserManagementComponent,
    UserProfileComponent,
    TableListComponent,
    TypographyComponent,
    IconsComponent,
    MapsComponent,
    NotificationsComponent,
    EmailManagementComponent,
    TrekManagementComponent,
    MembershipManagementComponent,
    SettingsComponent,
    AdduserComponent,
    EdituserComponent,
    DeleteDialog,
    EditTrekComponent
  ],
  entryComponents: [DeleteDialog],
})

export class AdminLayoutModule { }
