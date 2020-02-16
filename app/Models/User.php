<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'nID', 'verifyToken', "nID"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verifyToken',
    ];

    /**
     * Get the user's permissions and unserialize them.
     *
     * @return array
     */
    public function getPermissions() : array
    {
        if ($this->permissions === null)
            return [];
        else
            return \json_decode($this->permissions, true);
    }

    /**
     * Relation between the user and their accounts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany('App\Models\Accounts', 'nID', 'nID');
    }
}
