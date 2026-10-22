<?php

namespace App\Modules\Support\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'message',
        'reply',
        'replied_by',
        'validated_by',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'user_id'      => 'integer',
            'order_id'     => 'integer',
            'replied_by'   => 'integer',
            'validated_by' => 'integer',
        ];
    }

    public function recordReply(int $employeeId, string $reply): void
    {
        $this->attributes['replied_by'] = $employeeId;
        $this->attributes['reply'] = $reply;
        $this->attributes['status'] = 'repondue';
    }

    public function validateResolution(int $managerId): void
    {
        $this->attributes['validated_by'] = $managerId;
        $this->attributes['status'] = 'validee';
    }
}
