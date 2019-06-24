<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;

class Emitter extends Model
{
    protected $fillable = [
        'certificate_file_name', 'certificate_password', 'contributor_id', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contributor()
    {
        return $this->belongsTo(Contributor::class);
    }
}
