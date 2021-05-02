<?php

namespace Differ\Formatters\Json;

function getJson(array $diffTree): string
{
    return json_encode((object) $diffTree);
}
