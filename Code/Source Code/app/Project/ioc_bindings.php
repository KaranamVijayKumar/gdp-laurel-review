<?php
/**
 * File: bindings.php
 * Created: 08-09-2014
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

// IoC Container bindings
global $app;

$app['container'] = new \Illuminate\Container\Container();

/*
|--------------------------------------------------------------------------
| Submission preview
|--------------------------------------------------------------------------
|
| We are currently using the Box preview, if it needs changed, you just
| need to change the bindings here.
|
*/

$app['container']->bind('\Project\Services\PreviewInterface', '\Project\Services\Box\Preview');


/*
|--------------------------------------------------------------------------
| Billable interface
|--------------------------------------------------------------------------
|
| We are currently using paypal as checkout process
|
*/

$app['container']->bind(
    '\Project\Services\Billing\PaymentInterface',
    '\Project\Services\Billing\Paypal\Payment'
);

/*
|--------------------------------------------------------------------------
| Cache provider
|--------------------------------------------------------------------------
|
| Cache provider
|
*/

$app['container']->bind(
    'Project\Support\Cache\CacheProviderInterface',
    function () {
        $cache = new \Project\Support\Cache\File();
        $cache->setOptions(array('cache_path' => SP . 'storage/cache'));
        return $cache;
    }
);

/*
|--------------------------------------------------------------------------
| Exporter provider
|--------------------------------------------------------------------------
|
| Export provider
|
*/

$app['container']->singleton(
    '\Project\Services\Exporter\ExporterFactoryInterface',
    function () {
        return new \Project\Services\Exporter\ExporterFactory();
    }
);
