<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \DB::listen(function ($query) {
            // $query->sql
            // $query->bindings
            // $query->time

            $bindings = $query->bindings;
            $time = $query->time;

            $process = true;

            // SQL 문을 남기지 않을 경우 처리
            if ($process) {
                $data = compact('bindings', 'time');

                if (strpos($query->sql, ' [sessions] ', 0) !== false) {
                    $log = new Logger('sql');
                    $log->pushHandler(new StreamHandler(storage_path() . '/logs/sql-session-' . date('Y-m-d') . '.log', \Monolog\Logger::INFO));

                    // add records to the log
                    $log->info($query->sql, $data);
                } elseif ((stripos($query->sql, ' [payment_request] ') !== false) || (stripos($query->sql, ' [payment_request_detail] ') !== false)) {
                    $staffid = (auth()->check()) ? "id_" . auth()->user()->staffid . "___" . auth()->user()->name : "NO_LOGIN";
                    $log = new Logger($staffid);
                    if (strpos($query->sql, 'select ', 0) !== false) {
                        $log->pushHandler(new StreamHandler(storage_path() . '/logs/sql-pr-select-' . date('Y-m-d_H') . '.log', \Monolog\Logger::INFO));
                    } else {
                        $log->pushHandler(new StreamHandler(storage_path() . '/logs/sql-pr-' . date('Y-m-d') . '.log', \Monolog\Logger::INFO));
                    }
                    $log->info($query->sql, $data);

                } elseif (stripos($query->sql, ' [adminpermit] ', 0) !== false) {
                    $log = new Logger('sql');
                    $log->pushHandler(new StreamHandler(storage_path() . '/logs/sql-adminpermit-' . date('Y-m-d') . '.log', \Monolog\Logger::INFO));

                    // add records to the log
                    $log->info($query->sql, $data);

                } elseif (strpos($query->sql, 'select ', 0) !== false) {
                    $log = new Logger('sql');
                    $log->pushHandler(new StreamHandler(storage_path() . '/logs/sql-select-' . date('Y-m-d_H') . '.log', \Monolog\Logger::INFO));

                    // add records to the log
                    $log->info($query->sql, $data);
                } else {
                    $staffid = (auth()->check()) ? "id_" . auth()->user()->staffid . "___" . auth()->user()->name : "NO_LOGIN";
                    $log = new Logger($staffid);
                    $log->pushHandler(new StreamHandler(storage_path() . '/logs/sql-' . date('Y-m-d_H') . '.log', \Monolog\Logger::INFO));

                    // add records to the log
                    $log->info($query->sql, $data);

                }
            }

        });
    }
}
