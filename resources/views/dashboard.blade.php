@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Date Range Filter Form -->
<div class="p-4 bg-white shadow rounded-lg mb-6">
    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-4">
        <div class="flex items-center space-x-2">
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="border rounded p-2">
            <span class="text-gray-700">to</span>
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="border rounded p-2">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Apply Filters</button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">
    <!-- Sales per Store Chart -->
    <div class="bg-white shadow rounded-lg p-6">
        <h4 class="text-gray-700 font-medium mb-4">Sales per Store</h4>
        <canvas id="salesPerStoreChart"></canvas>
    </div>

    <!-- Sales per Product Chart -->
    <div class="bg-white shadow rounded-lg p-6">
        <h4 class="text-gray-700 font-medium mb-4">Sales per Product</h4>
        <canvas id="salesPerProductChart"></canvas>
    </div>

    <!-- Monthly Sales Chart -->
    <div class="bg-white shadow rounded-lg p-6">
        <h4 class="text-gray-700 font-medium mb-4">Monthly Sales</h4>
        <canvas id="monthlySalesChart"></canvas>
    </div>

    <!-- Seasonal Sales for Top 3 Stores -->
    <div class="bg-white shadow rounded-lg p-6">
        <h4 class="text-gray-700 font-medium mb-4">Seasonal Sales for Top 3 Stores</h4>
        <canvas id="seasonalSalesChart"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Data for Charts
    const salesPerStoreLabels = {!! json_encode($salesPerStoreLabels) !!};
    const salesPerStoreData = {!! json_encode($salesPerStoreData) !!};
    const salesPerProductLabels = {!! json_encode($salesPerProductLabels) !!};
    const salesPerProductData = {!! json_encode($salesPerProductData) !!};
    const months = {!! json_encode($allMonths) !!};
    const monthlySales = {!! json_encode($monthlySales) !!};
    const topStoresLabels = {!! json_encode($topStoresLabels) !!};
    const seasonalSales = {!! json_encode($seasonalSales) !!};

    // Sales per Store Chart
    new Chart(document.getElementById('salesPerStoreChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: salesPerStoreLabels,
            datasets: [{
                label: 'Sales',
                data: salesPerStoreData,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Sales per Product Chart
    new Chart(document.getElementById('salesPerProductChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: salesPerProductLabels,
            datasets: [{
                data: salesPerProductData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Monthly Sales Chart
    new Chart(document.getElementById('monthlySalesChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: months, // Make sure the months array contains all months
            datasets: [{
                label: 'Monthly Sales',
                data: monthlySales, // Monthly sales data should match the months
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Seasonal Sales Chart for Top 3 Stores
    new Chart(document.getElementById('seasonalSalesChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Winter', 'Spring', 'Summer', 'Fall'],
            datasets: seasonalSales.map((store, index) => ({
                label: topStoresLabels[index],
                data: Object.values(store),
                backgroundColor: `rgba(${index * 70}, ${100 + index * 50}, ${200 - index * 50}, 0.5)`,
                borderColor: `rgba(${index * 70}, ${100 + index * 50}, ${200 - index * 50}, 1)`,
                borderWidth: 1
            }))
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
