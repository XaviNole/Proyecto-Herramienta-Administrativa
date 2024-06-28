<?php

namespace App\Http\Controllers\Admin\Staff;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;

class StaffsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search=$request->search;

        $users = User::where(DB::raw("CONCAT(users.name,' ',IFNULL(users.surname,''),' ',users.email)"),"like","%".$search."%")
                        // "name","like","%".$search."%"
                        // ->orWhere("surname","like","%".$search."%")
                        // ->orWhere("email","like","%".$search."%")
                        ->orderBy("id","desc")
                        ->whereHas("roles",function($q){
                            $q->where("name","not like","%TERAPEUTA%");
                        })
                        ->get();

        return response()->json([
            "users"=>UserCollection::make($users), 
        ]);
    }

    public function config(){
        $roles = Role::where("name","not like","%TERAPEUTA%")->get();

        return response()->json([
            "roles" => $roles
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $users_is_valid= User::where("email",$request->email)->first();

        if($users_is_valid){
            return response()->json([
                "message"=>403,
                "message_text"=>"El usuario con ese email ya existe"
            ]);
        }

        if($request->hasFile("imagen")){
            $path=Storage::putFile("staffs",$request->file("imagen"));
            $request->request->add(["avatar"=>$path]);
        }

        if($request->password){
            $request->request->add(["password"=>bcrypt($request->password)]);
        }
        $date_clean = preg_replace('/\(.*\)|[A-Z]{3}-\d{4}/', '', $request->birth_date);
        $request->request->add(["birth_date" => Carbon::parse($date_clean)->format("Y-m-d h:i:s")]);
        $user = User::create($request->all());

        $role = Role::findOrFail($request->role_id);
        $user->assignRole($role);
        return response()->json([
            "message"=>200
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user=User::findOrFail($id);

        return response()->json([
            "user" => UserResource::make($user), 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $users_is_valid= User::where("id","<>",$id)-> where("email",$request->email)->first();

        if($users_is_valid){
            return response()->json([
                "message"=>403,
                "message_text"=>"El usuario con ese email ya existe"
            ]);
        }

        $user = User::findOrFail($id);
        
        if($request->hasFile("imagen")){
            if($user->avatar){
                Storage::delete($user->avatar);
            }
            $path=Storage::putFile("staffs",$request->file("imagen"));
            $request->request->add(["avatar"=>$path]);
        }

        if($request->password){
            $request->request->add(["password"=>bcrypt($request->password)]);
        }
        $date_clean = preg_replace('/\(.*\)|[A-Z]{3}-\d{4}/', '', $request->birth_date);
        $request->request->add(["birth_date" => Carbon::parse($date_clean)->format("Y-m-d h:i:s")]);
        $user->update($request->all());
        
        if($request->role_id != $user->roles()->first()->id){
            $role_old = Role::findOrFail($user->roles()->first()->id);
            $user->removeRole($role_old);
    
            $role_new = Role::findOrFail($request->role_id);
            $user->assignRole($role_new);
        }
        return response()->json([
            "message"=>200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user=User::findOrFail($id);
        if($user->avatar){
            Storage::delete($user->avatar);
        }
        $user->delete();
        return response()->json([
            "message"=>200
        ]);
    }
}
