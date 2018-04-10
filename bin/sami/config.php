<?php

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->exclude('Tests')
    ->in(__DIR__.'/../../backend/src')
;

return new Sami($iterator, array(
    'title'                => 'Campr Project Documentation',
    'build_dir'            => __DIR__.'/../../backend/src/AppBundle/Resources/docs/build/',
    'cache_dir'            => __DIR__.'/../../backend/src/AppBundle/Resources/docs/cache/',
    'default_opened_level' => 2,
));
