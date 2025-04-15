<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Filament\Resources\BannerResource\RelationManagers;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $modelLabel = 'Banner';
    protected static ?string $pluralModelLabel = 'Banners';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Banner')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Descrição')
                            ->required()
                            ->rows(3),
                        Forms\Components\TextInput::make('button_text')
                            ->label('Texto do Botão')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('button_link')
                            ->label('Link do Botão')
                            ->required()
                            ->url()
                            ->maxLength(255),
                        Forms\Components\Select::make('position')
                            ->label('Posição do Conteúdo')
                            ->options([
                                'left' => 'Esquerda',
                                'center' => 'Centro',
                                'right' => 'Direita',
                            ])
                            ->default('left')
                            ->required(),
                    ]),
                Forms\Components\Section::make('Imagens')
                    ->schema([
                        Forms\Components\FileUpload::make('mobile_image')
                            ->label('Imagem Mobile')
                            ->image()
                            ->required()
                            ->directory('banners/mobile'),
                        Forms\Components\FileUpload::make('desktop_image')
                            ->label('Imagem Desktop')
                            ->image()
                            ->required()
                            ->directory('banners/desktop'),
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
                Tables\Columns\ImageColumn::make('desktop_image')
                    ->label('Imagem')
                    ->circular(false),
                Tables\Columns\TextColumn::make('position')
                    ->label('Posição')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'left' => 'info',
                        'center' => 'primary',
                        'right' => 'success',
                    }),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('Ativo'),
                Tables\Columns\TextColumn::make('order')
                    ->label('Ordem')
                    ->sortable(),
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
