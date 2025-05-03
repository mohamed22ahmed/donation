<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'message' => $this->message,
            'read' => (bool)$this->read,
            'time_ago' => $this->timeAgo($this->created_at),
        ];
    }

    private function timeAgo($timestamp) {
        $time = Carbon::parse($timestamp);
        return $time->isFuture()
            ? $time->subSeconds(1)->diffForHumans() // Force past tense
            : $time->diffForHumans();
    }
}
