<?php

namespace App\Http\Resources;

use App\Http\Controllers\DateController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $prizes = [];
        foreach ($this->prizes as $item) {
            $prizes[]=[
                'prize'=> $item->chance,
                'date' => explode(' ',(new DateController)->toPersian($item->created_at))[0].' '.explode(' ',(new DateController)->toPersian($item->created_at))[1]

            ];
        }
        return [
            'mobile' => $this->mobile,
            'prizes' => $prizes,
        ];
    }
}
