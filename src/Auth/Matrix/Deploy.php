<?php

namespace Minivel\Auth\Matrix;

class Deploy
{
    private string $appResources = '';
    private string $originalViews = '';
    private string $configFile = '';
    private bool $todo = false;

    public function __construct()
    {
        $this->setVariables();
        if ($this->todo == true) {
            $this->preparePack();
        }
    }

    private function setVariables()
    {
        if (defined('ROOTDIR')) {

            $this->appResources = ROOTDIR . '/resources/views';
            $this->originalViews = dirname(__DIR__) . '/Clones/views';
            $this->configFile = dirname(__DIR__) . '/Clones/chosen.php';

            if (!file_exists(ROOTDIR . '/resources/views/chosen')) {
                $this->todo = true;
            }

        } else {

            trigger_error('Constant "ROOTDIR" is not set.');
        }
    }

    private function preparePack()
    {
        if (!file_exists(ROOTDIR . '/config/chosen.php')) {
            copy($this->configFile, ROOTDIR . '/config/chosen.php');
        }

        if (!file_exists($this->appResources . '/home.blade.php')) {
            copy($this->originalViews . '/home.blade.php', $this->appResources . '/home.blade.php');
        }

        if (!file_exists($this->appResources . '/chosen-template.blade.php')) {
            copy($this->originalViews . '/chosen-template.blade.php', $this->appResources . '/chosen-template.blade.php');
        }

        if (!file_exists($this->appResources . '/chosen')) {

            mkdir($this->appResources . '/chosen');
            $files = scandir($this->originalViews . '/chosen');
            foreach ($files as $file) {
                if ($file != '.' && $file != '..' && is_file($this->originalViews . '/chosen/' . $file)) {
                    copy($this->originalViews . '/chosen/' . $file, $this->appResources . '/chosen/' . $file);
                }

                if ($file != '.' && $file != '..' && is_dir($this->originalViews . '/chosen/' . $file)) {
                    $new = $this->originalViews . '/chosen/' . $file;
                    $newDir = $this->appResources . '/chosen/' . $file;
                    mkdir($newDir);
                    $passwords = scandir($new);
                    foreach ($passwords as $pass) {
                        if ($pass != '.' && $pass != '..' && is_file($new . '/' . $pass)) {
                            copy($new . '/' . $pass, $newDir . '/' . $pass);
                        }
                    }
                }
            }
        }
    }
}