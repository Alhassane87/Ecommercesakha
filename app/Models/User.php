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
        'role',
        'default_address_id',
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
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function cart()
    {
        return $this->hasOne(\App\Models\Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    /**
     * Retourne le nombre d'articles dans le panier de l'utilisateur
     */
    public function cartItemsCount(): int
    {
        // Vérifie si l'utilisateur a un panier et retourne le nombre d'articles
        if ($this->cart && $this->cart->items) {
            return $this->cart->items->count();
        }
        
        return 0;
    }

    /**
     * Version alternative avec eager loading pour optimiser les requêtes
     */
    public function getCartItemsCountAttribute(): int
    {
        // Cette version peut être utilisée comme $user->cart_items_count
        if (!$this->relationLoaded('cart')) {
            $this->load('cart.items');
        }
        
        return $this->cart && $this->cart->items ? $this->cart->items->count() : 0;
    }
}