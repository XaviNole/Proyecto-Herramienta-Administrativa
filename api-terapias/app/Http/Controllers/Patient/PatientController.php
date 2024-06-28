<?php

namespace App\Http\Controllers\Patient;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Patient\PatientCollection;
use App\Http\Resources\Patient\PatientResource;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $patients = Patient::where(DB::raw("CONCAT(patients.name,' ',IFNULL(patients.surname,''),' ',IFNULL(patients.email,''))"),"like","%".$search."%")
                        ->orderBy("id","desc")
                        ->paginate(20);

        return response()->json([
            "total" => $patients->total(),
            "patients" => PatientCollection::make($patients),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          
        $patient_is_valid= Patient::where("id_patient",$request->id_patient)->first();

        if($patient_is_valid){
            return response()->json([
                "message"=>403,
                "message_text"=>"El paciente ya existe"
            ]);
        }

        if($request->birth_date){
            $date_clean = preg_replace('/\(.*\)|[A-Z]{3}-\d{4}/', '', $request->birth_date);
        }

        $request->request->add(["birth_date" => Carbon::parse($date_clean)->format("Y-m-d h:i:s")]);

        $patient = Patient::create($request->all());

        return response()->json([
            "message"=>200
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patient=Patient::findOrFail($id);

        return response()->json([
            "patient" => PatientResource::make($patient), 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $patient_is_valid= Patient::where("id","<>",$id)-> where("id_patient",$request->id_patient)->first();

        if($patient_is_valid){
            return response()->json([
                "message"=>403,
                "message_text"=>"El paciente ya existe"
            ]);
        }

        $patient = Patient::findOrFail($id);

        $date_clean = preg_replace('/\(.*\)|[A-Z]{3}-\d{4}/', '', $request->birth_date);
        $request->request->add(["birth_date" => Carbon::parse($date_clean)->format("Y-m-d h:i:s")]);


        $patient->update($request->all());
        
        return response()->json([
            "message"=>200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patient=Patient::findOrFail($id);
        $patient->delete();
        return response()->json([
            "message"=>200
        ]);
    }
}
