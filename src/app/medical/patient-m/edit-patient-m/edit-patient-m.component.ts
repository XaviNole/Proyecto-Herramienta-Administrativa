import { Component } from '@angular/core';
import { PatientMService } from '../service/patient-m.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-edit-patient-m',
  templateUrl: './edit-patient-m.component.html',
  styleUrls: ['./edit-patient-m.component.scss']
})
export class EditPatientMComponent {
  public selectedValue !: string  ;
  public name:string='';
  public surname:string='';
  public email:string='';
  public mobile:string='';
  public id_patient:string='';
  public gender:number=1;
  public birth_date:string='';
  public address:string='';
  public legal_rep:string='';
  public age:string='';


  public roles:any =[];

  public FILE_AVATAR:any;
  public IMAGEN_PREVISUALIZA:any='assets/img/user-06.jpg';

  public text_success:string='';
  public text_validation:string='';

  public patient_id:any;
  constructor(
    public patientService: PatientMService,
    public activedRoute: ActivatedRoute,
  ){

  }
  ngOnInit():void{
    

    // this.patientService.listConfig().subscribe((resp:any)=>{
    //   console.log(resp);
    //   this.roles=resp.roles;
    // })
    this.activedRoute.params.subscribe((resp:any)=>{
      this.patient_id = resp.id;
    })
    this.patientService.showPatient(this.patient_id).subscribe((resp:any)=>{
      console.log(resp);
      
      this.name = resp.patient.name;
      this.surname = resp.patient.surname;
      this.email = resp.patient.email;
      this.mobile = resp.patient.mobile;
      this.birth_date = resp.patient.birth_date ? new Date(resp.patient.birth_date).toISOString() : '';
      this.gender = resp.patient.gender;
      this.age = resp.patient.age;
      this.id_patient = resp.patient.id_patient;
      this.legal_rep = resp.patient.legal_rep;
      this.address = resp.patient.address;
    })
  }

  save(){
    this.text_validation='';
    if(!this.name || !this.surname || !this.id_patient){
      this.text_validation="ES NECESARIO RELLENAR LOS CAMPOS (NOMBRE,APELLIDO Y DNI)";
      return;
    }

    console.log(this.selectedValue);

    let formData=new FormData();
    formData.append("name",this.name);
    formData.append("surname",this.surname);
    if(this.email){
      formData.append("email",this.email);
    }
    formData.append("mobile",this.mobile);
    if(this.birth_date){
      formData.append('birth_date', this.birth_date);
    }
    if(this.gender){
      formData.append("gender",this.gender+"");
    }
    formData.append("age",this.age);
    formData.append("id_patient",this.id_patient);
    formData.append("legal_rep",this.legal_rep);
    if(this.address){
      formData.append("address",this.address);
    }
    this.patientService.updatePatient(this.patient_id,formData).subscribe((resp:any)=>{
      console.log(resp);
      if(resp.message==403){
        this.text_validation=resp.message_text;
      }else{
        this.text_success = 'El paciente ha sido editado correctamente';
      }
    })
  }
  
  loadFile($event:any){
    if($event.target.files[0].type.indexOf("image")< 0){
      // alert("SOLO SE PUEDEN INGRESAR IMÁGENES");
      this.text_validation="SOLO SE PUEDEN INGRESAR IMÁGENES";
      return;
    }
    this.text_validation='';
    this.FILE_AVATAR=$event.target.files[0];
    let reader = new FileReader();
    reader.readAsDataURL(this.FILE_AVATAR);
    reader.onloadend=() => this.IMAGEN_PREVISUALIZA=reader.result;
  }
}
