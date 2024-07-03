<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Models\Client;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements JWTSubject, HasTenants {
    use Notifiable, HasRoles, HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'password'];
    protected $hidden = ['password', 'remember_token'];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    // public function organizations() {
    //     return $this->belongsToMany(Organization::class, 'relation_user_organization', 'user_id', 'organization_id');
    // }

    // public function organizations()
    // {
    //     return $this->hasMany(Organization::class);
    // }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class,);
    }
 
    public function getTenants(Panel $panel): Collection
    {
        return $this->organizations;
    }
 
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->organizations()->whereKey($tenant)->exists();
        //return true;
    }
    
}