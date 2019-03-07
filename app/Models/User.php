<?php

namespace Application\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'users';

    protected $fillable = [
        'username', 'email', 'password', 'image', 'ip', 'first_name', 'last_name', 'role_id'
    ];

    public function fullname()
    {
        return '@' . $this->username;
    }

    public function role()
    {
        return $this->belongsTo('Application\Models\Role');
    }

}