<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TemplateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Sablon model.
 */
class Template extends Model
{
    /** @use HasFactory<TemplateFactory> */
    use HasFactory;

    /**
     * Tömegesen hozzárendelhető attribútumok.
     *
     * @var list<string>
     */
    protected $fillable = ['title', 'content', 'type', 'is_active'];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
