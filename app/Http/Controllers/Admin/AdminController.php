<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class AdminController extends Controller
{
    /**
     * Create a new AdminController instance.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Return counted per day results for last 7 days
     *
     * @param   object  $model
     * @return  array with users
     */
    public function countGroupedByDay($model)
    {
        $results = $model::where('created_at', '>=', Carbon::now()->subWeek())
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get(array(
                \DB::raw('Date(created_at) as date'),
                \DB::raw('COUNT(*) as "total"')
            ));

        $results = collect($results)->keyBy('date')->map(function ($item) {
            $item->date = Carbon::parse($item->date);
            return $item;
        });

        $periods = new \DatePeriod(Carbon::now()->subDays(6), CarbonInterval::day(), Carbon::tomorrow());

        return $graph = array_map(function ($period) use ($results) {

            $month = $period->format('Y-m-d');

            return (object)[
                'date'  => $period->format('Y-m-d'),
                'total' => $results->has($month) ? $results->get($month)->total : 0,
            ];
        }, iterator_to_array($periods));
    }

    /**
     * Counting number of results for dates range
     *
     * @param   object  $model
     * @param   string  $from   -   date "YYYY-mm-dd"
     * @param   string  $to     -   date "YYYY-mm-dd"
     * @return  integer - results number
     */
    public function countPerDateRange($model, $from, $to)
    {
        return $model::where('created_at', '>=', Carbon::parse($from))
            ->where('created_at', '<=', Carbon::parse($to))
            ->count();
    }

    /**
     * Will build admin panel page pagination
     *
     * @param   object  $model
     * @return  array
     */
    public function buildPagination($model)
    {
        return [
            'pagination' => [
                'total' => $model->total(),
                'per_page' => $model->perPage(),
                'current_page' => $model->currentPage(),
                'last_page' => $model->lastPage(),
                'from' => $model->firstItem(),
                'to' => $model->lastItem()
            ],
            'data' => $model
        ];
    }

    /**
     * Clear all cache
     */
    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        \Artisan::call('config:clear');

        return "Cache is cleared";
    }

    /**
     * Cache the config
     */
    public function setConfigCache()
    {
        \Artisan::call('config:cache');

        return 'Config cached';
    }

    /**
     * Updating values in .env file
     */
    public function setEnvironmentValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $str .= "\n";
        $keyPosition = strpos($str, "{$envKey}=");
        $endOfLinePosition = strpos($str, PHP_EOL, $keyPosition);
        $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
        $str = substr($str, 0, -1);

        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }

    /**
     * Upodating env settings
     */
    public function updateSettings()
    {
        if (request()->has('app_name')) {
            $this->setEnvironmentValue('APP_NAME', request('app_name'));
        }

        if (request()->has('app_key')) {
            $this->setEnvironmentValue('APP_KEY', request('app_key'));
        }

        if (request()->has('app_url')) {
            $this->setEnvironmentValue('APP_URL', request('app_url'));
        }

        if (request()->has('mail_driver')) {
            $this->setEnvironmentValue('MAIL_DRIVER', request('mail_driver'));
        }

        if (request()->has('mail_host')) {
            $this->setEnvironmentValue('MAIL_HOST', request('mail_host'));
        }

        if (request()->has('mail_port')) {
            $this->setEnvironmentValue('MAIL_PORT', request('mail_port'));
        }

        if (request()->has('mail_username')) {
            $this->setEnvironmentValue('MAIL_USERNAME', request('mail_username'));
        }

        if (request()->has('mail_password')) {
            $this->setEnvironmentValue('MAIL_PASSWORD', request('mail_password'));
        }

        if (request()->has('mail_from_address')) {
            $this->setEnvironmentValue('MAIL_FROM_ADDRESS', request('mail_from_address'));
        }

        if (request()->has('mail_from_name')) {
            $this->setEnvironmentValue('MAIL_FROM_NAME', request('mail_from_name'));
        }

        $this->clearCache();

        return back();
    }
}
