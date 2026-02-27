<?php

namespace App\Filament\Resources\Payment\DiscountCodes;

use App\Filament\Resources\Payment\DiscountCodes\Pages\CreateDiscountCode;
use App\Filament\Resources\Payment\DiscountCodes\Pages\EditDiscountCode;
use App\Filament\Resources\Payment\DiscountCodes\Pages\ListDiscountCodes;
use App\Filament\Resources\Payment\DiscountCodes\Schemas\DiscountCodeForm;
use App\Filament\Resources\Payment\DiscountCodes\Tables\DiscountCodesTable;
use App\Models\Payment\DiscountCode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DiscountCodeResource extends Resource
{
    protected static ?string $model = DiscountCode::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-ticket';

    public static function getNavigationGroup(): ?string
    {
        return 'Settings & Payments';
    }

        public static function form(Schema $schema): Schema
    {
        return DiscountCodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiscountCodesTable::configure($table);
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
            'index' => ListDiscountCodes::route('/'),
            'create' => CreateDiscountCode::route('/create'),
            'edit' => EditDiscountCode::route('/{record}/edit'),
        ];
    }
}
