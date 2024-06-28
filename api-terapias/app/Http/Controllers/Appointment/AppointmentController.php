<?php

namespace App\Http\Controllers\Appointment;

use auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Patient\Patient;
use App\Models\Doctor\Specialitie;
use Illuminate\Support\Facades\DB;
use Psy\CodeCleaner\ReturnTypePass;
use App\Http\Controllers\Controller;
use App\Models\Appointment\Appointment;
use App\Models\Doctor\DoctorScheduleDay;
use App\Models\Appointment\AppointmentPay;
use App\Models\Doctor\DoctorScheduleJoinHour;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;
use App\Http\Resources\Appointment\AppointmentResource;
use App\Http\Resources\Appointment\AppointmentCollection;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $specialitie_id = $request->specialitie_id;
        $name_doctor = $request->search;
        $date = $request->date;

        $appointments = Appointment::filterAdvance($specialitie_id,$name_doctor,$date)->orderBy("id","desc")
                        ->paginate(20);

        return response()->json([
            "total" => $appointments->total(),
            "appointments" => AppointmentCollection::make($appointments),
        ]);
    }

    public function config(){
        $hours = [
            [
                "id" => "08",
                "name" => "8:00 AM",
            ],
            [
                "id" => "09",
                "name" => "9:00 AM",
            ],
            [
                "id" => "10",
                "name" => "10:00 AM",
            ],
            [
                "id" => "11",
                "name" => "11:00 AM",
            ],
            [
                "id" => "12",
                "name" => "12:00 PM",
            ],
            [
                "id" => "13",
                "name" => "01:00 PM",
            ],
            [
                "id" => "14",
                "name" => "02:00 PM",
            ],
            [
                "id" => "15",
                "name" => "03:00 PM",
            ],
            [
                "id" => "16",
                "name" => "04:00 PM",
            ],
            [
                "id" => "17",
                "name" => "05:00 PM",
            ],
            [
                "id" => "18",
                "name" => "06:00 PM",
            ],
            [
                "id" => "19",
                "name" => "07:00 PM",
            ],
        ];
        $specialities = Specialitie:: where("state",1)->get();
        return response()->json([
            "specialities" => $specialities,
            "hours" => $hours,
        ]);
    }

    public function filter(Request $request){

        $date_appointment = $request->date_appointment;
        $hour = $request->hour;
        $specialitie_id = $request->specialitie_id;

        date_default_timezone_set('America/Lima');
        Carbon::setLocale('es');
        DB::statement("SET lc_time_names = 'es_ES'");
        
        $name_day=Carbon::parse($date_appointment)->dayName;
        $doctor_query = DoctorScheduleDay::where("day","like","%".$name_day."%")
                                            ->whereHas("doctor",function($q) use($specialitie_id){
                                                $q->where("specialitie_id",$specialitie_id);
                                            })->whereHas("schedules_hours",function($q) use($hour){
                                                $q->whereHas("doctor_schedule_hour",function($qs)use($hour){
                                                    $qs->where("hour",$hour);
                                                });
                                            })->get();
        $doctors = collect([]);
        foreach ($doctor_query as $doctor_q) {

            $segments= DoctorScheduleJoinHour::where("doctor_schedule_day_id",$doctor_q->id)
                                                ->whereHas("doctor_schedule_hour",function($q)use($hour){
                                                    $q->where("hour",$hour);
                                                })->get();
            
            $doctors->push([
                "doctor"=>[
                    "id"=> $doctor_q->doctor->id,
                    "full_name"=>$doctor_q->doctor->name .' '.$doctor_q->doctor->surname,
                    "specialitie"=> [
                        "id" => $doctor_q->doctor->specialitie->id,
                        "name"=>$doctor_q->doctor->specialitie->name,
                    ],
                ],
                "segments"=> $segments->map(function($segment) use($date_appointment){
                    $appointment = Appointment::where("doctor_schedule_join_hour_id",$segment->id)
                                                ->whereDate("date_appointment",Carbon::parse($date_appointment)->format("Y-m-d"))
                                                ->first();
                    return [
                        "id"=> $segment ->id,
                        "doctor_schedule_day_id" => $segment ->doctor_schedule_day_id,
                        "doctor_schedule_hour_id" => $segment ->doctor_schedule_hour_id,
                        "is_appointment"=> $appointment ? true : false,
                        "format_segment" => [
                            "id" => $segment -> doctor_schedule_hour -> id,   
                            "hour_start" => $segment -> doctor_schedule_hour -> hour_start,
                            "hour_end" => $segment -> doctor_schedule_hour -> hour_end,
                            "format_hour_start" => Carbon::parse(date("Y-m-d").' '. $segment -> doctor_schedule_hour -> hour_start)-> format("h:i A"),
                            "format_hour_end" => Carbon::parse(date("Y-m-d").' '. $segment -> doctor_schedule_hour -> hour_end)-> format("h:i A"),
                            "hour" => $segment -> doctor_schedule_hour -> hour,
                        ]
                    ];
                })
            ]);
        }
        // dd($doctors);

        return response()->json ([
            "doctors"=>$doctors,
        ]);
    }

    public function query_patient(Request $request){
        $id_patient = $request->get("id_patient");

        $patient = Patient::where("id_patient",$id_patient)->first();
        if(!$patient){
            return response()->json([
                "message" =>403
            ]);
        }
        return response()->json([
            "message" =>200,
            "name" => $patient->name,
            "surname" => $patient->surname,
            "mobile" => $patient->mobile,
            "id_patient" => $patient->id_patient,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // doctor_id
        // name
        // surname
        // mobile
        // id_patient
        // date_appointment
        // specialitie_id
        // doctor_schedule_join_hour_id
        // amount
        // amount_add
        // method_payment

        $patient=null;

        $patient = Patient::where("id_patient",$request->id_patient)->first();

        if(!$patient){
            $patient=Patient::create([
                "name" =>$request->name,
                "surname" =>$request->surname,
                "mobile" =>$request->mobile,
                "id_patient" =>$request->id_patient,
            ]);
        }else{

        }

        $appointment = Appointment::create([
            "doctor_id" => $request->doctor_id,
            "patient_id" => $patient->id,
            "date_appointment" => Carbon::parse($request->date_appointment)->format("Y-m-d h:i:s"),
            "specialitie_id" => $request->specialitie_id,
            "doctor_schedule_join_hour_id" => $request->doctor_schedule_join_hour_id,
            "user_id" => auth("api")->user()->id,
            "amount" => $request->amount,
            "status_pay" => $request->amount != $request->amount_add ? 2 : 1,
        ]);


        AppointmentPay::create([
            "appointment_id"=>$appointment->id,
            "amount" => $request->amount_add,
            "method_payment" => $request->method_payment,
        ]);

        return response()->json([
            "message"=>200,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $appointment = Appointment :: findOrFail($id);

        return response()->json([
            "appointment" => AppointmentResource::make($appointment)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $appointment = Appointment :: findOrFail($id);

        if($appointment->payments->sum("amount") <$request->amount){
            return response()->json([
                "message"=> 403,
                "message_text"=>"LOS PAGOS INGRESADOS SUPERAN EL NUEVO MONTO QUE QUIERE GUARDAR"
            ]);
        }

        $appointment -> update ([
            "doctor_id" => $request->doctor_id,
            "date_appointment"=>$request->date_appointment,
            "specialitie_id"=>$request->specialitie_id,
            "doctor_schedule_join_hour_id"=>$request->doctor_schedule_join_hour_id,
            "amount" => $request->amount,
            "status_pay" => $appointment->payments->sum("amount") != $request->amount ? 2 : 1,
        ]);

        return response()->json([
            "message"=>200,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment :: findOrFail($id);
        $appointment->delete();
        return response()->json([
            "message"=>200,
        ]);
    }
}
