<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['nume_ro', 'nume_ru', 'unitate', 'category_id', 'activ'];

    protected function nume(): Attribute
    {
        return Attribute::get(fn () => $this->{'nume_'.app()->getLocale()} ?? $this->nume_ro);
    }

    protected $casts = [
        'activ' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
