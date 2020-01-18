import { Routes } from '@angular/router';

import { DashboardComponent } from '../../dashboard/dashboard.component';
import { UserProfileComponent } from '../../user-profile/user-profile.component';
import { TableListComponent } from '../../table-list/table-list.component';
import { TypographyComponent } from '../../typography/typography.component';
import { IconsComponent } from '../../icons/icons.component';
import { MapsComponent } from '../../maps/maps.component';
import { NotificationsComponent } from '../../notifications/notifications.component';
import { UserManagementComponent } from 'app/user-management/user-management.component';
import { EmailManagementComponent } from 'app/email-management/email-management.component';
import { TrekManagementComponent } from 'app/trek-management/trek-management.component';
import { MembershipManagementComponent } from 'app/membership-management/membership-management.component';
import { SettingsComponent } from 'app/settings/settings.component';
import { AdduserComponent } from 'app/user-management/adduser/adduser.component';
import { EdituserComponent } from 'app/user-management/edituser/edituser.component';
import { EditTrekComponent } from 'app/trek-management/edit-trek/edit-trek.component';
import { ImportComponent } from 'app/import/import.component';

export const AdminLayoutRoutes: Routes = [

    { path: 'dashboard', component: DashboardComponent },
    { path: 'user', component: UserManagementComponent },
    { path: 'email', component: EmailManagementComponent },
    { path: 'trek', component: TrekManagementComponent },
    { path: 'member', component: MembershipManagementComponent },
    { path: 'settings', component: SettingsComponent },
    { path: 'adduser', component: AdduserComponent },
    { path: 'edituser', component: EdituserComponent },
    { path: 'edittrek', component: EditTrekComponent },
    { path: 'import', component: ImportComponent },

    { path: 'user-profile', component: UserProfileComponent },
    { path: 'table-list', component: TableListComponent },
    { path: 'typography', component: TypographyComponent },
    { path: 'icons', component: IconsComponent },
    { path: 'maps', component: MapsComponent },
    { path: 'notifications', component: NotificationsComponent },
];
