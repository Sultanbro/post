<?php

namespace App\Models\Client;

use App\Models\Avatar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'parent_name',
        'short_name',
        'full_name',
        'iin',
        'sex',
        'resident_bool',
        'juridical_bool',
        'address',
        'type_id',
        'birthday',
        'updated_by',
        'created_by',
        'foreign_id',
    ];

    public function users()
    {
        return $this->hasMany(User::class,'client_id', 'id');
    }

    public function avatar()
    {
        return $this->hasOne(Avatar::class, 'client_id', 'id')->where('type',2);
    }



}
