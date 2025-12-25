<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalClients = Client::count();
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 'in_progress')->count();
        $completedProjects = Project::where('status', 'completed')->count();

        $totalEmployees = Employee::count();

        $unpaidInvoices = Invoice::where('type', 'facture')
            ->whereIn('status', ['unpaid', 'overdue'])
            ->sum('amount');

        $paidInvoices = Invoice::where('type', 'facture')
            ->where('status', 'paid')
            ->sum('amount');

        $pendingDevis = Invoice::where('type', 'devis')
            ->whereIn('status', ['draft', 'sent'])
            ->count();

        return [
            Stat::make('Total Clients', $totalClients)
                ->description('Registered clients')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success'),

            Stat::make('Active Projects', $activeProjects)
                ->description("{$completedProjects} completed, {$totalProjects} total")
                ->descriptionIcon('heroicon-o-briefcase')
                ->color('info'),

            Stat::make('Employees', $totalEmployees)
                ->description('Total staff members')
                ->descriptionIcon('heroicon-o-users')
                ->color('warning'),

            Stat::make('Unpaid Invoices', number_format($unpaidInvoices, 2) . ' MAD')
                ->description('Revenue to collect')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('danger'),

            Stat::make('Revenue (Paid)', number_format($paidInvoices, 2) . ' MAD')
                ->description('Total collected')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Pending Quotes', $pendingDevis)
                ->description('Devis awaiting response')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('gray'),
        ];
    }
}
