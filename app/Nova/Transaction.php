<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;


class Transaction extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Transaction::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'id';

     /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Finances';

    /**
     * The columns that should be searched.
     *
     * @var  array
     */
    public static $search = [
        'id', 'type', 'account_id', 'amount'
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return __('Transactions');
    }

    /**
    * Get the displayable singular label of the resource.
    *
    * @return  string
    */
    public static function singularLabel()
    {
        return __('Transaction');
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
                Number::make( __('Amount'),  'amount')
                ->sortable(),
                BelongsTo::make('Account')
                ->searchable()
                ->sortable(),
                Select::make( __('Status'),  'status')
                ->sortable()
                ->options([
                    'pending' => 'Pending',
                    'failed' => 'Failed',
                    'success' => 'Success',
                ])->displayUsingLabels(),
                Select::make( __('Type'),  'type')
                ->sortable()
                ->options([
                    'deposit' => 'Deposit',
                    'withdrawal' => 'Withdrawal',
                    'transfer' => 'Transfer',
                ])->displayUsingLabels(),
                Select::make( __('Payment Method'),  'payment_method')
                ->sortable()
                ->options([
                    'paypal' => 'PayPal',
                    'bank' => 'Bank',
                    'momo' => 'Mobile Money',
                ])->displayUsingLabels(),
                Textarea::make( __('Description'),  'description')
                ->sortable(),
                Text::make( __('Transaction ID'), 'transaction_id')
                ->onlyOnDetail(),
                Text::make( __('Pay Batch ID'), 'batch_id')
                ->onlyOnDetail(),
                Text::make( __('PayPal Status'), 'paypal_status')
                ->onlyOnDetail(),
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
