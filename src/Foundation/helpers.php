<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 12/30/2016
 * Time: 11:40 PM
 */

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;

if (!function_exists('nico_view')) {
    /**
     * @param null $view
     * @param array $data
     * @param array $mergeData
     * @param bool $nameOnly
     * @return View|Factory|string|Application
     */
    function nico_view($view = null, array $data = [], array $mergeData = [], bool $nameOnly = false): View|Factory|string|Application
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1)[0];
        $file = Arr::get($trace, 'file');
        //put the namespace according to the module used
        //windows check, replace all backslashes with forward slash
        $file = str_replace("\\", "/", $file);
        $modules = app()['config']->get('nicoSystem.module');
        preg_match('/' . $modules . '\/[a-zA-Z]+[a-zA-Z0-9]*\//', $file, $matches);
        if (!$matches) {
            return view($view, $data, $mergeData);
        }
        $match = $matches[0];
        $arr = explode('/', $match);
        $namespace = $arr[1];
        if ($nameOnly) {
            return "{$namespace}::{$view}";
        }
        return view("{$namespace}::{$view}", $data, $mergeData);
    }
}


if (!function_exists('nico_trans')) {
    /**
     * Translate the given message.
     *
     * @param string $id
     * @param array $parameters
     * @param string $locale
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    function nico_strans($id = null, $parameters = [], $locale = null): array|string|\Illuminate\Contracts\Translation\Translator|Application|null
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1)[0];
        $file = Arr::get($trace, 'file');
        //put the namespace according to the module used
        //windows check, replace all backslashes with forward slash
        $file = str_replace("\\", "/", $file);
        $modules = app()['config']->get('nicoSystem.module');
        preg_match('/' . $modules . '\/[a-zA-Z]+[a-zA-Z0-9]*\//', $file, $matches);
        $match = $matches[0];
        $arr = explode('/', $match);
        $namespace = $arr[1];
        return trans($id, $parameters, $namespace, $locale);
    }
}


if (!function_exists('filename_from_path')) {
    /**
     * @param string $path path to file
     * @param string $extra prepend/suffix string
     * @param bool $suffixed if true the extra will be suffixed
     * @return string
     */
    function filename_from_path(string $path, string $extra, bool $suffixed = true): string
    {
        $path = str_replace('\\', '/', $path);
        $pathArr = explode('/', $path);
        $filename = array_pop($pathArr);
        $filenameArr = explode('.', $filename);
        $extension = array_pop($filenameArr);
        $filename = implode('.', $filenameArr);
        if ($suffixed) {
            $filename .= $extra;
        } else {
            $filename = $extra . $filename;
        }
        $filename .= "." . $extension;
        return $filename;
    }
}

if (!function_exists('subdomain_url')) {
    /**
     * Get subdomain url for the given url
     * @param $domain
     * @param  $url
     * @param boolean $domainNameOnly If true protocal will be removed from the name
     * @return string
     */
    function subdomain_url($domain, $url, $domainNameOnly = false): string
    {
        //we can simply replace :// with ://domain.
        $str = str_replace("://", "://{$domain}.", $url);
        if ($domainNameOnly) {
            //remove https or http from the url
            $str = str_replace("https://", "", $str);
            $str = str_replace("http://", "", $str); /// If first one didn't work, this will
        }
        return $str;
    }
}
