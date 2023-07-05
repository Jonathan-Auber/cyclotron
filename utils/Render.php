<?php

namespace utils;

/**
 * Renders a view file by including it within the layout file.
 *
 * @param string $path The path to the view file, relative to the 'views' directory.
 * @param array $var An optional array of variables to be extracted and passed to the view file.
 * @return void
 */
class Render
{
    public static function render(string $path, array $var = [])
    {
        extract($var);
        ob_start();
        require('views/' . $path .  '.phtml');
        $pageContent = ob_get_clean();

        require('views/layout.phtml');
    }
}
