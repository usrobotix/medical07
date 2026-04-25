<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Research Dataset Source Path
    |--------------------------------------------------------------------------
    |
    | Path to the root folder containing the Research/ directory with clinic
    | YAML files. The import command will scan this directory recursively for
    | clinic.yaml files.
    |
    | Default: storage/app/research/
    |
    | To populate:
    |   cp -r /path/to/yandex-direct/Research storage/app/research/
    | or:
    |   git -C storage/app/research clone git@github.com:usrobotix/yandex-direct.git
    |
    */
    'source_path' => env('RESEARCH_SOURCE_PATH', storage_path('app/research')),
];
