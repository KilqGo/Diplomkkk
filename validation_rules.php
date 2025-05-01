<?php
return [
 'assembly' => [
        'assembly_price' => [
            'required' => true, 
            'type' => 'numeric',
            'max' => 9999999,
            'messages' => [
                'required' => 'Цена сборки обязательна',
                'type' => 'Цена должна быть числом',
                'max' => 'Максимальная цена 9,999,999'
            ]
        ],
        'date_of_admission' => [
            'required' => true,
            'type' => 'date',
            'messages' => ['type' => 'Неверный формат даты (ГГГГ-ММ-ДД)']
        ],
        'date_of_delivery' => [
            'required' => true,
            'type' => 'date',
            'messages' => ['type' => 'Неверный формат даты (ГГГГ-ММ-ДД)']
        ],
        'delivery_address' => [
            'required' => true, 
            'max' => 110,
            'messages' => [
                'required' => 'Адрес доставки обязателен',
                'max' => 'Максимальная длина адреса 110 символов'
            ]
        ],
        'ctr_id' => ['required' => true, 'foreign' => 'customer.ctr_id'],
        'mtr_id' => ['required' => true, 'foreign' => 'master.mtr_id'],
        'mbd_id' => ['required' => true, 'foreign' => 'motherboard.mbd_id'],
        'cpu_id' => ['required' => true, 'foreign' => 'processor.cpu_id'],
        'ram_id' => ['required' => true, 'foreign' => 'ram.ram_id'],
        'cse_id' => ['required' => true, 'foreign' => 'mcase.cse_id'],
        'gpu_id' => ['required' => true, 'foreign' => 'gpu.gpu_id'],
        'psu_id' => ['required' => true, 'foreign' => 'powerunit.psu_id'],
        'sdu_id' => ['required' => true, 'foreign' => 'storage.sdu_id']
    ],

    'master' => [
        'full_name' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'ФИО обязательно',
                'max' => 'Максимальная длина ФИО 110 символов'
            ]
        ],
        'legal_address' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Адрес обязателен',
                'max' => 'Максимальная длина адреса 110 символов'
            ]
        ],
        'phone_number' => [
            'required' => true,
            'pattern' => '/^\+?\d{10,15}$/',
            'messages' => [
                'required' => 'Телефон обязателен',
                'pattern' => 'Формат: +79998887766 или 89998887766'
            ]
        ]
    ],

    'customer' => [
        'full_name' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'ФИО обязательно',
                'max' => 'Максимальная длина ФИО 110 символов'
            ]
        ],
        'legal_address' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Адрес обязателен',
                'max' => 'Максимальная длина адреса 110 символов'
            ]
        ],
        'phone_number' => [
            'required' => true,
            'pattern' => '/^\+?\d{10,15}$/',
            'messages' => [
                'required' => 'Телефон обязателен',
                'pattern' => 'Формат: +79998887766 или 89998887766'
            ]
        ]
    ],

    'gpu' => [
        'gpu_name' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Название обязательно',
                'max' => 'Максимум 110 символов'
            ]
        ],
        'gmemory_size' => [
            'required' => true,
            'type' => 'numeric',
            'messages' => [
                'required' => 'Объем памяти обязателен',
                'type' => 'Должно быть числом'
            ]
        ],
        'gpu_series' => [
            'required' => true,
            'max' => 30,
            'messages' => [
                'required' => 'Серия обязательна',
                'max' => 'Максимум 30 символов'
            ]
        ],
        'gpu_manufacturer' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Производитель обязателен',
                'max' => 'Максимум 110 символов'
            ]
        ],
        'price' => [
            'required' => true,
            'type' => 'numeric',
            'max' => 999999,
            'messages' => [
                'required' => 'Цена обязательна',
                'type' => 'Должно быть числом',
                'max' => 'Максимальная цена 999,999'
            ]
        ]
    ],

    'mcase' => [
        'case_name' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Название обязательно',
                'max' => 'Максимум 110 символов'
            ]
        ],
        'form_factor' => [
            'required' => true,
            'max' => 20,
            'messages' => [
                'required' => 'Форм-фактор обязателен',
                'max' => 'Максимум 20 символов'
            ]
        ],
        'case_size' => [
            'required' => true,
            'max' => 30,
            'messages' => [
                'required' => 'Размер обязателен',
                'max' => 'Максимум 30 символов'
            ]
        ],
        'case_manufacturer' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Производитель обязателен',
                'max' => 'Максимум 110 символов'
            ]
        ],
        'price' => [
            'required' => true,
            'type' => 'numeric',
            'max' => 999999,
            'messages' => [
                'required' => 'Цена обязательна',
                'type' => 'Должно быть числом',
                'max' => 'Максимальная цена 999,999'
            ]
        ]
    ],

    'motherboard' => [
        'motherboard_name' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Название обязательно',
                'max' => 'Максимум 110 символов'
            ]
        ],
        'form_factor' => [
            'required' => true,
            'max' => 20,
            'messages' => [
                'required' => 'Форм-фактор обязателен',
                'max' => 'Максимум 20 символов'
            ]
        ],
        'chipset' => [
            'required' => true,
            'max' => 30,
            'messages' => [
                'required' => 'Чипсет обязателен',
                'max' => 'Максимум 30 символов'
            ]
        ],
        'socket' => [
            'required' => true,
            'max' => 30,
            'messages' => [
                'required' => 'Сокет обязателен',
                'max' => 'Максимум 30 символов'
            ]
        ],
        'board_manufacturer' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Производитель обязателен',
                'max' => 'Максимум 110 символов'
            ]
        ],
        'price' => [
            'required' => true,
            'type' => 'numeric',
            'max' => 999999,
            'messages' => [
                'required' => 'Цена обязательна',
                'type' => 'Должно быть числом',
                'max' => 'Максимальная цена 999,999'
            ]
        ]
    ],

    'powerunit' => [
        'power_name' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Название обязательно',
                'max' => 'Максимум 110 символов'
            ]
        ],
        'capability' => [
            'required' => true,
            'type' => 'numeric',
            'messages' => [
                'required' => 'Мощность обязательна',
                'type' => 'Должно быть числом'
            ]
        ],
        'power_manufacturer' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Производитель обязателен',
                'max' => 'Максимум 110 символов'
            ]
        ],
        'price' => [
            'required' => true,
            'type' => 'numeric',
            'max' => 999999,
            'messages' => [
                'required' => 'Цена обязательна',
                'type' => 'Должно быть числом',
                'max' => 'Максимальная цена 999,999'
            ]
        ]
    ],

    'processor' => [
        'unit_name' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Название обязательно',
                'max' => 'Максимум 110 символов'
            ]
        ],
        'socket' => [
            'required' => true,
            'max' => 30,
            'messages' => [
                'required' => 'Сокет обязателен',
                'max' => 'Максимум 30 символов'
            ]
        ],
        'base_frequency' => [
            'required' => true,
            'type' => 'numeric',
            'messages' => [
                'required' => 'Частота обязательна',
                'type' => 'Должно быть числом'
            ]
        ],
        'number_of_cores' => [
            'required' => true,
            'type' => 'numeric',
            'messages' => [
                'required' => 'Количество ядер обязательно',
                'type' => 'Должно быть числом'
            ]
        ],
        'cpu_manufacturer' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Производитель обязателен',
                'max' => 'Максимум 110 символов'
            ]
        ],
        'price' => [
            'required' => true,
            'type' => 'numeric',
            'max' => 999999,
            'messages' => [
                'required' => 'Цена обязательна',
                'type' => 'Должно быть числом',
                'max' => 'Максимальная цена 999,999'
            ]
        ]
    ],

    'ram' => [
        'ram_name' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Название обязательно',
                'max' => 'Максимум 110 символов'
            ]
        ],
        'memory_size' => [
            'required' => true,
            'type' => 'numeric',
            'messages' => [
                'required' => 'Объем обязателен',
                'type' => 'Должно быть числом'
            ]
        ],
        'type' => [
            'required' => true,
            'max' => 10,
            'messages' => [
                'required' => 'Тип обязателен',
                'max' => 'Максимум 10 символов'
            ]
        ],
        'base_frequency' => [
            'required' => true,
            'type' => 'numeric',
            'messages' => [
                'required' => 'Частота обязательна',
                'type' => 'Должно быть числом'
            ]
        ],
        'ram_manufacturer' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Производитель обязателен',
                'max' => 'Максимум 110 символов'
            ]
        ],
        'price' => [
            'required' => true,
            'type' => 'numeric',
            'max' => 999999,
            'messages' => [
                'required' => 'Цена обязательна',
                'type' => 'Должно быть числом',
                'max' => 'Максимальная цена 999,999'
            ]
        ]
    ],

    'storage' => [
        'storage_name' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Название обязательно',
                'max' => 'Максимум 110 символов'
            ]
        ],
        'storage_capacity' => [
            'required' => true,
            'type' => 'numeric',
            'messages' => [
                'required' => 'Объем обязателен',
                'type' => 'Должно быть числом'
            ]
        ],
        'reading_speed' => [
            'required' => true,
            'type' => 'numeric',
            'messages' => [
                'required' => 'Скорость чтения обязательна',
                'type' => 'Должно быть числом'
            ]
        ],
        'sdu_type' => [
            'required' => true,
            'max' => 20,
            'messages' => [
                'required' => 'Тип обязателен',
                'max' => 'Максимум 20 символов'
            ]
        ],
        'sdu_manufacturer' => [
            'required' => true,
            'max' => 110,
            'messages' => [
                'required' => 'Производитель обязателен',
                'max' => 'Максимум 110 символов'
            ]
        ],
        'price' => [
            'required' => true,
            'type' => 'numeric',
            'max' => 999999,
            'messages' => [
                'required' => 'Цена обязательна',
                'type' => 'Должно быть числом',
                'max' => 'Максимальная цена 999,999'
            ]
        ]
    ]
];
?>