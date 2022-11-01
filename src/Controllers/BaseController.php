<?php

namespace Ensue\NicoSystem\Controllers;

use Ensue\NicoSystem\Foundation\NicoResponseTraits;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

/**
 * Class BaseController
 * @package Ensue\NicoSystem\Controllers
 */
abstract class BaseController extends Controller
{
    use NicoResponseTraits, AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
