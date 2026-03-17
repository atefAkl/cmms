<x-app-layout>
    <div class="mb-6 flex justify-between items-center px-4 sm:px-0">
        <div>
            <h1 class="text-2xl font-black text-gray-900">New Purchase Record</h1>
            <p class="text-gray-500 text-sm">Initialize a procurement header for spare parts and supplies.</p>
        </div>
        <a href="{{ route('procurement.index') }}" class="p-2 px-6 bg-gray-100 border-2 border-transparent font-black text-black rounded-lg hover:border-gray-300 transition uppercase tracking-widest text-xs">
            Back to List
        </a>
    </div>

    <div class="max-w-4xl">
        <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-100">
            <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-gray-900 tracking-tight">Header Information</h3>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Step 1 of procurement workflow</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-black text-sm">1</div>
            </div>

            <form action="{{ route('procurement.store') }}" method="POST" class="p-10 space-y-10">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3">Supplier / Provider</label>
                        <select name="supplier_id" class="mt-1 block w-full border-gray-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 text-sm font-semibold">
                            <option value="">Unknown / Walk-in / Miscellaneous</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3">Destination Warehouse</label>
                        <select name="warehouse_id" required class="mt-1 block w-full border-gray-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 text-sm font-semibold">
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3">Transaction Date</label>
                        <input name="transaction_date" type="date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full py-3 border-gray-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-semibold" required />
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3">Reference / Invoice #</label>
                        <input name="reference_number" type="text" placeholder="e.g. INV-2026-X" class="mt-1 block w-full py-3 border-gray-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-semibold" />
                    </div>
                </div>

                <div class="bg-indigo-50/50 p-6 rounded-2xl border border-indigo-100">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-4">Payment Strategy</label>
                    <div class="flex gap-8">
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="radio" name="payment_status" value="unpaid" checked class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-indigo-600 transition tracking-tight">Unpaid / Credit Account</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="radio" name="payment_status" value="paid" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-indigo-600 transition tracking-tight">Paid Full / Cash Basis</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3">Internal Notes & Context</label>
                    <textarea name="notes" placeholder="Reason for purchase, terms, etc..." class="mt-1 block w-full border-gray-200 rounded-xl shadow-sm min-h-[120px] p-4 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium"></textarea>
                </div>

                <div class="flex items-center justify-between pt-8 border-t border-gray-100">
                    <x-secondary-button :href="route('procurement.index')" class="!py-4 !px-8 !rounded-xl text-xs uppercase font-black">
                        Discard Draft
                    </x-secondary-button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 px-12 rounded-xl shadow-xl shadow-indigo-200 transition transform hover:-translate-y-0.5 active:translate-y-0 active:scale-95 flex items-center uppercase text-xs tracking-widest">
                        Save & Continue to Items
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

