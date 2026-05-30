<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['nume_ro', 'nume_ru', 'ordine_sortare'];

    protected function nume(): Attribute
    {
        return Attribute::get(fn () => $this->{'nume_'.app()->getLocale()} ?? $this->nume_ro);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
