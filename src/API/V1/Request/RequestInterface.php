<?php
namespace Mtr\MiniCRM\API\V1\Request;

use JsonSerializable;

interface RequestInterface extends JsonSerializable
{
    const MAX_PAGE_SIZE = 100;
}