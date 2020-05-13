<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use DigitalCloud\NovaResourceNotes\Fields\Notes;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;


class Invite extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Invite::class;

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
        'id', 'job_id', 'profile_id'
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return __('Invites');
    }

    /**
    * Get the displayable singular label of the resource.
    *
    * @return  string
    */
    public static function singularLabel()
    {
        return __('Invite');
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
                BelongsTo::make('User')
                ->searchable()
                ->sortable(),
                BelongsTo::make('Profile')
                ->searchable()
                ->sortable(),
                BelongsTo::make('Job')
                ->searchable()
                ->sortable(),
                Textarea::make( __('Message'),  'message')
                ->sortable(),
                Select::make( __('Status'),  'status')
                ->sortable()
                ->options([
                    'pending' => 'pending',
                    'accepted' => 'accepted',
                    'rejected' => 'rejected',
                ])->displayUsingLabels(),
                Files::make('Supporting Document', 'project_files')
                ->customProperties([
                    'type' => 'project files',
                ]),
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
