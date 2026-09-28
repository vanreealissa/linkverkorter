<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Link extends Model
{
    use HasFactory;

    /** Codes die botsen met bestaande routes van de app. */
    public const RESERVED_CODES = ['api', 'links', 'up', 'statistieken', 'css', 'favicon.ico', 'robots.txt'];

    /** Tekens zonder verwarrende lookalikes (geen 0/O, 1/l/I). */
    private const ALPHABET = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    protected $fillable = ['code', 'url'];

    protected $attributes = ['clicks_count' => 0];

    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }

    public static function generateCode(int $length = 6): string
    {
        do {
            $code = collect(range(1, $length))
                ->map(fn () => self::ALPHABET[random_int(0, strlen(self::ALPHABET) - 1)])
                ->implode('');
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function shortUrl(): string
    {
        return url($this->code);
    }

    public function displayUrl(int $limit = 60): string
    {
        return Str::limit(preg_replace('#^https?://(www\.)?#', '', $this->url), $limit);
    }
}
