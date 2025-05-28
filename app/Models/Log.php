<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Log extends Model
{
    protected $fillable = [
        'level',
        'message',
        'context',
        'channel',
    ];

    protected $casts = [
        'context'    => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope para buscar logs antigos (mais de X dias).
     *
     * @param mixed $query
     */
    public function scopeOldLogs($query, int $days = 30)
    {
        return $query->where('created_at', '<', Carbon::now()->subDays($days));
    }

    /**
     * Scope para buscar logs por nível.
     *
     * @param mixed $query
     */
    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope para buscar logs por canal.
     *
     * @param mixed $query
     */
    public function scopeByChannel($query, string $channel)
    {
        return $query->where('channel', $channel);
    }
}
