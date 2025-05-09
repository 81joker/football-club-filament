<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use App\Enums\ProductTypeEnum;
use Faker\Core\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-s-archive-box-arrow-down';


    protected static ?string $recordTitleAttribute = 'name';

    protected static int $globalSearchResultsLimit = 20;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();

    }

    public static function getGloballySearchableAttributes(): array
{
    return ['name', 'slug', 'description' , 'brand.name'];
}

    // public static function getGlobalSearchEloquentQuery(): Builder
    // {
    //     return parent::getGlobalSearchEloquentQuery()
    //         ->with('brand');
    // }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'ID' => $record->id,
            'Brand' => $record->brand->name,
            'Price' => $record->price,
            'Description' => $record->description,
            'Quantity' => $record->quantity
        ];

    }

    protected static ?string $navigationLabel = 'Products';

    // To responsiple for the navigation group
    protected static ?string $navigationGroup = 'Shop';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([

                                // The first method is to create a slug from the name field nehad
                                // TextInput::make('name')
                                //     ->required()
                                //     ->maxLength(255)
                                //     ->live('change->dispatch("updateSlug")')
                                //     ->reactive()
                                //     ->afterStateUpdated(function (callable $set, $state) {
                                //         $set('slug', str($state)->slug());
                                //     }),
                                // TextInput::make('slug')
                                //     ->required()
                                //     ->maxLength(255)
                                //     ->disabled()
                                //     ->dehydrated(false)
                                //     ->reactive(),
                                TextInput::make('name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->maxLength(255)
                                    // ->unique()
                                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set ) {
                                        if ($operation !== 'create') {
                                            return;
                                        }
                                        $set('slug', str($state)->slug());
                                    })

                                 ,
                                TextInput::make('slug')
                                ->disabled()
                                ->dehydrated()
                                ->required()
                                ->unique( Product::class, 'slug', ignoreRecord: true)
                                  ,

                                MarkdownEditor::make('description')
                                    ->required()
                                    ->maxLength(65535)->columnSpan('full'),

                            ])->columns(2),



                            Section::make('Pricing & Inventory')
                            ->schema([
                                // sku Stock keeping unit[ unique alphanumeric code assigned to a product by a business, primarily used for inventory management and tracking.]
                                TextInput::make('sku')->label('SKU (Stock Keeping Unit)' )->required(),

                                TextInput::make('price')
                                ->numeric()
                                ->rules('regex:/^\d{1,6}+(\.\d{0,2})?$/')
                                ->label('Price')
                                ->required(),

                                // First Rules
                                // TextInput::make('quantity')->rules([
                                //     'integer',
                                //     'min:0',
                                // ])->label('Quantity')->required(),
                                // Second Rules
                                TextInput::make('quantity')
                                ->numeric()
                                 ->minValue(0)
                                 ->maxValue(1000)
                                 ->required()
                                ,


                                Select::make('type')
                                    ->options([
                                        'deliverable' => ProductTypeEnum::DELIVERABLE->value,
                                        'downloadable' => ProductTypeEnum::DOWNLOADABLE->value,
                                    ])
                                    ->required(),
                            ])->columns(2),

                    ]),
                Group::make()
                    ->schema([
                        Section::make('Status')
                            ->schema([
                                  Toggle::make('is_visible')
                                        ->label('Visible')
                                        ->helperText('Enable or disable the product visibility')
                                        ->default(true),
                                        // ->inline(false)
                                Toggle::make('is_featured')
                                        ->label('Featured')
                                        ->helperText('Enable or disable the product featured status'),

                                DatePicker::make('published_at')
                                ->label('Availibility Date')
                                ->default(now())
                                ,
                            ]),

                            Section::make('Image')
                                    ->schema([
                            Forms\Components\FileUpload::make('image')
                            ->directory('from-attachments')
                            ->preserveFilenames()
                            ->image()
                            ->imageEditor()

                            ,
                                    ])->collapsed(),

                            Section::make('Associations ')
                                    ->schema([
                                        Select::make('brand_id')
                                            ->relationship('brand', 'name')
                                            ->required()
                                            ->preload()
                                            ->searchable()
                                    ]),

                        ]),


                // Group::make()
                //     ->schema([
                //         Section::make('Pricing & Inventory')
                //             ->schema([
                //                 TextInput::make('sku'),
                //                 TextInput::make('price'),
                //                 TextInput::make('quantity'),
                //                 Select::make('type')
                //                     ->options([
                //                         'deliverable' => ProductTypeEnum::DELIVERABLE->value,
                //                         'downloadable' => ProductTypeEnum::DOWNLOADABLE->value,
                //                     ])
                //                     ->required(),
                //             ])->columns(2),
                //     ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image'),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('brand.name')->sortable()->searchable()->toggleable('Brand'),
                // IconColumn::make('is_visible')->boolean()->sortable()->searchable()->toggleable()->label('Visible'),
                IconColumn::make('is_visible')->boolean()->trueIcon('heroicon-o-eye')->falseIcon('heroicon-o-eye-slash')->sortable()->searchable()->toggleable()->label('Visible'),


                TextColumn::make('price')->sortable()->searchable(),
                TextColumn::make('quantity')->sortable()->searchable(),
                TextColumn::make('published_at')->sortable()->searchable()->date()->sortable(),
                // TextColumn::make('brand.name')
                //     ->sortable()
                //     ->searchable()
                //     ->label('Brand'),
                // IconColumn::make('is_visible')
                //     ->label('Visible')
                //     ->boolean()
                //     ->trueIcon('heroicon-o-eye')
                //     ->falseIcon('heroicon-o-eye-off')
                //     ->sortable()
                //     ->searchable(),
                TextColumn::make('type'),

            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_visible')
                ->label('Visible')
                ->boolean()
                ->trueLabel('Only Visible Products')
                ->falseLabel('Only Hidden Products')
                ->native(false),
                Tables\Filters\SelectFilter::make('brand')->relationship('brand', 'name'),



            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
