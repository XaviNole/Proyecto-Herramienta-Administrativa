import { Component } from '@angular/core';
import { AppointmentService } from '../service/appointment.service';

@Component({
  selector: 'app-add-appointments',
  templateUrl: './add-appointments.component.html',
  styleUrls: ['./add-appointments.component.scss']
})
export class AddAppointmentsComponent {




  hours:any = [];
  specialities:any = [];
  date_appointment:any;
  hour:any;
  specialitie_id:any;

  name:string='';
  surname:string='';
  mobile:string='';
  id_patient:string='';

  amount:number=0;
  amount_add:number=0;
  method_payment:string='';

  DOCTORS:any=[];
  DOCTOR_SELECTED:any;
  selected_segment_hour:any;

  public text_success:string='';
  public text_validation:string='';
  constructor(
    public appointmentService: AppointmentService
  ){

  }

  ngOnInit():void{


    this.appointmentService.listConfig().subscribe((resp:any)=>{
      this.hours = resp.hours
      this.specialities = resp.specialities;
    })
  }
  save(){
    this.text_validation = "";
    if(this.amount < this.amount_add){
      this.text_validation="EL MONTO INGRESADO COMO ADELANTO NO PUEDE SER MAYOR A LA CITA MÉDICA";
      return;
    }
    
    if(!this.name||!this.surname||!this.mobile||!this.id_patient||!this.date_appointment||!this.specialitie_id||!this.selected_segment_hour||!this.amount||!this.amount_add||!this.method_payment){
      this.text_validation="ES NECESARIO RELLENAR LOS CAMPOS (HORA, FECHA,PACIENTE Y PAGO)";
      return;
    }


    let data ={
      "doctor_id":this.DOCTOR_SELECTED.doctor.id,
      // "patient_id",
      name: this.name,
      surname: this.surname,
      mobile: this.mobile,
      id_patient: this.id_patient,
      "date_appointment":this.date_appointment,
      "specialitie_id":this.specialitie_id,
      "doctor_schedule_join_hour_id":this.selected_segment_hour.id,
      amount:this.amount,
      amount_add:this.amount_add,
      method_payment:this.method_payment,

    }

    this.appointmentService.registerAppointment(data).subscribe((resp:any)=>{
      console.log(resp);

      this.text_success = "LA CITA SE HA REGISTRADO CON ÉXITO";
    });
  }

  filtro(){
    let data = {
      date_appointment:this.date_appointment,
      hour:this.hour,
      specialitie_id:this.specialitie_id,
    }
    this.appointmentService.listFilter(data).subscribe((resp:any)=>{
      console.log(resp);
      this.DOCTORS = resp.doctors
    })
  }

  countDisponibilidad(DOCTOR:any){
    let SEGMENTS = [];
    SEGMENTS = DOCTOR.segments.filter((item:any)=> !item.is_appointment);
    return SEGMENTS.length
  }

  showSegment(DOCTOR:any){
    this.DOCTOR_SELECTED= DOCTOR;
  }

  selectSegment(SEGMENT:any){
    this.selected_segment_hour= SEGMENT;
  }

  filterPatient(){
    this.appointmentService.listPatient(this.id_patient+"").subscribe((resp:any) => {
      console.log(resp);
      if(resp.message==403){
        this.name = '';
        this.surname = '';
        this.mobile = '';
        this.id_patient='';
      }else{
        this.name = resp.name;
        this.surname = resp.surname;
        this.mobile = resp.mobile;
        this.id_patient = resp.id_patient;
      }
    })
  }

  resetPatient(){
    this.name = '';
    this.surname = '';
    this.mobile = '';
    this.id_patient='';
  }
}
