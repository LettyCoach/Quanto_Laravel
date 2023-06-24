<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'full_name',
        'shop_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->role_id == 1;
    }

    public function role()
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }
    /**
     * Send password reset email
     *
     * @param string $ token
     *@return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    /**
     * The roles that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function productes(): BelongsToMany
    {
        return $this->belongsToMany(UserProduct::class, 'save_items', 'user_id', 'product_id');
    }

    public function getQID()
    {
        return sprintf("Q%05d", $this->id);
    }

    public function getQID_New()
    {
        $id = DB::table('users')->max('id');

        return sprintf("Q%05d", $id + 1);
    }

    public function getSettingInfo($key)
    {
        return json_decode($this->settings, true)[$key] ?? "";
    }

    public function getCreatedDate()
    {
        return $this->created_at->format('Y-m-d');
    }
}