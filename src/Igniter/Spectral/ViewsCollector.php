<?php

namespace Minivel\Igniter\Spectral;

class ViewsCollector
{
    protected $controller = [];
    protected $finalArray = [];

    public function viewParser($data)
    {
        if (!empty($data)) {

            $view = $data['view'];
            $paths = $data['paths'] . '/' . $view . '.blade.php';
            $realPaths = ROOTDIR . $paths;
            $params = $data['params'];
            $file = file($realPaths);
            $views = [];

            $subber = $this->subViewsParser($file, $params, ROOTDIR . $data['paths'], $data['paths']);
            foreach ($subber as $sub) {
                if (isset($sub['name'])) {
                    array_push($views, $sub);
                }
            }

            $origin = [
                'name' => str_replace('/', '.', $view),
                'view' => $view,
                'paths' => $paths,
                'params' => $this->getUsedParams($file),
                'allParams' => $params
            ];

            array_push($views, $origin);

            return array_reverse($views);

        } else {

            return [];
        }
    }

    protected function getUsedParams($file)
    {
        $used = [];
        $final = [];

        foreach ($file as $line) {

            $single = $this->get_string_between($line, '{{ $', ' }}');
            $singleTrimmed = $this->get_string_between($line, '{{$', '}}');
            $object = $this->get_string_between($line, '{{ $', '->');
            $objectTrimmed = $this->get_string_between($line, '{{$', '->');
            $array = $this->get_string_between($line, '{{ $', '[');
            $arrayTrimmed = $this->get_string_between($line, '{{$', '[');
            $raw = $this->get_string_between($line, '$', ')');
            $raw2 = $this->get_string_between($line, '$', ';');

            if (!empty($single)) array_push($used, $single);
            if (!empty($singleTrimmed)) array_push($used, $singleTrimmed);
            if (!empty($object)) array_push($used, $object);
            if (!empty($objectTrimmed)) array_push($used, $objectTrimmed);
            if (!empty($array)) array_push($used, $array);
            if (!empty($arrayTrimmed)) array_push($used, $arrayTrimmed);
            if (!empty($raw)) array_push($used, $raw);
            if (!empty($raw2)) array_push($used, $raw2);
        }

        foreach ($used as $use) {
            foreach ($use as $u) {
                array_push($final, $u);
            }
        }

        return $final;
    }

    protected function get_string_between($str, $startDelimiter, $endDelimiter)
    {
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;

        while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {

            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($str, $endDelimiter, $contentStart);

            if (false === $contentEnd) { break; }

            $trimmed = trim(substr($str, $contentStart, $contentEnd - $contentStart));
            $contents[] = $trimmed;
            $startFrom = $contentEnd + $endDelimiterLength;
        }

        return $contents;
    }

    protected function subViewsParser($originalFile, $params, $root, $resources)
    {
        $views = [];

        // Extends files

        foreach ($originalFile as $line) {

            $extends = $this->get_string_between($line, '@extends(', ')');
            if (!empty($extends)) array_push($views, $extends);
        }

        $analyse = $this->seekIncludes($originalFile);
        if (!empty($analyse)) {
            foreach ($analyse as $an) {
                if (isset($an[0]) && $an[0] != '') {

                    $trimmed = trim(
                        str_replace(
                            '"', '', str_replace(
                                '\'', '', $an[0]
                            )
                        )
                    );

                    if (!in_array($trimmed, $this->controller)) {

                        array_push($views, $an);
                        array_push($this->controller, $trimmed);
                    }
                }
            }
        }

        if (!empty($views)) {
            foreach ($views as $lists) {
                foreach ($lists as $list) {

                    $filePaths = ROOTDIR . $resources . '/' . trim(str_replace('\'', '', str_replace('"', '',
                            str_replace('.', '/', $list)))) . '.blade.php';

                    $file = file($filePaths);
                    $this->subViewsParser($file, $params, ROOTDIR . $resources, $resources);
                }
            }

            $all = $this->collectViews($views, $root, $params);
            foreach ($all as $a) {
                if (!empty($a)) {
                    array_push($views, $a);
                }
            }
        }

        return $views;
    }

    protected function includes($mode, $data)
    {
        $final = [];
        if (!empty($data)) {
            switch ($mode) {

                case 'include':

                    foreach ($data as $file) {
                        array_push($final, $file);
                    }
                    break;

                case 'boolean':

                    foreach ($data as $file) {
                        $split = explode(',', $file)[1];
                        array_push($final, $split);
                    }
                    break;

                case 'first':

                    foreach ($data as $file) {
                        $split = (isset(explode(',', $file)[0])) ? explode(',', $file)[0] : $file;
                        $vars = trim(str_replace(
                            'array(', '', str_replace(
                                ')', '', str_replace(
                                    '[', '', str_replace(
                                        ']', '', str_replace(
                                            '\'', '', str_replace(
                                                '"', '', $split
                                            )
                                        )
                                    )
                                )
                            )
                        ));
                        $explode = explode(',', $vars);
                        foreach ($explode as $item) {
                            $clean = trim($item);
                            array_push($final, $clean);
                        }
                    }
                    break;
            }
        }

        return $final;
    }

    protected function collectViews($views, $root, $params)
    {
        $final = [];
        foreach ($views as $view) {
            foreach ($view as $vars) {

                $vars = trim(str_replace($root, '', $vars));
                $vars = trim(str_replace('\'', '', $vars));
                $vars = trim(str_replace('"', '', $vars));
                $subFile = file($root . '/' . str_replace($root, '', str_replace(ROOTDIR, '', str_replace('.', '/', $vars))) . '.blade.php');

                $sub = [
                    'name' => str_replace('/', '.', $vars),
                    'view' => str_replace($root, '', str_replace(ROOTDIR, '', str_replace('.', '/', $vars))),
                    'paths' => str_replace(ROOTDIR, '', $root) . '/' . $vars . '.blade.php',
                    'params' => $this->getUsedParams($subFile),
                    'allParams' => $params
                ];

                array_push($this->finalArray, $sub);
                array_push($final, $sub);
            }
        }

        return $this->finalArray;
    }

    protected function seekIncludes($newFile)
    {
        $views = [];
        foreach ($newFile as $line) {

            //====================================================

            // @include('view.name') directive parser
            $include = $this->includes(
                'include',
                $this->get_string_between($line, '@include(', ')')
            );
            if (!empty($include)) array_push($views, $include);

            // @include('view.name', ['some' => 'data']) directive parser
            $includeWithData = $this->includes(
                'include',
                $this->get_string_between($line, '@include(', ',')
            );
            if (!empty($includeWithData)) array_push($views, $includeWithData);

            //====================================================

            // @includeIf('view.name') directive parser
            $includeIf = $this->includes(
                'include',
                $this->get_string_between($line, '@includeIf(', ')')
            );
            if (!empty($includeIf)) array_push($views, $includeIf);

            // @includeIf('view.name', ['some' => 'data']) directive parser
            $includeIfWithData = $this->includes(
                'include',
                $this->get_string_between($line, '@includeIf(', ',')
            );
            if (!empty($includeIf)) array_push($views, $includeIfWithData);

            //====================================================

            // @includeWhen($boolean, 'view.name') directive parser
            $includeWhen = $this->includes(
                'boolean',
                $this->get_string_between($line, '@includeWhen(', ')')
            );
            if (!empty($includeWhen)) array_push($views, $includeWhen);

            // @includeWhen($boolean, 'view.name', ['some' => 'data']) directive parser
            $includeWhenWithData = $this->includes(
                'boolean',
                $this->get_string_between($line, '@includeWhen(', ')')
            );
            if (!empty($includeWhenWithData)) array_push($views, $includeWhenWithData);

            //====================================================

            // @includeUnless($boolean, 'view.name') directive parser
            $includeUnless = $this->includes(
                'boolean',
                $this->get_string_between($line, '@includeUnless(', ')')
            );
            if (!empty($includeUnless)) array_push($views, $includeUnless);

            // @includeUnless($boolean, 'view.name', ['some' => 'data']) directive parser
            $includeUnlessWithData = $this->includes(
                'boolean',
                $this->get_string_between($line, '@includeUnless(', ')')
            );
            if (!empty($includeUnlessWithData)) array_push($views, $includeUnlessWithData);

            //====================================================

            // @includeFirst(['custom.admin', 'admin']) directive parser
            $includeFirst = $this->includes(
                'first',
                $this->get_string_between($line, '@includeFirst(', ')')
            );
            if (!empty($includeFirst)) array_push($views, $includeFirst);

            // @includeFirst(['custom.admin', 'admin'], ['some' => 'data']) directive parser
            $includeFirstWithData = $this->includes(
                'first',
                $this->get_string_between($line, '@includeFirst(', ',')
            );
            if (!empty($includeFirstWithData)) array_push($views, $includeFirstWithData);
        }

        return $views;
    }
}