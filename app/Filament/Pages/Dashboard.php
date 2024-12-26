<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    public function mount(): void
    {
        $this->widgets = $this->getUserWidgets();
    }

    protected function getUserWidgets(): array
    {
        $user = Auth::user();
        $widgets = $user->dashboard_widgets ?? [];

        return array_map(function ($widget) {
            return Widget::make($widget['name'], $widget['data']);
        }, $widgets);
    }

    public function saveUserWidgets(array $widgets): void
    {
        $user = Auth::user();
        $user->dashboard_widgets = $widgets;
        $user->save();
    }

    public function addWidget(string $name, array $data): void
    {
        $widgets = $this->getUserWidgets();
        $widgets[] = ['name' => $name, 'data' => $data];
        $this->saveUserWidgets($widgets);
    }

    public function removeWidget(string $name): void
    {
        $widgets = array_filter($this->getUserWidgets(), function ($widget) use ($name) {
            return $widget['name'] !== $name;
        });
        $this->saveUserWidgets($widgets);
    }

    public function rearrangeWidgets(array $widgets): void
    {
        $this->saveUserWidgets($widgets);
    }
}
