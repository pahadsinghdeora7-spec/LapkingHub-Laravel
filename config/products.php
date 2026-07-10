<?php

return [
    'images' => [
        'disk' => env('PRODUCT_IMAGE_DISK', 'public'),
        'directory' => env('PRODUCT_IMAGE_DIRECTORY', 'products/gallery'),
        'max_size_kb' => (int) env('PRODUCT_IMAGE_MAX_SIZE_KB', 4096),
        'mimes' => ['jpg', 'jpeg', 'png', 'webp'],
    ],
];
