<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Nelmio\ApiDocBundle\NelmioApiDocBundle as NelmioApiDocBundle;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
