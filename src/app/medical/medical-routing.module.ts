import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { MedicalComponent } from './medical.component';
import { AuthGuard } from 'src/app/shared/gaurd/auth.guard';


// ruta base: http://localhost:4200/roles/register
const routes: Routes = [
  {
    path:'',
    component: MedicalComponent,
    canActivate: [AuthGuard],
    children: [
      {
        path: 'roles',
        loadChildren: () =>
          import('./roles/roles.module').then((m) => m.RolesModule),
      },
      {
        path: 'staffs',
        loadChildren: () =>
          import('./staff/staff.module').then((m) => m.StaffModule),
      },
      {
        path: 'specialities',
        loadChildren: () =>
          import('./specialitie/specialitie.module').then((m) => m.SpecialitieModule),
      },
      {
        path: 'doctors',
        loadChildren: () =>
          import('./doctors/doctors.module').then((m) => m.DoctorsModule),
      },
      {
        path: 'patient-m',
        loadChildren: () =>
          import('./patient-m/patient-m.module').then((m) => m.PatientMModule),
      },
      {
        path: 'appointment-m',
        loadChildren: () =>
          import('./appointment/appointment.module').then((m) => m.AppointmentModule),
      },
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class MedicalRoutingModule { }
