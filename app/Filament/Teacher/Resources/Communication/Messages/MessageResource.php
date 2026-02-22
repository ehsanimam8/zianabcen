<?php

namespace App\Filament\Teacher\Resources\Communication\Messages;

use App\Filament\Teacher\Resources\Communication\Messages\Pages\CreateMessage;
use App\Filament\Teacher\Resources\Communication\Messages\Pages\EditMessage;
use App\Filament\Teacher\Resources\Communication\Messages\Pages\ListMessages;
use App\Filament\Teacher\Resources\Communication\Messages\Schemas\MessageForm;
use App\Filament\Teacher\Resources\Communication\Messages\Tables\MessagesTable;
use App\Models\Communication\Message;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'subject';

    public static function form(Schema $schema): Schema
    {
        return MessageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MessagesTable::configure($table);
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
            'index' => ListMessages::route('/'),
            'create' => CreateMessage::route('/create'),
            'edit' => EditMessage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('sender_id', auth()->id())
            ->orWhere('recipient_id', auth()->id());
    }
}
