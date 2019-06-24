<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;

class Contributor extends Model
{

    protected $guarded = [];

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function emitter()
    {
        return $this->hasOne(Emitter::class);
    }
}
