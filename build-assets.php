<?php
    define('CACHE', true);
    define('COMPRESS', true);

    if (empty($_GET['type']) or empty($_GET['filename'])) {
        header('Status: 406 Not acceptable');
        throw new Exception('Missing vital parameters');
    }

    $assets = array(
        'css' => array(
            'content-type' => 'text/css',
            'files' => array(
                'assets/style.css',
                'assets/form.css',
                'assets/form-elements.css',
                'assets/collapsable.css',
                'assets/tooltip.css',
            ),
        ),
        'js' => array(
            'content-type' => 'text/javascript',
            'files' => array(
                'assets/storage.js',
                'assets/collapsable.js',
                'assets/script.js',
            ),
        )
    );
    
    $type  = $_GET['type'];
    if (!isset($assets[$type])) {
        header('Status: 406 Not acceptable');
        throw new Exception('Invalid assets type "'.$type.'" defined');
    }
    $asset = $assets[$type];
    
    $result = implode("\n", array_map('file_get_contents', $asset['files']));

    if (COMPRESS) {
        if ($type === 'css') {
            require 'classes/vendor/cssmin.class.php';
            $result = cssmin::minify($result);
        } elseif ($type === 'js') {
            require 'classes/vendor/JSMinPlus.class.php';
            $result = JSMinPlus::minify($result);
        }
    }

    if (CACHE) {
        file_put_contents($_GET['filename'], $result);
        chmod($_REQUEST['filename'], 0666);
        header('Location: '.$_SERVER['REQUEST_URI']);
    } else {
        header('Content-Type: '.$asset['content-type']);
        echo $result;
    }
