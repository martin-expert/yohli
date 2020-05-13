<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Place;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphMany;
use DigitalCloud\NovaResourceNotes\Fields\Notes;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Benjacho\BelongsToManyField\BelongsToManyField;


class Job extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Job::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'title';


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
        'id', 'title'
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return __('Jobs');
    }

    /**
    * Get the displayable singular label of the resource.
    *
    * @return  string
    */
    public static function singularLabel()
    {
        return __('Job');
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
            Files::make('Supporting Document', 'project_files')
            ->customProperties([
                'type' => 'project files',
            ]),
            BelongsTo::make('Hirer', 'owner', 'App\Nova\User')
            ->searchable()
            ->sortable(),  
            BelongsTo::make('Freelancer', 'profile', 'App\Nova\Profile')
            ->searchable()
            ->nullable()
            ->sortable(),    
            Text::make( __('Title'),  'title')
            ->sortable(),       
            Textarea::make( __('Description'),  'description'), 
            Select::make( __('Budget Type'),  'budget_type')
            ->sortable()
            ->options([
                'fixed' => 'Fixed',
                'hourly' => 'Hour',
            ]),
            Text::make( __('Max Budget'),  'max_budget')
            ->sortable(), 
            Text::make( __('Min Budget'),  'min_budget')
            ->sortable(), 
            BelongsTo::make('Industry')
            ->searchable()
            ->sortable(),
            BelongsToManyField::make('Skills', 'skills', 'App\Nova\Skill')->optionsLabel('title')->nullable()->hideFromIndex(),
            Select::make( __('Status'),  'status')
            ->sortable()
            ->options([
                'not assigned' => 'Not Assigned',
                'assigned' => 'Assigned',
                'completed' => 'Completed',
                'inactive' => 'Make inactive',
            ]),
            Select::make( __('Featured'),  'featured')
            ->sortable()
            ->options([
                'no' => 'No',
                'yes' => 'Yes',
            ])->displayUsingLabels(),

            Place::make( __('City'),  'city')
                ->sortable()
                ->onlyCities(),

            BelongsTo::make('Country')
                ->searchable()
                ->sortable(),
            Select::make( __('On Time'),  'ontime')
            ->sortable()
            ->options([
                'no' => 'No',
                'yes' => 'Yes',
            ]),
            Select::make( __('On Budget'),  'onbudget')
            ->sortable()
            ->options([
                'no' => 'No',
                'yes' => 'Yes',
            ]),

            DateTime::make(__('Created At'), 'created_at')->format('LL')
            ->sortable()
            ->exceptOnForms(),
            
            MorphMany::make('Reviews'),
            HasMany::make('Bids'),
            HasMany::make('Milestones'),
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
        return [
            new Filters\JobDateRangeFilter(),
        ];
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
        return [
            new \Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel,
        ];
    }
}
