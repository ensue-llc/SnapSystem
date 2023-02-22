<?php

namespace Ensue\Snap\Controllers;

use Ensue\Snap\Foundation\SnapResponseTraits;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

/**
 * Class BaseController
 * @package Ensue\Snap\Controllers
 */
abstract class SnapController extends Controller
{
    use SnapResponseTraits, AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
