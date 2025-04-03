<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['title','name','guard_name'];


    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    }

    public function givePermissionTo($permissions)
    {
        if (!is_array($permissions))
        {
            $permissions = [$permissions->id];
        } 
        else 
        {
            $permissions = collect($permissions)->pluck('id')->toArray();
        }
        
        $this->permissions()->syncWithoutDetaching($permissions);
    }

    public function hasPermission($permission)
    {
        
        return $this->permissions()->where('name', $permission)->exists();
    }

    public function roleuser()
    {
        return $this->hasMany(Roleuser::class);
    }
}
