<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Store;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Utilizator';

    protected static ?string $pluralModelLabel = 'Utilizatori';

    protected static ?int $navigationSort = 6;

    public static function getNavigationIcon(): string|\BackedEnum|\Illuminate\Contracts\Support\Htmlable|null
    {
        return 'heroicon-o-users';
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
            TextInput::make('name')
                ->label('Nume')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->maxLength(255),

            TextInput::make('password')
                ->label('Parolă')
                ->password()
                ->required(fn (string $operation): bool => $operation === 'create')
                ->dehydrated(fn (?string $state): bool => filled($state))
                ->helperText('Lăsați gol pentru a păstra parola actuală (la editare)'),

            Select::make('rol')
                ->label('Rol')
                ->options([
                    'admin'      => 'Admin',
                    'operator'   => 'Operator',
                    'vanzatoare' => 'Vânzătoare',
                ])
                ->required()
                ->live(),

            Select::make('store_id')
                ->label('Magazin')
                ->options(fn () => Store::orderBy('denumire')->pluck('denumire', 'id'))
                ->searchable()
                ->hidden(fn (Get $get): bool => $get('rol') !== 'vanzatoare')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nume')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->color('gray'),

                TextColumn::make('rol')
                    ->label('Rol')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin'      => 'danger',
                        'operator'   => 'warning',
                        'vanzatoare' => 'success',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin'      => 'Admin',
                        'operator'   => 'Operator',
                        'vanzatoare' => 'Vânzătoare',
                        default      => $state,
                    }),

                TextColumn::make('store.denumire')
                    ->label('Magazin')
                    ->color('gray')
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('rol')
                    ->label('Rol')
                    ->options([
                        'admin'      => 'Admin',
                        'operator'   => 'Operator',
                        'vanzatoare' => 'Vânzătoare',
                    ]),

                SelectFilter::make('store_id')
                    ->label('Magazin')
                    ->relationship('store', 'denumire'),
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
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
