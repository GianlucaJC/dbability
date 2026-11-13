<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public const STORE_SLUG = 'percorsi-pregrafismo-creativo';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price_cents',
        'print_price_cents',
        'file_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getPriceAttribute(): string
    {
        return number_format($this->price_cents / 100, 2, ',', '.');
    }

    public function getPrintPriceAttribute(): string
    {
        return number_format($this->print_price_cents / 100, 2, ',', '.');
    }
}
