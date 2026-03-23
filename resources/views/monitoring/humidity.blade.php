<x-app-layout>
    <x-page-header title="{{ __('Humidity Monitoring') }}" description="Environmental humidity levels and sensor status.">
        <x-button variant="primary" size="sm">
            <i class="fa fa-download mr-2"></i> Export Data
        </x-button>
    </x-page-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-6 border border-gray-100">
                <div class="flex items-center justify-center py-20 text-center">
                    <div>
                        <div class="w-20 h-20 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">
                            <i class="fa fa-tint"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Humidity Dashboard</h3>
                        <p class="text-gray-500 max-w-sm mx-auto">Environmental tracking is coming soon. Sensor integration is in progress.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
