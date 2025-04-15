<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TalkResource\Pages;
use App\Filament\Resources\TalkResource\RelationManagers;
use App\Models\Talk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $modelLabel = 'Palestra';
    protected static ?string $pluralModelLabel = 'Palestras';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações da Palestra')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Descrição')
                            ->required()
                            ->rows(3),
                        Forms\Components\TextInput::make('author')
                            ->label('Palestrante')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('duration')
                            ->label('Duração (MM:SS)')
                            ->required()
                            ->regex('/^[0-9]{1,2}:[0-9]{2}$/')
                            ->placeholder('25:30')
                            ->helperText('Formato: minutos:segundos (MM:SS)'),
                        Forms\Components\TextInput::make('video_url')
                            ->label('URL do Vídeo')
                            ->required()
                            ->url()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date')
                            ->label('Data da Publicação')
                            ->required(),
                        Forms\Components\TextInput::make('views')
                            ->label('Visualizações')
                            ->default('0 visualizações')
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Imagem')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Thumbnail')
                            ->image()
                            ->required()
                            ->directory('talks'),
                        Forms\Components\TextInput::make('image_alt')
                            ->label('Texto Alternativo da Imagem')
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Status')
                    ->schema([
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
                    ->searchable()
                    ->limit(50),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Thumbnail'),
                Tables\Columns\TextColumn::make('author')
                    ->label('Palestrante')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duração'),
                Tables\Columns\TextColumn::make('views')
                    ->label('Visualizações'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_video')
                    ->label('Ver Vídeo')
                    ->icon('heroicon-o-play')
                    ->url(fn(Talk $record): string => $record->video_url)
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListTalks::route('/'),
            'create' => Pages\CreateTalk::route('/create'),
            'edit' => Pages\EditTalk::route('/{record}/edit'),
        ];
    }
}
