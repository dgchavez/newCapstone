<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class DashboardCharts extends Component
{
    public function initCharts()
    {
        $this->dispatch('initCharts');
    }

    public function render()
    {
        return view('livewire.admin.dashboard-charts');
    }
} 