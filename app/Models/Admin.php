<?php

namespace App\Models;

use App\Atedu\HasProfilePhoto;
use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_ip',
        'last_login_date', 
        'status', 
        'login_times',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'role_id', 
        'is_super',
        'profile_photo_url',
    ];

    public function getIsSuperAttribute()
    {
        return $this->isSuper();
    }

    public function getRoleIdAttribute()
    {
        $roles = $this->roles()->select(['id'])->get()->pluck('id');
        return $roles;
    }

    /**
     * 管理员包含的角色.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(
            AdminRole::class,
            AdminRoleRelation::class,
            'admin_id',
            'role_id'
        );
    }

    public function permissions()
    {
        $permissions = [];

        $roles = $this->roles;

        if($roles->isEmpty()) return $permissions;
        
        // 超管返回所有的权限
        if($this->isSuper()) {
            $permissions = AdminPermission::query()->select(['slug'])->get()->pluck('slug')->toArray();
        }else {
            foreach($roles as $role) {
                $tmp = $role->permissions()->select(['slug'])->get()->pluck('slug')->toArray();
                $permissions = array_merge($permissions, $tmp);
            }
        }

        return array_flip($permissions);
    }

    /**
     * 是否为超级管理员.
     *
     * @return bool
     */
    public function isSuper()
    {
        return $this->roles()->whereSlug(config('atedu.admin.super_slug'))->exists();
    }
    
    /**
     * 是否存在指定角色.
     *
     * @param AdminRole $role
     *
     * @return bool
     */
    public function hasRole(AdminRole $role)
    {
        return $this->roles()->where('id', $role->id)->exists();
    }

    /**
     * @param $path
     * @param $method
     * @return bool
     */
    public function hasPermission($path, $method)
    {
        $access = false;

        $roles = $this->roles;

        foreach ($roles as $role) {
            // http method
            $permissions = $role->permissions()->where('method', 'like', "%{$method}%")->get();
            if ($permissions->isEmpty()) {
                continue;
            }
            // url
            foreach ($permissions as $permission) {
                if (preg_match("#{$permission->url}$#i", $path) === 1) {
                    $access = true;
                    break;
                }
            }
        }

        return $access;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }
}
