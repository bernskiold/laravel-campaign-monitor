<?php

use Bernskiold\LaravelCampaignMonitor\Tests\TestCase;

/**
 * The Campaign Monitor SDK only autoloads its resource classes, which in turn
 * load the base classes such as CS_REST_Wrapper_Result. Touching one of them
 * up front makes sure the base classes are available in every test, also
 * when a single test file is run in isolation.
 */
class_exists(CS_REST_Subscribers::class);

uses(TestCase::class)->in(__DIR__);
