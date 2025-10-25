<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'organization_type_id',
        'is_approved',
        'temp_password',
        'temp_password_expires_at',
        'must_change_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'temp_password',
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
            'temp_password' => 'hashed',
            'temp_password_expires_at' => 'datetime',
            'is_approved' => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }

    /**
     * Get the organization type for this user.
     */
    public function organizationType(): BelongsTo
    {
        return $this->belongsTo(OrganizationType::class);
    }

    /**
     * Get the audit logs for this user.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }


    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Check if user is district admin.
     */
    public function isDistrictAdmin(): bool
    {
        return $this->hasRole('district-admin');
    }

    /**
     * Check if user is data entry.
     */
    public function isDataEntry(): bool
    {
        return $this->hasRole('data-entry');
    }

    /**
     * Check if user is viewer.
     */
    public function isViewer(): bool
    {
        return $this->hasRole('viewer');
    }

    /**
     * Check if user is approved.
     */
    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    /**
     * Check if user has a valid temporary password.
     */
    public function hasValidTempPassword(): bool
    {
        return $this->temp_password && 
               $this->temp_password_expires_at && 
               $this->temp_password_expires_at->isFuture();
    }

    /**
     * Check if user must change password.
     */
    public function mustChangePassword(): bool
    {
        return $this->must_change_password || $this->hasValidTempPassword();
    }

    /**
     * Generate a temporary password.
     */
    public function generateTempPassword(): string
    {
        return strtoupper(substr(md5(uniqid()), 0, 8));
    }

    /**
     * Set temporary password with expiration.
     */
    public function setTempPassword(string $password, int $hours = 24): void
    {
        $this->update([
            'temp_password' => $password,
            'temp_password_expires_at' => now()->addHours($hours),
            'must_change_password' => true,
        ]);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\PasswordResetNotification($token));
    }

    /**
     * Clear temporary password.
     */
    public function clearTempPassword(): void
    {
        $this->update([
            'temp_password' => null,
            'temp_password_expires_at' => null,
            'must_change_password' => false,
        ]);
    }
}
