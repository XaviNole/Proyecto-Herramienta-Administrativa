import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { MedicalRoutingModule } from './medical-routing.module';
import { MedicalComponent } from './medical.component';
import { SharedModule } from 'src/app/shared/shared.module';
import { AddPatientMComponent } from './patient-m/add-patient-m/add-patient-m.component';
import { EditPatientMComponent } from './patient-m/edit-patient-m/edit-patient-m.component';
import { ListPatientMComponent } from './patient-m/list-patient-m/list-patient-m.component';
// import { HeaderComponent } from 'src/app/common-component/header/header.component';
// import { SidebarComponent } from 'src/app/common-component/sidebar/sidebar.component';


@NgModule({
  declarations: [
    MedicalComponent,
    AddPatientMComponent,
    EditPatientMComponent,
    ListPatientMComponent,
    // HeaderComponent,
    // SidebarComponent,
  ],
  imports: [
    CommonModule,
    MedicalRoutingModule,
    SharedModule,
  ]
})
export class MedicalModule { }

