<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->profile()->create();

            if ($user->role === UserRole::Industry) {
                $user->company()->create([
                    'name' => $user->name . ' Company',
                ]);
            }
        });
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function challengeApplications(): HasMany
    {
        return $this->hasMany(ChallengeApplication::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->withPivot('level')
            ->withTimestamps();
    }

    public function badges(): HasMany
    {
        return $this->hasMany(Badge::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        $panelId = $panel->getId();

        // All authenticated users can access the portal panel to log in/redirect
        if ($panelId === 'portal') {
            return true;
        }

        // Admin can access everything
        if ($this->role === UserRole::Admin) {
            return true;
        }

        // Other roles can only access their specific panel
        return $this->role->value === $panelId;
    }

    /**
     * Role helper checks.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isStudent(): bool
    {
        return $this->role === UserRole::Student;
    }

    public function isIndustry(): bool
    {
        return $this->role === UserRole::Industry;
    }

    public function isResearcher(): bool
    {
        return $this->role === UserRole::Researcher;
    }

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
            'role' => UserRole::class,
        ];
    }
}
