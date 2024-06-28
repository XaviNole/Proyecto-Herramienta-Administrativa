import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { URL_SERVICIOS } from 'src/app/config/config';
import { AuthService } from 'src/app/shared/auth/auth.service';

@Injectable({
  providedIn: 'root'
})
export class DoctorService {

  constructor(
    public http: HttpClient,
    public authService: AuthService,
  ) { }

  listDoctors(){
    let headers = new HttpHeaders({'Authorization':'Bearer '+this.authService.token});
    let URL = URL_SERVICIOS+"/doctors";
    return this.http.get(URL,{headers: headers});
  }

  listConfig(){
    let headers = new HttpHeaders({'Authorization':'Bearer '+this.authService.token});
    let URL = URL_SERVICIOS+"/doctors/config";
    return this.http.get(URL,{headers: headers});
  }

  registerDoctors(data:any){
    let headers = new HttpHeaders({'Authorization':'Bearer '+this.authService.token});
    let URL = URL_SERVICIOS+"/doctors";
    return this.http.post(URL,data,{headers: headers});
  }

  showDoctors(doctor_id:string){
    let headers = new HttpHeaders({'Authorization':'Bearer '+this.authService.token});
    let URL = URL_SERVICIOS+"/doctors/"+doctor_id;
    return this.http.get(URL,{headers: headers});
  }
  
  updateDoctors(doctor_id:string, data:any){
    let headers = new HttpHeaders({'Authorization':'Bearer '+this.authService.token});
    let URL = URL_SERVICIOS+"/doctors/"+doctor_id;
    return this.http.post(URL,data,{headers: headers}); //usamos post en lugar de put porque la imagen no se podría guardar si ponemos put
  }

  deleteDoctors(doctor_id:string){
    let headers = new HttpHeaders({'Authorization':'Bearer '+this.authService.token});
    let URL = URL_SERVICIOS+"/doctors/"+doctor_id;
    return this.http.delete(URL,{headers: headers});
  }
}
