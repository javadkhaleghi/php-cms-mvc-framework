<?php

namespace Core;

use Core\Config;

class View
{
    private $_siteTitle = '';
    private $_content = [];
    private $_currentContent;
    private $_buffer;
    private $_layout;
    private $_defaultViewPath;

    public function __construct($path = '')
    {
        $this->_defaultViewPath = $path;
        $this->_siteTitle = Config::get('default_site_title');
    }

    public function setLayout($layout)
    {
        $this->_layout = $layout;
    }

    public function setSiteTitle($title)
    {
        $this->_siteTitle = $title;
    }

    public function getSiteTitle()
    {
        return $this->_siteTitle;
    }

    public function render($path = '')
    {
        if(empty($path)) {
            $path = $this->_defaultViewPath;
        }

        $layoutPath = SITE_ROOT . DS . 'app' . DS . 'views' . DS . 'layouts' . DS . $this->_layout . '.php';
        $fullPath = SITE_ROOT . DS . 'app' . DS . 'views' . DS . $path . '.php';

        if(!file_exists($fullPath)) {
            throw new \Exception("The view [{$path}] does not exist.");
        }

        if(!file_exists($layoutPath)) {
            throw new \Exception("The layout [{$this->_layout}] does not exist.");
        }

        include($fullPath);
        include($layoutPath);
    }

    public function start($key)
    {
        if(empty($key)) {
            throw new \Exception("Start method requires a valid key.");
        }

        $this->_buffer = $key;
        ob_start();
    }

    public function end()
    {
        if(empty($this->_buffer)) {
            throw new \Exception("First run the start method.");
        }

        $this->_content[$this->_buffer] = ob_get_clean();
        $this->_buffer = null;
    }

    public function content($key)
    {
        if(array_key_exists($key, $this->_content)) {
            echo $this->_content[$key];
        } else {
            echo '';
        }
    }

    public function section($path)
    {
        $fullPath = SITE_ROOT . DS . 'app' . DS . 'views' . DS . $path . '.php';
        if(file_exists($fullPath)) {
            include($fullPath);
        }
    }
}