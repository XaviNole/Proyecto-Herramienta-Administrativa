<?php

namespace App\Http\Resources\Patient;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->resource->id,
            "name" => $this->resource->name,
            "surname" => $this->resource->surname,
            "email" => $this->resource->email,
            "mobile" => $this->resource->mobile,
            "birth_date" => $this->resource->birth_date ? Carbon::parse($this->resource->birth_date)->format("Y/m/d") : NULL,
            "gender" => $this->resource->gender,
            "age" => $this->resource->age,
            "id_patient" => $this->resource->id_patient,
            "legal_rep" => $this->resource->legal_rep,
            "address" => $this->resource->address,
            "created_at" => $this->resource->created_at->format("Y-m-d h:i A"),
        ];
    }
}
