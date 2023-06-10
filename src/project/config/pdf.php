<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'tempDir'               => base_path('storage/temp/'),
    'font_path'             => base_path('storage/fonts/'),
    'font_data'             => [
        'examplefont' => [
            'R'  => 'IRANSans.ttf',
            'B'  => 'IRANSans_Bold.ttf',
            'I'  => 'IRANSans.ttf',
            'BI' => 'IRANSans.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'iransansfa' => [
            'R'  => 'IRANSansWebFa.ttf',
            'B'  => 'IRANSansWebFa_Bold.ttf',
            'I'  => 'IRANSansWebFa.ttf',
            'BI' => 'IRANSansWebFa.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ]
    ]
];
