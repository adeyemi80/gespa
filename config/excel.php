<?php

use Maatwebsite\Excel\Excel;

return [

    'exports' => [

        /*
        |--------------------------------------------------------------------------
        | Chunk size
        |--------------------------------------------------------------------------
        |
        | When using FromQuery, the query is automatically chunked.
        | Here you can specify how big the chunk should be.
        |
        */
        'chunk_size'             => 1000,

        /*
        |--------------------------------------------------------------------------
        | Pre-calculate formulas during export
        |--------------------------------------------------------------------------
        */
        'pre_calculate_formulas' => false,

        /*
        |--------------------------------------------------------------------------
        | Enable strict null comparison
        |--------------------------------------------------------------------------
        */
        'strict_null_comparison' => false,

        /*
        |--------------------------------------------------------------------------
        | CSV Settings
        |--------------------------------------------------------------------------
        |
        | Configure the CSV writer.
        |
        */
        'csv' => [
            'delimiter'              => ',',
            'enclosure'              => '"',
            'line_ending'            => PHP_EOL,
            'use_bom'                => false,
            'include_separator_line' => false,
            'excel_compatibility'    => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | Default writer type
        |--------------------------------------------------------------------------
        |
        | Here we force XLSX as the default writer, to éviter les confusions CSV/XLSX.
        |
        */
        'default_writer' => Excel::XLSX,
    ],

    'imports' => [

        'read_only' => true,

        'heading_row' => [
            'formatter' => 'slug',
        ],
    ],

    'extension_detector' => [

        'xlsx'     => Excel::XLSX,
        'xlsm'     => Excel::XLSX,
        'xltx'     => Excel::XLSX,
        'xltm'     => Excel::XLSX,
        'xls'      => Excel::XLS,
        'xlt'      => Excel::XLS,
        'ods'      => Excel::ODS,
        'ots'      => Excel::ODS,
        'slk'      => Excel::SLK,
        'xml'      => Excel::XML,
        'gnumeric' => Excel::GNUMERIC,
        'htm'      => Excel::HTML,
        'html'     => Excel::HTML,
        'csv'      => Excel::CSV,
        'tsv'      => Excel::TSV,

        // Ajout d'une sécurité
        'default'  => Excel::XLSX,
    ],

    'value_binder' => [

        'default' => Maatwebsite\Excel\DefaultValueBinder::class,
    ],

    'transactions' => [

        'handler' => env('EXCEL_TRANSACTIONS_HANDLER', 'db'),
],

    'temporary_files' => [

        'local_path'          => storage_path('framework/laravel-excel'),
        'remote_disk'         => null,
        'remote_prefix'       => null,
        'force_resync_remote' => null,
    ],
];
