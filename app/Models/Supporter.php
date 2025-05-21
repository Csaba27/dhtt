<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SupporterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Támogató model.
 *
 * @property string $image_url
 * @property bool $is_local
 * @property-read string $image_path
 */
class Supporter extends Model
{
    /** @use HasFactory<SupporterFactory> */
    use HasFactory;

    /**
     * Tömegesen hozzárendelhető attribútumok.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'image_url', 'is_local', 'link'];

    /**
     * A teljes kép elérési útjának lekérése.
     */
    public function getImagePathAttribute(): string
    {
        return $this->is_local
            ? asset(Storage::url($this->image_url))
            : $this->image_url;
    }
}
