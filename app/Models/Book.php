<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['titulo', 'descricao', 'data_publicacao', 'author_id'];

    protected $casts = [
        'data_publicacao' => 'date',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
