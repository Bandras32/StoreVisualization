@extends('layouts.layout')

@section('header')
    Kimutatások és riporotok
@endsection

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="flex items-center justify-between mb-6">
        <!-- Filter Form -->
        <div class="p-4 bg-white shadow rounded-lg">
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="border rounded p-2">
                    <span class="text-gray-700">to</span>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="border rounded p-2">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Apply Filters</button>
            </form>
        </div>
    
        <!-- Tabs Navigation -->
        <div class="p-4 bg-white shadow rounded-lg flex">
            <ul class="flex border-b space-x-4">
                <li class="mr-1">
                    <a href="#storeDashboard" class="tab-link inline-block py-2 px-4 text-blue-500 hover:text-blue-800 font-semibold">Üzletek adatai</a>
                </li>
                <li class="mr-1">
                    <a href="#customerDashboard" class="tab-link inline-block py-2 px-4 text-blue-500 hover:text-blue-800 font-semibold">Vásárlók adatai</a>
                </li>
                <li class="mr-1">
                    <a href="#productDashboard" class="tab-link inline-block py-2 px-4 text-blue-500 hover:text-blue-800 font-semibold">Termékek adatai</a>
                </li>
            </ul>
        </div>
    </div>
    

    <!-- Tabs Content -->
    <div id="storeDashboard" class="tab-content">
        <div class="flex flex-col gap-4">
            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="text-gray-700 font-medium mb-4">Üzletek havi bevételei</h4>
                <canvas id="groupedBarChart"></canvas>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="text-gray-700 font-medium mb-4">Üzletek eladásai</h4>
                <canvas id="salesPerStoreChart"></canvas>
            </div>
        </div>
    </div>
    <div id="customerDashboard" class="tab-content hidden">
        <div class="flex  flex-col gap-2">
            <div class="bg-white shadow rounded-lg p-6 flex-1">
                <h4 class="text-gray-700 font-medium mb-4">Vásárlók fizetési szokásaik</h4>
                <canvas id="paymentPreferencesChart"></canvas>
            </div>
    
            <div class="bg-white shadow rounded-lg p-6 flex-1">
                <h4 class="text-gray-700 font-medium mb-4">Top Vásárlók</h4>
                <canvas id="topCustomersBarChart"></canvas>
            </div>
        </div>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-4">
           
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vásárló neve</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vásárolt termék</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Összes vásárlás</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($topProductsPerCustomer as $data)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $data->CustomerName }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $data->ProductName }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($data->TotalQuantity, 0, ',', ' ') }} db</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                {{ $topProductsPerCustomer->links('vendor.pagination.tailwind') }}
            </div>
        </div>
        
        
    </div>

    <div id="productDashboard" class="tab-content hidden">
        <div class="flex flex-wrap gap-4">
            <div class="bg-white shadow rounded-lg p-6 flex-1">
                <h4 class="text-gray-700 font-medium mb-4">Top Termékek</h4>
                <canvas id="salesPerProductChart"></canvas>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
<script>
    // Tab Switching Logic
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.tab-link');
        const tabContents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function (e) {
                e.preventDefault();

                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('border-b-2', 'border-blue-500', 'text-blue-800'));

                // Add active class to clicked tab
                this.classList.add('border-b-2', 'border-blue-500', 'text-blue-800');

                // Hide all tab contents
                tabContents.forEach(content => content.classList.add('hidden'));

                // Show the targeted tab content
                const target = document.querySelector(this.getAttribute('href'));
                target.classList.remove('hidden');
            });
        });
    });

    // Data for Charts
    
    const salesPerStoreLabels = {!! json_encode($salesPerStoreLabels) !!};
    const salesPerStoreData = {!! json_encode($salesPerStoreData) !!};
    const salesPerProductLabels = {!! json_encode($salesPerProductLabels) !!};
    const salesPerProductData = {!! json_encode($salesPerProductData) !!};
    const monthlyRevenues = {!! json_encode($monthlyRevenues) !!};



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



    new Chart(document.getElementById('salesPerProductChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: salesPerProductLabels.slice(0, 10),
            datasets: [{
                label: 'Eladások per termék',
                data: salesPerProductData.slice(0, 10),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    const groupedMonths = {!! json_encode($allMonths) !!};

    const groupedDatasets = {!! json_encode($groupedDatasets) !!};

    new Chart(document.getElementById('groupedBarChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: groupedMonths, // Months
            datasets: groupedDatasets, // Grouped datasets for stores
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                x: {
                    stacked: false,
                },
                y: {
                    stacked: false,
                    title: {
                        display: true,
                        text: 'Bevétel (Ft)',
                    },
                },
            },
        },
    });
    const paymentLabels = {!! json_encode($paymentPreferences->pluck('payment_type')) !!};
    const paymentData = {!! json_encode($paymentPreferences->pluck('TotalPurchases')) !!};

        
        new Chart(document.getElementById('paymentPreferencesChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: paymentLabels, 
                datasets: [{
                    label: 'Vásárlások száma',
                    data: paymentData, 
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)', 
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(255, 206, 86, 0.5)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
            },
        });
const customerLabels = {!! json_encode($topCustomers->pluck('Name')) !!};
const customerData = {!! json_encode($topCustomers->pluck('TotalSpent')) !!};

new Chart(document.getElementById('topCustomersBarChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: customerLabels,
        datasets: [{
            label: 'Vásárlók',
            data: customerData,
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Költött összeg'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Vásárlók'
                }
            }
        }
    }
});

</script>
@endsection

