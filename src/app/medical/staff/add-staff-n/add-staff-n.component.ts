import { Component } from '@angular/core';
import { StaffService } from '../service/staff.service';

@Component({
  selector: 'app-add-staff-n',
  templateUrl: './add-staff-n.component.html',
  styleUrls: ['./add-staff-n.component.scss']
})
export class AddStaffNComponent {

  public selectedValue !: string  ;
  public name:string='';
  public surname:string='';
  public mobile:string='';
  public email:string='';
  public password:string='';
  public password_confirmation:string='';

  public birth_date:string='';
  public gender:number=1;
  public education:string='';
  public designation:string='';
  public address:string='';

  public roles:any =[];

  public FILE_AVATAR:any;
  public IMAGEN_PREVISUALIZA:any='assets/img/user-06.jpg';

  public text_success:string='';
  public text_validation:string='';

  constructor(
    public staffService: StaffService,
  ){

  }
  ngOnInit():void{
    

    this.staffService.listConfig().subscribe((resp:any)=>{
      console.log(resp);
      this.roles=resp.roles;
    })
  }

  save(){
    this.text_validation='';
    if(!this.name || !this.email || !this.surname || !this.password){
      this.text_validation="ES NECESARIO RELLENAR LOS CAMPOS (NOMBRE,APELLIDO,EMAIL,CONTRASEÑA)";
      return;
    }
    
    if(this.password != this.password_confirmation){
      this.text_validation="LAS CONTRASEÑAS DEBEN SER IGUALES";
      return;
    }
    console.log(this.selectedValue);

    let formData=new FormData();
    formData.append("name",this.name);
    formData.append("surname",this.surname);
    formData.append("email",this.email);
    formData.append("mobile",this.mobile);
    formData.append('birth_date', this.birth_date);
    formData.append("gender",this.gender+"");
    formData.append("education",this.education);
    formData.append("designation",this.designation);
    formData.append("address",this.address);
    formData.append("password",this.password);
    formData.append("role_id",this.selectedValue);
    formData.append("imagen",this.FILE_AVATAR);

    this.staffService.registerUser(formData).subscribe((resp:any)=>{
      console.log(resp);
      if(resp.message==403){
        this.text_validation=resp.message_text;
      }else{
        this.text_success = 'El usuario ha sido registrado correctamente';

        this.name = '';
        this.surname = '';
        this.email = '';
        this.mobile = '';
        this.birth_date = '';
        this.gender = 1;
        this.education = '';
        this.designation = '';
        this.address = '';
        this.password_confirmation = '';
        this.selectedValue = '';
        this.FILE_AVATAR = null;
        this.IMAGEN_PREVISUALIZA = null;
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
