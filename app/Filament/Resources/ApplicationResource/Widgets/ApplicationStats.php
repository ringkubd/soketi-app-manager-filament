<?php

namespace App\Filament\Resources\ApplicationResource\Widgets;

use App\Filament\Resources\ApplicationResource\Pages\ListApplications;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;

class ApplicationStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = '5s';

    protected function getTablePage(): string
    {
        return ListApplications::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Applications', $this->getPageTableQuery()->count()),
            Stat::make('Active Applications', $this->getPageTableQuery()->where('enabled', true)->count()),
            Stat::make('Inactive Applications', $this->getPageTableQuery()->where('enabled', false)->count()),
        ];
    }

    public function render()
    {
        $applications = $this->getPageTableQuery()->get();

        $chart = LivewireCharts::columnChartModel()
            ->setTitle('Applications Status')
            ->addColumn('Active', $applications->where('enabled', true)->count(), '#4CAF50')
            ->addColumn('Inactive', $applications->where('enabled', false)->count(), '#F44336');

        return view('filament.resources.application-resource.widgets.application-stats', [
            'chart' => $chart,
        ]);
    }
}
