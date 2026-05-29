<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Models\Store;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $modelLabel = 'Magazin';

    protected static ?string $pluralModelLabel = 'Magazine';

    protected static ?int $navigationSort = 5;

    public static function getNavigationIcon(): string|\BackedEnum|\Illuminate\Contracts\Support\Htmlable|null
    {
        return 'heroicon-o-building-storefront';
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'Configurare';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('denumire')
                ->label('Denumire')
                ->required()
                ->maxLength(255),

            TextInput::make('localitate')
                ->label('Localitate')
                ->required()
                ->maxLength(255),

            TextInput::make('adresa')
                ->label('Adresă')
                ->maxLength(500),

            Select::make('route_id')
                ->label('Rută')
                ->relationship('route', 'nume')
                ->required()
                ->preload(),

            Select::make('tip')
                ->label('Tip')
                ->options(['magazin' => 'Magazin', 'angro' => 'Angro'])
                ->required()
                ->default('magazin'),

            TextInput::make('token_acces')
                ->label('Token acces (link comandă)')
                ->readOnly()
                ->dehydrated(false)
                ->visibleOn('edit')
                ->helperText('Generat automat. Copiați din tabel linkul complet.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('denumire')
                    ->label('Magazin')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('localitate')
                    ->label('Localitate')
                    ->searchable()
                    ->color('gray'),

                TextColumn::make('route.nume')
                    ->label('Rută')
                    ->badge()
                    ->color('info'),

                TextColumn::make('tip')
                    ->label('Tip')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'angro' ? 'warning' : 'success')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('token_acces')
                    ->label('Link comandă')
                    ->formatStateUsing(fn (string $state): string => route('store.order', $state))
                    ->copyable()
                    ->copyMessage('Link copiat în clipboard!')
                    ->icon('heroicon-o-clipboard')
                    ->limit(45)
                    ->tooltip(fn (Store $record): string => route('store.order', $record->token_acces)),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ])
            ->striped()
            ->paginated([25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit'   => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}
