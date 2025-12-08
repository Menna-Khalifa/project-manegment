<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Image\Manipulations;


class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasRoles, HasFactory, Notifiable, InteractsWithMedia;
    protected $guarded = [];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime'];

    public function scopeUser($query)
    {
        return $query->where('type', 'user');
    }

    public function scopeAdmin($query)
    {
        return $query->where('type', 'admin');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    protected static function booted()
    {
        static::deleting(function ($user) {
            $user->clearMediaCollection('avatars');
        });
    }
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatars')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('avatar')
                    ->fit(Manipulations::FIT_CROP, 400, 400) // Crop to 400x400
                    ->crop(Manipulations::CROP_TOP, 400, 400) // Crop from top
                    ->quality(80)
                    ->optimize();
            });
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function projectTeams()
    {
        return $this->hasMany(ProjectTeam::class, 'user_id');
    }

    public function activeProjectTeams()
    {
        return $this->hasMany(ProjectTeam::class, 'user_id')
            ->whereHas('project', function ($q) {
                $q->where('status', '!=', 'completed');
            });
    }

    public function projectAmers()
{
    return $this->hasMany(ProjectAmer::class);
}

public function createdInvoices()
{
    return $this->hasMany(InvoiceAmer::class, 'created_by');
}

public function approvedInvoices()
{
    return $this->hasMany(InvoiceAmer::class, 'approved_by');
}
}
