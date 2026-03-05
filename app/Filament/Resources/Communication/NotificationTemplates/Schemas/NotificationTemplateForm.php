<?php

namespace App\Filament\Resources\Communication\NotificationTemplates\Schemas;

use Filament\Schemas\Schema;

class NotificationTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Template Details')->schema([
                    \Filament\Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    \Filament\Forms\Components\Select::make('trigger_event')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->options([
                            'user.registered' => 'New User Registered',
                            'enrollment.enrolled' => 'New Course Enrollment',
                            'enrollment.completed' => 'Course Completed',
                            'donation.received' => 'Donation Received',
                            'sponsorship.started' => 'Sponsorship Activated',
                            'event.registered' => 'Event Registration',
                            'assessment.submitted' => 'Assessment Submitted',
                            'assessment.graded' => 'Assessment Graded',
                            'contact.created' => 'New CRM Contact Created',
                        ])
                        ->searchable()
                        ->helperText('Select the system event that triggers this notification.'),
                    \Filament\Forms\Components\TextInput::make('subject')
                        ->required()
                        ->maxLength(255),
                    \Filament\Forms\Components\RichEditor::make('body')
                        ->required()
                        ->columnSpanFull()
                        ->helperText('You can use dynamic tags like [FIRST_NAME], [LAST_NAME], [COURSE_TITLE].'),
                    \Filament\Forms\Components\Textarea::make('description')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                    \Filament\Forms\Components\Toggle::make('is_active')
                        ->default(true),
                ])->columns(2),
            ]);
    }
}
