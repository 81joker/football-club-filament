<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\OrderStatusEnum;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use Filament\Forms\Components\Repeater;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Shop';

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::where('status', '=', 'processing')->count();
    // }

    // public static function getNavigationBadgeColor(): ?string
    // {
    //     return static::getModel()::where('status', '=', 'processing')->count() > 10
    //         ? 'warning'
    //         : 'primary';
    // }
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Order Details')
                        ->schema([
                            Forms\Components\TextInput::make('number')
                                ->default('ORD-' . random_int(100000, 999999))
                                // ->default('ORD-' . mt_rand(1000, 9999))
                                ->disabled()
                                ->dehydrated(),

                            Forms\Components\Select::make('customer_id')
                                ->relationship('customer', 'name')
                                ->required(),

                            Forms\Components\Select::make('type')
                                ->options([
                                    'pending' => OrderStatusEnum::PENDING->value,
                                    'processing' => OrderStatusEnum::PROCESSING->value,
                                    'completed' => OrderStatusEnum::COMPLETED->value,
                                    'cancelled' => OrderStatusEnum::DECLINED->value,
                                ])->columnSpanFull()->required(),

                            Forms\Components\MarkdownEditor::make('notes')
                                ->columnSpanFull()
                               ,

                    ])->columns(2),

                    Forms\Components\Wizard\Step::make('Order Items')
                        ->schema([
                            Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Product')
                                    ->options(Product::query()->pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                                    $set('unit_price', Product::find($state)?->price ?? 0)),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->live()
                                    ->dehydrated()
                                    ->default(1)
                                    ->required(),

                                Forms\Components\TextInput::make('unit_price')
                                    ->label('Unit Price')
                                    ->disabled()
                                    ->dehydrated()
                                    ->numeric()
                                    ->required(),

                            //     Forms\Components\Placeholder::make('total_price')
                            //         ->label('Total Price')
                            //         ->content(function ($get) {
                            //             return $get('quantity') * $get('unit_price');
                            //         })
                            // ])->columns(4)
                            Forms\Components\Placeholder::make('total_price')
                            ->label('Total Price')
                            ->content(function ($get) {
                                return number_format($get('quantity') * $get('unit_price'), 2);
                            })
                    ])
                    ->columns(4)
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $total = collect($state)->sum(fn ($item) => 
                            ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0)
                        );
                        $set('total_price', $total);
                    }),
                    
                Forms\Components\Hidden::make('total_price')
                    // ->numeric()
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                        ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('customer.name')
                ->searchable()
                ->sortable()
                ->toggleable(),

            Tables\Columns\TextColumn::make('status')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Order Date')
                ->date(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
