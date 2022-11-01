<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 12/30/2016
 * Time: 11:38 PM
 */

namespace Ensue\NicoSystem\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Ensue\NicoSystem\Foundation\NicoResponseTraits;

/**
 * Class BaseController
 * @package Ensue\NicoSystem\Controllers
 */
abstract class BaseController extends Controller
{
    use NicoResponseTraits, AuthorizesRequests, DispatchesJobs, ValidatesRequests;

}
