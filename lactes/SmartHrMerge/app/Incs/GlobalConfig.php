<?php

namespace App\Incs;

class GlobalConfig {
    // Template Variables
    public  $name               = '',
        $version            = '',
        $author             = '',
        $title              = '',
        $description        = '',
        $assets_folder      = '';

    /**
     * Class constructor
     */
    public function __construct($name = '', $version = '', $assets_folder = '') {
        // Set Template's name, version and assets folder
        $this->name                 = $name;
        $this->version              = $version;
        $this->assets_folder        = $assets_folder;
    }
}
