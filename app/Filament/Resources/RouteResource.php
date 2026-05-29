<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RouteResource\Pages;
use App\Models\Route;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RouteResource extends Resource
{
    protected static ?string $model = Route::class;

    protected static ?string $modelLabel = 'Rută';

    protected static ?string $pluralModelLabel = 'Rute';

    protected static ?int $navigationSort = 4;

    public static function getNavigationIcon(): string|\BackedEnum|\Illuminate\Contracts\Support\Htmlable|null
    {
        return 'heroicon-o-map';
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('stores');
    }

    public static function form(Schema $schema): Schema
    {
        $zileOptions = [
            'luni'     => 'Luni',
            'marti'    => 'Marți',
            'miercuri' => 'Miercuri',
            'joi'      => 'Joi',
            'vineri'   => 'Vineri',
            'sambata'  => 'Sâmbătă',
            'duminica' => 'Duminică',
        ];

        return $schema->components([
            TextInput::make('nume')
                ->label('Nume rută')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            CheckboxList::make('zile_livrare')
                ->label('Zile livrare')
                ->options($zileOptions)
                ->columns(4)
                ->required(),

            Select::make('zi_cutoff')
                ->label('Zi cutoff')
                ->options($zileOptions)
                ->required(),

            TimePicker::make('ora_cutoff')
                ->label('Oră cutoff')
                ->required()
                ->seconds(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        $zileLabel = [
            'luni'     => 'Lu',
            'marti'    => 'Ma',
            'miercuri' => 'Mi',
            'joi'      => 'Jo',
            'vineri'   => 'Vi',
            'sambata'  => 'Sâ',
            'duminica' => 'Du',
        ];

        $zileLabelFull = [
            'luni'     => 'Luni',
            'marti'    => 'Marți',
            'miercuri' => 'Miercuri',
            'joi'      => 'Joi',
            'vineri'   => 'Vineri',
            'sambata'  => 'Sâmbătă',
            'duminica' => 'Duminică',
        ];

        return $table
            ->columns([
                TextColumn::make('nume')
                    ->label('Rută')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('zile_livrare')
                    ->label('Zile livrare')
                    ->formatStateUsing(function ($state) use ($zileLabel) {
                        $zile = is_array($state) ? $state : (json_decode($state, true) ?? []);
                        return collect($zile)->map(fn ($z) => $zileLabel[$z] ?? $z)->join(' · ');
                    }),

                TextColumn::make('zi_cutoff')
                    ->label('Zi cutoff')
                    ->formatStateUsing(fn ($state) => $zileLabelFull[$state] ?? $state),

                TextColumn::make('ora_cutoff')
                    ->label('Oră cutoff'),

                TextColumn::make('stores_count')
                    ->label('Magazine')
                    ->badge()
                    ->color('info'),
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
            'index'  => Pages\ListRoutes::route('/'),
            'create' => Pages\CreateRoute::route('/create'),
            'edit'   => Pages\EditRoute::route('/{record}/edit'),
        ];
    }
}
