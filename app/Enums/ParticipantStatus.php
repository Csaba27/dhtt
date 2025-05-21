<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * A résztvevők státuszait kezelő enum.
 */
enum ParticipantStatus: string
{
    case Absent = 'absent';
    case Started = 'started';
    case Completed = 'completed';
    case Abandoned = 'abandoned';

    /**
     * Visszaadja a státusz magyar nyelvű címkéjét.
     */
    public function label(): string
    {
        return match ($this) {
            self::Started => 'Elkezdte',
            self::Completed => 'Befejezte',
            self::Abandoned => 'Feladta',
            self::Absent => 'Hiányzó',
        };
    }

    /**
     * Visszaadja az összes státusz opciót érték-címke párokban.
     *
     * @return array<int, array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(fn ($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }

    /**
     * Visszaadja az alapértelmezett státuszt.
     */
    public static function default(): self
    {
        return self::Absent;
    }

    /**
     * Visszaadja az összes státuszt asszociatív tömbként.
     *
     * @return array<string, string>
     */
    public static function toArray(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])->toArray();
    }
}
