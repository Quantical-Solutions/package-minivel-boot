<?php

namespace Minivel\Igniter\Candela;

use Carbon\Carbon;
use Jenssegers\Blade\Blade;
use Minivel\Igniter\Wormhole\Wormhole;
use Minivel\Uxdebugger\Debugger as Uxdebug;
use Minivel\Igniter\Solutions\Solutions;
use Minivel\Igniter\Candela\Expander as Database;
use Illuminate\Database\Capsule\Manager as DB;
use Minivel\Igniter\Spectral\DataCollector;
use Minivel\Auth\Matrix\FormErrors as Errors;

class Config
{
    /**
     * uriSegments method
     * Convert REQUEST_URI in array.
     *
     * @return array
     */
    public static function uriSegments()
    {
        $uris = $_SERVER['REQUEST_URI'];
        $clean = explode('#', $uris)[0];
        $clean = explode('?', $clean)[0];
        $explode = explode('/', $clean);
        return $explode;
    }

    /**
     * views method
     * Convert Select the blade view corresponding to the URL.
     *
     * @param $view
     * @param mixed $data
     * @return void
     */
    public static function views($view, $data = false) {

        $resources = '/resources/views';
        $cache = '/app/Views/cache/blade';

        if (!defined('CHECKRENDER')) {
            define('CHECKRENDER', true);
        }

        $scan = scandir(ROOTDIR . $resources);
        $allViews = [];
        foreach ($scan as $file) {
            if ($file != '.' && $file != '..') {
                $newRoot = ROOTDIR . $resources . '/' . $file;
                if (is_dir($newRoot)) {
                    $newViews = self::listAllViews($newRoot, ROOTDIR . $resources);
                    if (!empty($newViews)) {
                        foreach ($newViews as $newView) {
                            $new = str_replace(
                                '.blade.php', '', str_replace(
                                    ROOTDIR . $resources . '/', '', $newView
                                )
                            );
                            array_push($allViews, $new);
                        }
                    }
                } else {
                    if (is_file($newRoot)) {
                        $new = str_replace(
                            '.blade.php', '', str_replace(
                                ROOTDIR . $resources . '/', '', $newRoot
                            )
                        );
                        array_push($allViews, $new);
                    }
                }
            }
        }

        $view = str_replace('.', '/', $view);

        Solutions::addViews($allViews);
        Solutions::askedView($view);

        addSolution(
            'The Blade\'s view [ ' . $view . ' ] doesn\'t exist.',
            'Did you mean <b>[ ' . Solutions::$possibleView . ' ]</b> ?'
        );

        if (file_exists(ROOTDIR . $resources . '/' . $view . '.blade.php')) {

            $tracer = debug_backtrace();
            $file = '';
            if (isset($tracer[1])) {
                $file = str_replace(ROOTDIR, '', $tracer[1]['file']) . ':' . $tracer[1]['line'];
            }
            $_ENV['constellation']['main']['view'] = $view;
            $_ENV['constellation']['main']['data'] = $data;
            $_ENV['constellation']['main']['file'] = $file;

            $blade = new Blade(ROOTDIR . $resources, ROOTDIR . $cache);
            $data['errors'] = Errors::get();
            $original = $blade->render($view, $data);
            $uxDebugger = (class_exists('Minivel\Uxdebugger\Debugger')) ? Uxdebug::ignite() : false;
            $debug = Wormhole::BottomBar(config('wormhole.bottombar'), $uxDebugger, array(
                'view' => $view,
                'params' => array_keys($data),
                'paths' => $resources
            ));

            if (isset(explode('</body>', $original)[1])) {

                $content = explode('</body>', $original)[0] . PHP_EOL;
                $closure = $debug . PHP_EOL . '</body>' . explode('</body>', $original)[1];
                echo $content . $closure;

            } else {

                $head = (strpos($original, '<head>') === false)
                    ?  '<head><meta charset="UTF-8"><title>Empty view...</title><link rel="icon" href="/vendor/minivel/boot/src/Igniter/ErrorDocument/assets/favicon.png"></head>'
                    : '';
                $content = $head . PHP_EOL . $original . PHP_EOL . $debug;
                return $content;
            }

        } else {
            trigger_error('The Blade\'s view [ ' . $view . ' ] doesn\'t exist.');
        }
    }

    /**
     * listAllViews method
     * A list of all existing views
     *
     * @param $root
     * @param $origin
     * @return array
     */
    private static function listAllViews($root, $origin)
    {
        $allViews = [];
        $scan = scandir($root);
        foreach ($scan as $file) {
            if ($file != '.' && $file != '..') {
                $newRoot = $root . '/' . $file;
                if (is_dir($newRoot)) {
                    $newViews = self::listAllViews($newRoot, $origin);
                    if (!empty($newViews)) {
                        foreach ($newViews as $newView) {
                            $new = str_replace(
                                '.blade.php', '', str_replace(
                                    $origin . '/', '', $newView
                                )
                            );
                            array_push($allViews, $new);
                        }
                    }
                } else {
                    if (is_file($newRoot)) {
                        $new = str_replace(
                            '.blade.php', '', str_replace(
                                $origin . '/', '', $newRoot
                            )
                        );
                        array_push($allViews, $new);
                    }
                }
            }
        }
        return $allViews;
    }

    /**
     * createSVGFolder method
     * Create de SVGs folder if not exists
     *
     * @return void
     */
    public static function createSVGFolder()
    {
        if (!file_exists(ROOTDIR . '/resources/svg')) {
            mkdir(ROOTDIR . '/resources/svg');
        }
    }

    /**
     * import_svg method
     * Import a svg file from the SVG folder and build a HTML Element containing de asked svg file
     *
     * @param $file
     * @param $class
     * @param mixed $array
     * @return mixed
     */
    public static function import_svg($file, $class, $array = false)
    {
        $js = '';
        if ($array != false) {
            foreach ($array as $foo) {

                $js .= ' ' . $foo[0] . '="' . $foo[1] . '"';
            }
        }

        $path = ROOTDIR . '/storage/svgs/' . $file . '.svg';
        if (file_exists($path)) {

            ob_start();
            $inner = str_replace('<svg ', '<svg class="' . explode(' ', $class)[0] . '_svg" ', file_get_contents($path));

            if (strpos($inner, '<title>') !== false && strpos($inner, '</title>') !== false) {

                $final1 = substr($inner, 0, strpos($inner, '<title>'));
                $final2 = substr($inner, strpos($inner, '</title>'), strlen($inner));
                $final = $final1 . $final2;

            } else {

                $final = $inner;
            }

            echo '<div class="' . $class . '"' . $js . '>' . $final . '</div>';
            $svg = ob_get_clean();
            return $svg;
        }
    }

    /**
     * sitemap_generator method
     * Build the sitemap.xml and robot.txt files at the project's root
     *
     * @return void
     */
    public static function sitemap_generator() {

        if (config('app.env') !== 'production') {

            $navigation = config('app.navigation');
            $array = [];

            foreach ($navigation as $row) {

                $page = [$row['titre'], $row['url']];

                $priority = ($row['titre'] == 'Accueil' || $row['titre'] == 'Le Blog' || $row['titre'] == 'Tous nos sp??cialistes' ||
                    $row['titre'] == 'L\'offre Astro Consult\'' || $row['titre'] == 'Les tarifs conseill??s') ? '1' : '0.9';

                array_push($page, $priority);
                array_push($array, $page);
            }

            $dir = scandir(ROOTDIR);
            $checker = 0;
            foreach ($dir as $file) { if ($file == 'sitemap.xml') { $checker++; } }
            $all = [];

            $onglets = $array;

            foreach ($onglets as $onglet) {
                array_push($all, $onglet);
            }

            if ($checker == 0) {

                $doc = new \DOMDocument('1.0', 'UTF-8');
                $doc->formatOutput = true;
                $nav = $doc->createElement('urlset');
                $att = $doc->createAttribute('xmlns');
                $att->value = 'http://www.sitemaps.org/schemas/sitemap/0.9';
                $nav->appendChild($att);
                $nav = $doc->appendChild($nav);

                foreach ($all as $onglet) {

                    $em = $doc->createElement('url');

                    $li1 = $doc->createElement('loc', (config('app.url') . $onglet[1]));
                    $em->appendChild($li1);
                    $li2 = $doc->createElement('lastmod', date('Y-m-d'));
                    $em->appendChild($li2);
                    $li3 = $doc->createElement('changefreq', 'weekly');
                    $em->appendChild($li3);
                    $li4 = $doc->createElement('priority', $onglet[2]);
                    $em->appendChild($li4);
                    $li5 = $doc->createElement('xhtml:link');
                    $att1 = $doc->createAttribute('rel');
                    $att1->value = 'alternate';
                    $li5->appendChild($att1);
                    $att2 = $doc->createAttribute('hreflang');
                    $att2->value = 'fr';
                    $li5->appendChild($att2);
                    $att3 = $doc->createAttribute('href');
                    $att3->value = config('app.url') . $onglet[1];
                    $li5->appendChild($att3);
                    $em->appendChild($li5);

                    $nav->appendChild($em);
                }

                $doc->save('sitemap.xml');

                $fp = fopen('robots.txt', 'w');
                $string = 'Sitemap: ' . config('app.url') . '/sitemap.xml';
                fwrite($fp, $string);
                fclose($fp);

            } else {

                if (file_exists(ROOTDIR . '/sitemap.xml')) {
                    unlink(ROOTDIR . '/sitemap.xml');
                }
                if (ROOTDIR . '/robots.txt') {
                    unlink(ROOTDIR . '/robots.txt');
                }
                self::sitemap_generator();
            }
        }
    }

    /**
     * is_ajax method
     * Check if the Request sent is an XMLHttpRequest type
     *
     * @return boolean
     */
    public static function is_ajax() {

        $response = false;
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $response = true;
        }
        return $response;
    }

    /**
     * symlinker method
     * Create symlinks
     *
     * @param $target
     * @param $link
     * @return void
     */
    public static function symlinker()
    {
        $symlinks = require(ROOTDIR . '/config/symlinks.php');
        $vendorLinks = require(dirname(__DIR__) . '/symlinks.php');
        foreach ($symlinks as $link => $target) {
            if (!file_exists($link) && file_exists($target)) {
                if (isset($_SERVER['WINDIR']) && ($_SERVER['WINDIR'] || $_SERVER['windir'])) {
                    exec('junction "' . $link . '" "' . $target . '"');
                } else {
                    symlink($target, $link);
                }
            }
            self::interceptor($link);
        }
        foreach ($vendorLinks as $link => $target) {
            if (!file_exists($link) && file_exists($target)) {
                if (isset($_SERVER['WINDIR']) && ($_SERVER['WINDIR'] || $_SERVER['windir'])) {
                    exec('junction "' . $link . '" "' . $target . '"');
                } else {
                    symlink($target, $link);
                }
            }
            self::interceptor($link);
        }
    }

    private static function interceptor($link)
    {
        $fullURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" .
            $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $uri = str_replace(config('app.url'), '', $fullURL);
        if (isset(explode('/', $uri)[1])
            && explode('/', $uri)[1] == $link) {

            $rest = explode($link . '/', $uri)[1];
            $new = $link . '/' . $rest;
            if (file_exists($new)) {
                $split = explode('.', $new);
                $ext = end($split);
                if ($ext == 'css' || $ext == 'html' || $ext == 'json') {
                    header('Content-Type: text/' . $ext);
                } else if ($ext == 'js') {
                    header('Content-Type: text/javascript');
                } else if ($ext == 'json') {
                    header('Content-Type: text/' . $ext);
                } else {
                    header('Content-Type: application/octet-stream');
                }
                readfile($new);
                exit();
            }
        }
    }

    /**
     * unlinkSymlinker method
     * Delete symlink from the symlinks list
     *
     * @return void
     */
    public static function unlinkSymlinker($link)
    {
        if (file_exists($link)) {
            if (isset($_SERVER['WINDIR']) && ($_SERVER['WINDIR'] || $_SERVER['windir'])) {
                exec('junction -d "' . $link . '"');
            } else {
                unlink($link);
            }
        }
    }

    /**
     * config method
     * Convert all globals files and their contents in root scope variables
     *
     * @param $str
     * @return mixed
     */
    public static function config($str)
    {
        $globals = ROOTDIR . '/config/';

        if (count(explode('.', $str)) == 2) {

            $component = strtolower(explode('.', $str)[0]);
            $index = strtolower(explode('.', $str)[1]);

            $files = scandir($globals);

            foreach ($files as $file) {

                $var = strtolower(str_replace('.php', '', $file));

                if ($component == $var) {

                    $content = require($globals . $file);
                    $response = $content[$index];
                    return $response;
                }
            }

        } else {

            trigger_error('Wrong string parameter format in config(). Argument : "' . $str . '" is not valid');
        }
    }

    /**
     * init method
     * Convert .init file constants in root scope variables
     *
     * @param $declaration
     * @param mixed $default
     * @return mixed
     */
    public static function init($declaration, $default = null)
    {
        $response = $default;
        $initArray = Config::ConvertEnvConstants();

        if (is_string($declaration) && isset($initArray[$declaration])) {

            $response = $initArray[$declaration];
        }

        return $response;
    }

    /**
     * humanizeSize method
     * Convert octets in readable information for human
     *
     * @param $space
     * @return string
     */
    public static function humanizeSize($space)
    {
        if ($space / pow(1024, 4) < 1 && $space / pow(1024, 3) >= 1) {
            $used = number_format(($space / pow(1024, 3)), 1, '.', ' ') . 'GB';
        } else if ($space / pow(1024, 3) < 1 && $space / pow(1024, 2) >= 1) {
            $used = number_format(($space / pow(1024, 2)), 1, '.', ' ') . 'MB';
        } else if ($space / pow(1024, 2) < 1 && $space / 1024 >= 1) {
            $used = number_format(($space / 1024), 1, '.', ' ') . 'KB';
        } else if ($space / 1024 < 1 && $space >= 1) {
            $used = number_format($space, 1, '.', ' ') . 'B';
        } else {
            $used = '0B';
        }

        return $used;
    }

    /**
     * storage_path method
     * Build the sitemap.xml and robot.txt files at the project's root
     *
     * @param $data
     * @return string
     */
    public static function storage_path($data)
    {
        return $data;
    }

    /**
     * resource_path method
     * Build the sitemap.xml and robot.txt files at the project's root
     *
     * @param $path
     * @return string
     */
    public static function resource_path($path)
    {
        return $path;
    }

    /**
     * setDB method
     * Set connexion to DataBase with Eloquent
     *
     * @return void
     */
    public static function setDB()
    {
        new Database();
        DB::connection()->enableQueryLog();
    }

    /**
     * addMessage method
     * Add messages to the collection of DataCollector Object
     *
     * @param $data
     * @param $level
     * @return void
     */
    public static function addMessage($data, $level)
    {
        DataCollector::addMessage($data, $level);
    }

    /**
     * addModel method
     * Add models to the collection of DataCollector Object
     *
     * @param $data
     * @return void
     */
    public static function addModel($data)
    {
        DataCollector::addModel($data);
    }

    /**
     * addMails method
     * Add mails to the collection of DataCollector Object
     *
     * @param $data
     * @return void
     */
    public static function addMails($data)
    {
        DataCollector::addMails($data);
    }

    /**
     * addGates method
     * Add gates to the collection of DataCollector Object
     *
     * @param $data
     * @return void
     */
    public static function addGates($data)
    {
        DataCollector::addGates($data);
    }

    /**
     * addQuery method
     * Add queries to the collection of DataCollector Object
     *
     * @param $queries
     * @param $traces
     * @return void
     */
    public static function addQueries($queries, $traces)
    {
        DataCollector::addQueries($queries, $traces);
    }

    /**
     * collect method
     * Return a collection of DataCollector Object
     *
     * @return mixed
     */
    public static function collect()
    {
        return DataCollector::collect();
    }
}