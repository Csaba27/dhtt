<?php

declare(strict_types=1);

namespace App;

use Livewire\Attributes\Locked;
use Spatie\LivewireWizard\Components\StepComponent;

/**
 * Egyedi lépés komponens.
 */
abstract class FeaturedStepComponent extends StepComponent
{
    /**
     * Meghatározza, hogy a lépés megjelenítésekor validáció szükséges-e.
     */
    #[Locked]
    protected bool $validateOnShowStep = true;

    /**
     * Meghatározza, hogy a lépések átugorhatók-e.
     */
    #[Locked]
    protected bool $canSkipStep = false;

    /**
     * Megjeleníti a megadott lépést.
     *
     * @param  string  $stepName  A megjelenítendő lépés neve.
     */
    public function showStep(string $stepName): void
    {
        if (! $this->canSkipStep) {
            $currentIndex = -1;
            foreach ($this->steps as $index => $step) {
                if ($step->isCurrent()) {
                    $currentIndex = $index;
                }
                if ($step->stepName == $stepName && $step->isNext()) {
                    if ($index - $currentIndex > 1) {
                        $this->addError('error', 'Nem hagyhatsz ki lépéseket!');

                        return;
                    }
                    if ($this->validateOnShowStep && ! $this->validate()) {
                        return;
                    }
                    break;
                }
            }
        }

        parent::showStep($stepName);
    }
}
