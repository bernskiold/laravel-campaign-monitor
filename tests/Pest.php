<?php

use BernskioldMedia\LaravelCampaignMonitor\Tests\TestCase;
use Illuminate\Http\Request;

uses(TestCase::class)->in(__DIR__);

function makeIncomingJsonRequest(string $uri, array $data, string $method): Request
{
    return Request::create($uri, $method, [], [], [], [], json_encode($data));
}
