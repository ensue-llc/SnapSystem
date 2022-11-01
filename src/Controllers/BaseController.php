<?php
namespace NicoSystem\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use NicoSystem\Foundation\NicoResponseTraits;

/**
 * Class BaseController
 * @package NicoSystem\Controllers
 */
abstract class BaseController extends Controller
{
    use NicoResponseTraits, AuthorizesRequests, DispatchesJobs, ValidatesRequests;

}
