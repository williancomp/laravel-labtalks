<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatResource\Pages;
use App\Filament\Resources\StatResource\RelationManagers;
use App\Models\Stat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatResource extends Resource
{
    protected static ?string $model = Stat::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $modelLabel = 'Estatística';
    protected static ?string $pluralModelLabel = 'Estatísticas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detalhes da Estatística')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('value')
                            ->label('Valor')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Exemplo: "50+", "2K+", etc.'),
                        Forms\Components\Select::make('icon')
                            ->label('Ícone')
                            ->options([
                                'user-group' => 'Usuários',
                                'megaphone' => 'Eventos',
                                'academic-cap' => 'Certificados',
                                'user' => 'Palestrantes',
                                'calendar' => 'Calendário',
                                'presentation-chart-bar' => 'Gráfico',
                            ])
                            ->helperText('Selecione um ícone para esta estatística'),
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
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Valor'),
                Tables\Columns\TextColumn::make('icon')
                    ->label('Ícone')
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->badge(),
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
            'index' => Pages\ListStats::route('/'),
            'create' => Pages\CreateStat::route('/create'),
            'edit' => Pages\EditStat::route('/{record}/edit'),
        ];
    }
}
