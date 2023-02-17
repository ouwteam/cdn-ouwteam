<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFile extends Model
{
    use HasFactory;

    /**
     *
     * @return Attribute
     */
    protected function slug(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => route("api.view_url", $this->uuid),
        );
    }

    /**
     *
     * @return Attribute
     */
    protected function filename(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => pathinfo(storage_path('app/users/' . $value), PATHINFO_BASENAME),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
