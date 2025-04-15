<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\RelationManagers;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $modelLabel = 'Membro da Equipe';
    protected static ?string $pluralModelLabel = 'Equipe';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Membro')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('title')
                            ->label('Cargo/Função')
                            ->required()
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Foto')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Foto do Membro')
                            ->image()
                            ->required()
                            ->directory('team'),
                        Forms\Components\TextInput::make('image_alt')
                            ->label('Texto Alternativo da Imagem')
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Redes Sociais')
                    ->schema([
                        Forms\Components\TextInput::make('social_twitter')
                            ->label('URL do Twitter')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('social_linkedin')
                            ->label('URL do LinkedIn')
                            ->url()
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Configurações')
                    ->schema([
                        Forms\Components\Toggle::make('active')
                            ->label('Ativo')
                            ->default(true),
                        Forms\Components\TextInput::make('order')
                            ->label('Ordem')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Cargo/Função'),
                Tables\Columns\TextColumn::make('order')
                    ->label('Ordem')
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order');
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
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
