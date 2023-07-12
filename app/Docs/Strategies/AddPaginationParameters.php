<?php

namespace App\Docs\Strategies;

use Knuckles\Camel\Extraction\ExtractedEndpointData;
use Knuckles\Scribe\Extracting\ParamHelpers;
use Knuckles\Scribe\Extracting\Strategies\Strategy;

class AddPaginationParameters extends Strategy
{
    /**
     * Trait containing some helper methods for dealing with "parameters",
     * such as generating examples and casting values to types.
     * Useful if your strategy extracts information about parameters or generates examples.
     */
    use ParamHelpers;

    /**
     * @link https://scribe.knuckles.wtf/laravel/advanced/plugins
     *
     * @param  ExtractedEndpointData  $endpointData The endpoint we are currently processing.
     *   Contains details about httpMethods, controller, method, route, url, etc, as well as already extracted data.
     * @param  array  $routeRules Array of rules for the ruleset which this route belongs to.
     *
     * See the documentation linked above for more details about writing custom strategies.
     */
    public function __invoke(ExtractedEndpointData $endpointData, array $routeRules = []): ?array
    {
        $isGetRoute = in_array('GET', $endpointData->httpMethods);
        $isIndexRoute = strpos($endpointData->route->getName(), '.index') !== false;
        if ($isGetRoute && $isIndexRoute) {
            return [
                'query' => [
                    'description' => 'Text to search for.',
                    'required' => false,
                ],
                'page' => [
                    'description' => 'Page number to return.',
                    'required' => false,
                ],
            ];
        }

        return null;
    }
}
