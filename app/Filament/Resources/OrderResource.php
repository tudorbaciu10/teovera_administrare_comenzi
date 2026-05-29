<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $modelLabel = 'Comandă';

    protected static ?string $pluralModelLabel = 'Comenzi';

    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): string|\BackedEnum|\Illuminate\Contracts\Support\Htmlable|null
    {
        return 'heroicon-o-clipboard-document-list';
    }

    public static function getNavigationLabel(): string
    {
        return 'Comenzi';
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'Comenzi';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with(['store.route', 'user', 'items'])
                    ->withCount('items')
                    ->latest('data')
                    ->latest('created_at')
            )
            ->defaultGroup(
                Group::make('store.route.nume')
                    ->label('Rută')
                    ->collapsible()
            )
            ->columns([
                TextColumn::make('data')
                    ->label('Data')
                    ->date('d.m.Y')
                    ->sortable()
                    ->weight(FontWeight::SemiBold),

                TextColumn::make('store.denumire')
                    ->label('Magazin')
                    ->searchable()
                    ->weight(FontWeight::Medium),

                TextColumn::make('store.localitate')
                    ->label('Localitate')
                    ->searchable()
                    ->color('gray'),

                TextColumn::make('user.name')
                    ->label('Vânzătoare')
                    ->color('gray'),

                TextColumn::make('items_count')
                    ->label('Produse')
                    ->badge()
                    ->color('info'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'trimisa'  => 'info',
                        'printata' => 'warning',
                        'livrata'  => 'success',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'trimisa'  => 'Trimisă',
                        'printata' => 'Printată',
                        'livrata'  => 'Livrată',
                        default    => $state,
                    }),

                TextColumn::make('created_at')
                    ->label('Ora')
                    ->dateTime('H:i')
                    ->color('gray'),
            ])
            ->filters([
                Filter::make('azi')
                    ->label('Doar azi')
                    ->query(fn (Builder $query) => $query->whereDate('data', today()))
                    ->default(),

                SelectFilter::make('route')
                    ->label('Rută')
                    ->relationship('route', 'nume'),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'trimisa'  => 'Trimisă',
                        'printata' => 'Printată',
                        'livrata'  => 'Livrată',
                    ]),
            ])
            ->actions([
                Action::make('view_items')
                    ->label('Detalii')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading(fn (Order $record) => 'Comandă — ' . $record->store->denumire . ' · ' . $record->data->format('d.m.Y'))
                    ->modalContent(fn (Order $record) => view('filament.modals.order-detail', [
                        'order' => $record->load('items.product.category'),
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Închide'),

                Action::make('print')
                    ->label('Printează')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (Order $record) => route('print.order', $record))
                    ->openUrlInNewTab(),

                Action::make('mark_printed')
                    ->label('Printată ✓')
                    ->icon('heroicon-o-check')
                    ->color('warning')
                    ->visible(fn (Order $record) => $record->status === 'trimisa')
                    ->action(function (Order $record) {
                        $record->update(['status' => 'printata']);
                        Notification::make()->title('Status → Printată')->success()->send();
                    }),

                Action::make('mark_delivered')
                    ->label('Livrată ✓')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->visible(fn (Order $record) => in_array($record->status, ['trimisa', 'printata']))
                    ->action(function (Order $record) {
                        $record->update(['status' => 'livrata']);
                        Notification::make()->title('Status → Livrată')->success()->send();
                    }),

                DeleteAction::make()
                    ->visible(fn () => auth()->user()?->isAdmin() ?? false)
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkAction::make('print_selected')
                    ->label('Printează selectate')
                    ->icon('heroicon-o-printer')
                    ->action(function (Collection $records) {
                        $ids = $records->pluck('id')->join(',');
                        redirect()->route('print.orders.bulk', ['ids' => $ids]);
                    }),

                BulkAction::make('mark_printed_bulk')
                    ->label('Marchează ca printate')
                    ->icon('heroicon-o-check')
                    ->color('warning')
                    ->action(fn (Collection $records) => $records->each->update(['status' => 'printata']))
                    ->deselectRecordsAfterCompletion(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([25, 50, 100]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
        ];
    }
}
