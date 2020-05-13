<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Ampeco\Filters\DateRangeFilter;
use Laravel\Nova\Filters\DateFilter;

class JobDateRangeFilter extends DateRangeFilter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $from = Carbon::parse($value[0]);
        $to = Carbon::parse($value[1]);
        //
        return $query;
    }
}
