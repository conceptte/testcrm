<?php

namespace Mtr\MiniCRM\API\V1\Resource;

use JsonSerializable;

interface ResourceInterface extends JsonSerializable
{
    const NODE_SUCCESS = 'success';
    const NODE_DATA = 'data';
    const NODE_META_URI = 'uri';
}