<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_FINANCE_ADMIN = 'finance_admin';
    public const ROLE_REGISTRATION_ADMIN = 'registration_admin';
    public const ROLE_ACCREDITATION_OFFICER = 'accreditation_officer';
    public const ROLE_SUPPORT_AGENT = 'support_agent';
    public const ROLE_REGISTRANT = 'registrant';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasRole(string ...$roles): bool
    {
        if ($this->role === self::ROLE_SUPER_ADMIN) {
            return true;
        }

        if (in_array($this->role, $roles, true)) {
            return true;
        }

        $assignedRoleSlugs = [];
        if (Schema::hasTable('roles') && Schema::hasTable('role_user')) {
            $assignedRoleSlugs = $this->roles()->pluck('slug')->all();
        }
        foreach ($roles as $role) {
            if (in_array($role, $assignedRoleSlugs, true)) {
                return true;
            }

            if ($this->canActAsRole($role)) {
                return true;
            }
        }

        return false;
    }

    public function hasPermission(string ...$permissions): bool
    {
        if ($this->role === self::ROLE_SUPER_ADMIN) {
            return true;
        }

        if (Schema::hasTable('roles') && Schema::hasTable('role_user')
            && $this->roles()->where('slug', self::ROLE_SUPER_ADMIN)->exists()) {
            return true;
        }

        if (empty($permissions)) {
            return true;
        }

        $assigned = $this->permissionSlugs();
        foreach ($permissions as $permission) {
            if (! $assigned->contains($permission)) {
                return false;
            }
        }

        return true;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function permissionSlugs(): Collection
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('permissions')
            || ! Schema::hasTable('role_user') || ! Schema::hasTable('permission_role')) {
            return collect();
        }

        return $this->roles()
            ->with('permissions:id,slug')
            ->get()
            ->flatMap(fn (Role $role) => $role->permissions->pluck('slug'))
            ->unique()
            ->values();
    }

    protected function canActAsRole(string $role): bool
    {
        $required = static::rolePermissionMap()[$role] ?? null;
        if (! is_array($required) || empty($required)) {
            return false;
        }

        return $this->hasPermission(...$required);
    }

    public static function rolePermissionMap(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => ['users.manage'],
            self::ROLE_REGISTRATION_ADMIN => ['registrations.manage'],
            self::ROLE_FINANCE_ADMIN => ['finance.manage'],
            self::ROLE_ACCREDITATION_OFFICER => ['accreditation.manage'],
            self::ROLE_SUPPORT_AGENT => ['registrations.manage'],
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the user's registrations
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the profile photo URL
     */
    public function profilePhotoUrl(): ?string
    {
        if (!$this->profile_photo) {
            return null;
        }

        if (filter_var($this->profile_photo, FILTER_VALIDATE_URL)) {
            return $this->profile_photo;
        }

        return asset('storage/' . $this->profile_photo);
    }
}
