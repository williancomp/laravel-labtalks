<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $modelLabel = 'Evento';
    protected static ?string $pluralModelLabel = 'Eventos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Evento')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Descrição')
                            ->required()
                            ->rows(3),
                        Forms\Components\Select::make('event_type')
                            ->label('Tipo de Evento')
                            ->options([
                                'Workshop' => 'Workshop',
                                'Palestra' => 'Palestra',
                                'Minicurso' => 'Minicurso',
                                'Meetup' => 'Meetup',
                                'Conferência' => 'Conferência',
                                'Hackathon' => 'Hackathon'
                            ])
                            ->required(),
                        Forms\Components\DateTimePicker::make('date')
                            ->label('Data e Hora')
                            ->required()
                            ->seconds(false),
                        Forms\Components\TextInput::make('subscription_url')
                            ->label('URL de Inscrição')
                            ->url()
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Imagem')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Imagem do Evento')
                            ->image()
                            ->required()
                            ->directory('events'),
                        Forms\Components\TextInput::make('image_alt')
                            ->label('Texto Alternativo da Imagem')
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Configurações')
                    ->schema([
                        Forms\Components\Toggle::make('featured')
                            ->label('Destaque')
                            ->default(false),
                        Forms\Components\Toggle::make('active')
                            ->label('Ativo')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagem'),
                Tables\Columns\TextColumn::make('event_type')
                    ->label('Tipo')
                    ->badge(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\IconColumn::make('featured')
                    ->label('Destaque')
                    ->boolean(),
                Tables\Columns\IconColumn::make('active')
                    ->label('Ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Ativo')
                    ->placeholder('Todos')
                    ->trueLabel('Ativos')
                    ->falseLabel('Inativos'),
                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Destaque')
                    ->placeholder('Todos')
                    ->trueLabel('Destacados')
                    ->falseLabel('Não destacados'),
                Tables\Filters\SelectFilter::make('event_type')
                    ->label('Tipo de Evento')
                    ->options([
                        'Workshop' => 'Workshop',
                        'Palestra' => 'Palestra',
                        'Minicurso' => 'Minicurso',
                        'Meetup' => 'Meetup',
                        'Conferência' => 'Conferência',
                        'Hackathon' => 'Hackathon'
                    ]),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
