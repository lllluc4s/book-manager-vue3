<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['nome', 'estado'];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function scopeActive($query)
    {
        return $query->where('estado', true);
    }
}
