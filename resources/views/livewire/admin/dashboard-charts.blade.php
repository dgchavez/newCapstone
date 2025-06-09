<div>
    <!-- Charts Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" 
         wire:init="initCharts"
         wire:ignore>
        <!-- Transaction Status Distribution -->
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Transaction Status Distribution</h3>
            <canvas id="transactionStatusChart" width="400" height="300"></canvas>
        </div>

        <!-- Statistics Overview -->
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistics Overview</h3>
            <canvas id="statisticsChart" width="400" height="300"></canvas>
        </div>
    </div>
</div> 