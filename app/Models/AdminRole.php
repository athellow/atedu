<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'display_name', 'slug', 'description',
    ];

    protected $appends = [
        'permission_ids',
    ];

    public function getPermissionIdsAttribute()
    {
        return $this->permissions()->select(['id'])->get()->pluck('id')->toArray();
    }

    /**
     * 角色下的管理员.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function admins()
    {
        return $this->belongsToMany(
            Admin::class,
            AdminRoleRelation::class,
            'role_id',
            'admin_id'
        );
    }

    /**
     * 角色下的权限.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(
            AdminPermission::class,
            AdminRolePermissionRelation::class,
            'role_id',
            'permission_id'
        );
    }

    /**
     * 判断角色是否有权限
     * 
     * @param AdminPermission $permission
     *
     * @return bool
     */
    public function hasPermission(AdminPermission $permission)
    {
        return $this->permissions()->where('id', $permission->id)->exists();
    }

    /**
     * 当前角色是否属于某个用户.
     *
     * @param Admin $admin
     *
     * @return mixed
     */
    public function hasAdmin(Admin $admin)
    {
        return $this->admins()->whereId($admin->id)->exists();
    }

}
