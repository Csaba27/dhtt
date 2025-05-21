<?php

use function Pest\Laravel\artisan;

test('dhtt registration status', function () {
    artisan('registration:status')
        ->expectsOutput('Registration status updated.');
});
