<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'xp',
        'is_profile_public',
        'streak_days',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
            
            'is_profile_public' => 'boolean',
        ];
    }


    //Long term relationships with users and personas
    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'user_persona')
                    ->withPivot('xp', 'level', 'date', 'is_unlocked', 'last_selected_at')
                    ->withTimestamps();
    }


    //History of persona selections by the user
    public function personaSelections()
    {
        return $this->hasMany(PersonaSelection::class);
    }


    //Todays selected persona
    public function selectedPersona()
    {
        // Get today’s selection from persona_selections
        $selection = $this->personaSelections()
                        ->whereDate('selected_at', today())
                        ->with('persona')
                        ->latest('selected_at')
                        ->first();

        
        return $selection ? $selection->persona : null;
    }

    public function userPersonas()
    {
        return $this->hasMany(UserPersona::class);
    }

    

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
                    ->withTimestamps()
                    ->withPivot('unlocked_at');
    }


}
