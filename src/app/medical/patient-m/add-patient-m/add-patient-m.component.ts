import { Component } from '@angular/core';
import { PatientMService } from '../service/patient-m.service';

@Component({
  selector: 'app-add-patient-m',
  templateUrl: './add-patient-m.component.html',
  styleUrls: ['./add-patient-m.component.scss']
})
export class AddPatientMComponent {

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

  constructor(
    public patientService: PatientMService,
  ){

  }
  ngOnInit():void{
    

    // this.patientService.listConfig().subscribe((resp:any)=>{
    //   console.log(resp);
    //   this.roles=resp.roles;
    // })
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
    this.patientService.registerPatient(formData).subscribe((resp:any)=>{
      console.log(resp);
      if(resp.message==403){
        this.text_validation=resp.message_text;
      }else{
        this.text_success = 'El paciente ha sido registrado correctamente';

        this.name = '';
        this.surname = '';
        this.email = '';
        this.mobile = '';
        this.birth_date = '';
        this.gender = 1;
        this.age='';
        this.id_patient = '';
        this.legal_rep = '';
        this.address = '';
        this.selectedValue = '';
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
