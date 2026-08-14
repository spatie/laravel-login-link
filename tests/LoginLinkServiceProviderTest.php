<?php

use Illuminate\View\Compilers\BladeCompiler;

it('does not resolve the Blade compiler during application bootstrap', function () {
    expect(app()->resolved(BladeCompiler::class))->toBeFalse();
});
