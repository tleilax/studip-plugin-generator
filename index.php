<?php
    require 'includes/bootstrap.php';

    $action = basename(request('action', 'generator')); 
    
    // controller
    $variables = (array)include 'controllers/'.$action.'.php';
    
    // view
    header('Content-Type: text/html; charset=UTF-8');
    echo render($action, (array)@$variables, 'html-layout', 'CONTENT', 'html_trim_whitespace');
