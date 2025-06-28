<?php

return [

    /*
    |--------------------------------------------------------------------------
    | QR Code Generator
    |--------------------------------------------------------------------------
    |
    | This package supports multiple QR Code generators. You can configure
    | the generators here. The default generator is 'svg'.
    |
    | Supported generators: 'svg', 'eps', 'png'
    |
    */

    'generator' => env('QR_CODE_GENERATOR', 'png'),

    /*
    |--------------------------------------------------------------------------
    | QR Code Size
    |--------------------------------------------------------------------------
    |
    | The size of the QR code in pixels. The default is 100.
    |
    */

    'size' => env('QR_CODE_SIZE', 300),

    /*
    |--------------------------------------------------------------------------
    | QR Code Margin
    |--------------------------------------------------------------------------
    |
    | The margin around the QR code in pixels. The default is 0.
    |
    */

    'margin' => env('QR_CODE_MARGIN', 1),

    /*
    |--------------------------------------------------------------------------
    | QR Code Error Correction Level
    |--------------------------------------------------------------------------
    |
    | The error correction level for the QR code. The default is 'M'.
    |
    | Supported levels: 'L', 'M', 'Q', 'H'
    |
    | L - 7% of data can be restored
    | M - 15% of data can be restored
    | Q - 25% of data can be restored
    | H - 30% of data can be restored
    |
    */

    'errorCorrection' => env('QR_CODE_ERROR_CORRECTION', 'M'),

    /*
    |--------------------------------------------------------------------------
    | QR Code Format
    |--------------------------------------------------------------------------
    |
    | The format of the QR code. The default is 'png'.
    |
    | Supported formats: 'svg', 'eps', 'png'
    |
    */

    'format' => env('QR_CODE_FORMAT', 'png'),

    /*
    |--------------------------------------------------------------------------
    | QR Code Colors
    |--------------------------------------------------------------------------
    |
    | The colors for the QR code. The default is black and white.
    |
    */

    'colors' => [
        'foreground' => env('QR_CODE_FOREGROUND_COLOR', '#000000'),
        'background' => env('QR_CODE_BACKGROUND_COLOR', '#FFFFFF'),
    ],

]; 