<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\DateTime;


class PaymentProvider extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\PaymentProvider::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var  array
     */
    public static $search = [
        'id', 'title'
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return __('Payment Providers');
    }

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Finances';

    /**
    * Get the displayable singular label of the resource.
    *
    * @return  string
    */
    public static function singularLabel()
    {
        return __('Payment Provider');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function fields(Request $request)
    {
        return [
            ID::make( __('Id'),  'id')
            ->rules('required')
            ->sortable(),
            Text::make( __('Title'),  'title')
            ->sortable(),
            Number::make( __('Withdrawal Rate'),  'withdrawal_rate')->step(0.01)
            ->sortable(),
            Number::make( __('Deposit Rate'),  'deposit_rate')->step(0.01)
            ->sortable(),
            Number::make( __('Withdrawal Fixed  Rate'),  'withdrawal_fixed_rate')->step(0.01)
            ->sortable(),
            Number::make( __('Deposit Fixed Rate'),  'deposit_fixed_rate')->step(0.01)
            ->sortable(),
            Number::make( __('Api Key'),  'api_key')->step(0.01)
            ->sortable(),
            Number::make( __('Api Secret'),  'api_secret')->step(0.01)
            ->sortable(),
            Number::make( __('Merchant Id'),  'merchant_id')->step(0.01)
            ->sortable(),
            Number::make( __('Merchank Key'),  'merchank_key')->step(0.01)
            ->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
