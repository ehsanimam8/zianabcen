<?php

namespace App\Filament\Resources\CMS\Categories;

use App\Filament\Resources\CMS\Categories\Pages\CreateCategory;
use App\Filament\Resources\CMS\Categories\Pages\EditCategory;
use App\Filament\Resources\CMS\Categories\Pages\ListCategories;
use App\Filament\Resources\CMS\Categories\Schemas\CategoryForm;
use App\Filament\Resources\CMS\Categories\Tables\CategoriesTable;
use App\Models\CMS\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-folder';

    public static function getNavigationGroup(): ?string
    {
        return 'Content Management (CMS)';
    }

        public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
