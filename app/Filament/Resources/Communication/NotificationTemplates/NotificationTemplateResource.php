<?php

namespace App\Filament\Resources\Communication\NotificationTemplates;

use App\Filament\Resources\Communication\NotificationTemplates\Pages\CreateNotificationTemplate;
use App\Filament\Resources\Communication\NotificationTemplates\Pages\EditNotificationTemplate;
use App\Filament\Resources\Communication\NotificationTemplates\Pages\ListNotificationTemplates;
use App\Filament\Resources\Communication\NotificationTemplates\Schemas\NotificationTemplateForm;
use App\Filament\Resources\Communication\NotificationTemplates\Tables\NotificationTemplatesTable;
use App\Models\Communication\NotificationTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NotificationTemplateResource extends Resource
{
    protected static ?string $model = NotificationTemplate::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-envelope-open';

    public static function getNavigationGroup(): ?string
    {
        return 'Communication';
    }

        public static function form(Schema $schema): Schema
    {
        return NotificationTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotificationTemplatesTable::configure($table);
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
            'index' => ListNotificationTemplates::route('/'),
            'create' => CreateNotificationTemplate::route('/create'),
            'edit' => EditNotificationTemplate::route('/{record}/edit'),
        ];
    }
}
