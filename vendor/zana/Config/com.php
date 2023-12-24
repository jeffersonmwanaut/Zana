<?php
/**
 * root: The top of the directory tree under which the project files are kept.
 */
$root = isset($root) ? $root : dirname(__DIR__, 3);

/**
 * domain: The server on which the project is hosted.
 */
$domain = $_SERVER['SERVER_NAME'];

/**
 * urlRoot: The project directory. Replace value project-dir with your own.
 */
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$urlRoot = str_replace('/public/app.php', '', $protocol . '://'. $domain . $_SERVER['PHP_SELF']);
/**
 * This is the common configuration file.
 * It contains the configuration directives that give,
 * based on current configuration mode, additional instruction to Zana.
 */
return [
    'path' => [
        'img_root' => $urlRoot . '/public/img',
        'css_root' => $urlRoot . '/public/css',
        'js_root' => $urlRoot . '/public/js',
        'favicon' => [
            'filename' => $urlRoot . '/public/img/icon.png',
            'type' => 'image/png'
        ],
        'bootstrap' => [
            'css' => [
                'core' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.x/dist/css/bootstrap.min.css',
                'md' => 'https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.11.0/css/mdb.min.css'
            ],
            'js' => [
                'core' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js',
                'popper' => 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js',
                'md' => 'https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.11.0/js/mdb.min.js'
            ]
        ],
        'fontawesome' => 'https://use.fontawesome.com/releases/v6.5.1/css/all.css',
        'jquery' => 'https://code.jquery.com/jquery-3.6.1.min.js',
        'google_fonts' => [ 
            'roboto' => 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap',
            'montserrat' => 'https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;600;700&display=swap'
        ]
    ]
];