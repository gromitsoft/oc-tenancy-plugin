<?php

namespace GromIT\Tenancy\Models;

abstract class ImportModel extends \Backend\Models\ImportModel
{
    public $attachOne = [
        'import_file' => [
            File::class,
            'public' => false
        ],
    ];
}
