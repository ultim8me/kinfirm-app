<?php

namespace App\Http\Requests;

use App\DataTransferObjects\AbstractDto;

interface RequestDtoInterface
{
    public function getDTO(): AbstractDto;
}
