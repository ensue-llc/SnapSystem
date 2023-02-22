<?php

return [
    'create_thumb' => env('FILE_UPLOAD_CREATE_THUMB', true),
    'create_folder_for_user' => env("FILE_UPLOAD_FOLDER_FOR_USER", false),
    'image_directory' => env('FILE_UPLOAD_IMAGE_DIRECTORY', 'images'),
    'video_directory' => env('FILE_UPLOAD_IMAGE_DIRECTORY', 'videos'),
    'image_thumb_directory' => env('FILE_UPLOAD_THUMB_DIRECTORY', 'images'),
    'document_directory' => env("FILE_UPLOAD_DOCUMENT_DIRECTORY", 'documents'),
    'font_directory' => env("FILE_UPLOAD_FONT_DIRECTORY", 'fonts'),
    'theme_directory' => env("FILE_UPLOAD_THEME_DIRECTORY", 'themes'),
    'thumb_suffix' => env("FILE_UPLOAD_THUMB_SUFFIX", '_thumb'),
    'thumb_width' => env('FILE_UPLOAD_THUMB_WIDTH', 150),
    'thumb_height' => env('FILE_UPLOAD_THUMB_HEIGHT', 150),
    'temp_dir_folder' => env('FILE_TEMP_DIR_FOLDER', 'temp'),
    'whitelist_urls' => env('WHITELIST_URLS', null),
];
