<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatusEnum;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = '5s';

    protected static bool $isLazy = true;


    protected function getStats(): array
    {
        return [
            Stat::make('Total Customers' , Customer::count())
            ->description('Increase in  Customers')
            ->descriptionIcon('heroicon-m-arrow-trending-up')->color('success')->chart([22, 10, 5, 5, 20, 20, 40]),



            Stat::make('Total Product', Product::count())
            ->description('Total Product in app')
            ->descriptionIcon('heroicon-m-arrow-trending-up')->color('danger')->chart([22, 10, 5, 5, 20, 20, 40]),

            Stat::make('Pending Orders', Order::where('status' , OrderStatusEnum::PENDING->value)->count())
            ->description('Increase in Orders')
            ->descriptionIcon('heroicon-m-arrow-trending-up')->color('wraing')->chart([22, 10, 5, 5, 20, 20, 40]),
        ];
    }
}
