<?php

namespace App\Http\Resources;

use App\Http\Controllers\DateController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyPrizeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                 'id' => $this->id,
                 'value' => $this->value,
                 'possibility' => $this->possibility,
                 'updated_at' => explode(' ',(new DateController)->toPersian($this->updated_at))[0].' '.explode(' ',(new DateController)->toPersian($this->updated_at))[1]

        ];
    }
}
