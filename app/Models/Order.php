<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'total', 
        'status', 
        'payment_provider', 
        'payment_reference', 
        'shipping_address', 
        'public_token', 
        'tracking_number', 
        'customer_email', 
        'customer_phone',
        'order_number' // AJOUT
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'public_token_expires_at' => 'datetime',
        'total' => 'decimal:2' // AJOUT
    ];

    // BOOT METHOD POUR GÉNÉRATION AUTOMATIQUE - AJOUT
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // Générer le numéro de commande automatiquement
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber();
            }
            // Par défaut, utiliser le numéro de commande comme numéro de suivi
            if (empty($order->tracking_number)) {
                $order->tracking_number = $order->order_number;
            }
        });

        // Plus besoin de régénérer un tracking_number aléatoire au changement de statut :
        // il reste aligné avec le numéro de commande, sauf modification manuelle en admin.
    }

    // MÉTHODE POUR GÉNÉRER LE NUMÉRO DE COMMANDE - AJOUT
    public static function generateOrderNumber()
    {
        $prefix = 'CMD';
        $date = now()->format('Ymd');
        
        // Trouver la dernière commande du jour
        $lastOrder = static::where('order_number', 'like', $prefix . $date . '%')
            ->orderBy('id', 'desc')
            ->first();

        // Incrémenter la séquence
        $sequence = $lastOrder ? (int)substr($lastOrder->order_number, -4) + 1 : 1;

        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    // MÉTHODE POUR GÉNÉRER LE NUMÉRO DE SUIVI - AJOUT
    public static function generateTrackingNumber()
    {
        $prefix = 'SAKHA';
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $random = '';

        for ($i = 0; $i < 8; $i++) {
            $random .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $prefix . $random;
    }

    // MÉTHODES UTILITAIRES - AJOUT
    public function getStatusText()
    {
        $statuses = [
            'received' => 'Reçue',
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'processing' => 'En traitement',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée'
        ];

        return $statuses[$this->status] ?? 'Inconnu';
    }

    public function getStatusColor()
    {
        $colors = [
            'received' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'processing' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            'shipped' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
            'delivered' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
        ];

        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    }

    public function getStatusIcon()
    {
        $icons = [
            'received' => '📥',
            'pending' => '⏳',
            'confirmed' => '✅',
            'processing' => '🔄',
            'shipped' => '🚚',
            'delivered' => '📦',
            'cancelled' => '❌'
        ];

        return $icons[$this->status] ?? '❓';
    }

    public function getFormattedTotal()
    {
        return number_format($this->total, 2, ',', ' ') . ' fcfa';
    }

    public function hasTracking()
    {
        return !empty($this->tracking_number);
    }

    public function canBeTracked()
    {
        return in_array($this->status, ['shipped', 'delivered']) && $this->hasTracking();
    }

    public function isCompleted()
    {
        return in_array($this->status, ['delivered', 'cancelled']);
    }

    // SCOPES UTILES - AJOUT
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // RELATIONS EXISTANTES
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    // ACCESSORS - AJOUT
    public function getDisplayNumberAttribute()
    {
        return $this->order_number ?? 'CMD-' . $this->id;
    }

    public function getIsPaidAttribute()
    {
        return $this->payments()->where('status', 'completed')->exists();
    }

    public function getItemsCountAttribute()
    {
        return $this->items->count();
    }
}