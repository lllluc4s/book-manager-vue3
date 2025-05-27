<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'descricao', 'data_publicacao', 'author_id', 'capa'];

    protected $casts = [
        'data_publicacao' => 'date',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
