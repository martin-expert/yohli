<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use DigitalCloud\NovaResourceNotes\Fields\Notes;


class Milestone extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Milestone::class;

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
    public static $group = 'Job';

    /**
     * The columns that should be searched.
     *
     * @var  array
     */
    public static $search = [
        'id', 'profile_id', 'activity'
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return __('Milestones');
    }

    /**
    * Get the displayable singular label of the resource.
    *
    * @return  string
    */
    public static function singularLabel()
    {
        return __('Milestone');
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
            BelongsTo::make('Profile')
            ->searchable()
            ->sortable(),
            BelongsTo::make('Job')
            ->searchable()
            ->sortable(),
            BelongsTo::make('Bid')
            ->searchable()
            ->nullable()
            ->sortable(),
            Text::make( __('Heading'),  'heading')
            ->sortable(),
            Textarea::make( __('Activity'),  'activity')
            ->sortable(),
            Number::make( __('Hours'),  'hours')
            ->sortable(),
            Number::make( __('Cost'),  'cost')
            ->sortable(),
            Select::make( __('Status'),  'status')
            ->sortable()
            ->options([
                'not done' => 'Pending',
                'started' => 'Started',
                'request changes' => 'Request Changes',
                'approved' => 'Approved',
                'done' => 'Completed',
            ])->displayUsingLabels(),
            Select::make( __('Payment'),  'is_paid')
            ->sortable()
            ->options([
                '0' => 'No',
                '1' => 'Yes',
            ])->displayUsingLabels(),
            Notes::make('Notes','notes')->onlyOnDetail(),
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
