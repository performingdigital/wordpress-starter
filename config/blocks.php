<?php

return [
    'card' => [
        'title' => 'Card',
        'category' => 'widgets',
        'attributes' => [
            'title' => [
                'type' => 'string',
                'default' => '',
            ],
            'text' => [
                'type' => 'string',
                'default' => '',
            ],
        ],
        'supports' => [
            'autoRegister' => true,
        ],
    ],
];
