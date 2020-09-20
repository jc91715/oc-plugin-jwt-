<?php

namespace Jcc\Jwt\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use Jcc\Jwt\Support\Traits\ResponseTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ResponseTrait;


}
