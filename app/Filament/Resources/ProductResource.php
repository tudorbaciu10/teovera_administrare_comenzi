<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $modelLabel = 'Produs';

    protected static ?string $pluralModelLabel = 'Produse';

    protected static ?int $navigationSort = 3;

    public static function getNavigationIcon(): string|\BackedEnum|\Illuminate\Contracts\Support\Htmlable|null
    {
        return 'heroicon-o-cube';
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'Catalog';
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
            TextInput::make('nume')
                ->label('Nume produs')
                ->required()
                ->maxLength(255),

            Select::make('category_id')
                ->label('Categorie')
                ->relationship('category', 'nume')
                ->required()
                ->preload(),

            Select::make('unitate')
                ->label('Unitate măsură')
                ->options(['kg' => 'kg', 'buc' => 'buc'])
                ->required()
                ->default('kg'),

            Toggle::make('activ')
                ->label('Activ')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.nume')
                    ->label('Categorie')
                    ->badge()
                    ->color(fn (Product $record): string => match ($record->category_id) {
                        1 => 'success',
                        2 => 'info',
                        3 => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('nume')
                    ->label('Produs')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('unitate')
                    ->label('Unitate')
                    ->badge()
                    ->color('gray'),

                ToggleColumn::make('activ')
                    ->label('Activ'),
            ])
            ->defaultSort('category_id')
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Categorie')
                    ->options(fn () => Category::orderBy('ordine_sortare')->pluck('nume', 'id')),

                Filter::make('activ')
                    ->label('Doar activi')
                    ->query(fn (Builder $query) => $query->where('activ', true)),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ])
            ->striped();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
