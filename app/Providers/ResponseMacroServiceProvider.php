<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @internal param ResponseFactory $factory
     */
    public function boot()
    {
        Response::macro('success', function ($data) {
            return Response::json(['error_code' => 0, 'data' => $data]);
        });

        Response::macro('success_list', function ($data, $total, $page, $size) {
            return Response::json([
                'error_code' => 0,
                'data' => [
                    'list' => $data,
                    'total' => $total,
                    'page' => $page,
                    'total_page' => $total % $size ? intval($total / $size + 1) : intval($total / $size),
                    'size' => $size,
                ],
            ]);
        });

        Response::macro('error', function ($error_code, $error_message = null, $status = 200, $sprintf = null) {
//            $error_message = $error_message ? trans('errors.' . $error_message) : trans('errors.Undefined Error');
//            if ($sprintf) {
//                $error_message = sprintf($error_message, $sprintf);
//            }

            return Response::json(['error_code' => $error_code, 'error_message' => $error_message], $status);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
