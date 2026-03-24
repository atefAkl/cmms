<x-app-layout>
    <x-page-header title="سند تحويل بين المخازن">
        <div class="flex space-x-3 rtl:space-x-reverse">
            <a href="{{ route('inventory.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-black text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition shadow-sm">
                إلغاء
            </a>
            <button type="submit" form="transfer-form" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-black text-xs text-white uppercase tracking-widest hover:bg-indigo-500 transition shadow-sm">
                اعتماد التحويل
            </button>
        </div>
    </x-page-header>

    <div class="py-6 bg-gray-50/50" x-data="stockMovementForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <form id="transfer-form" action="#" method="POST">
                @csrf
                
                <!-- Document Header -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <x-label for="doc_number" value="رقم المستند" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1" />
                            <x-input id="doc_number" type="text" value="TR-{{ date('Ymd-Hi') }}" class="block w-full bg-gray-50 font-black text-slate-500" readonly />
                        </div>
                        <div>
                            <x-label for="doc_date" value="التاريخ" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1" />
                            <x-input id="doc_date" type="date" value="{{ date('Y-m-d') }}" class="block w-full font-black" required />
                        </div>
                        <div>
                            <x-label for="from_warehouse_id" value="من مخزن" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1" />
                            <select id="from_warehouse_id" x-model="from_wh" class="block w-full rounded-lg border-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-bold text-sm">
                                <option value="">اختر مخزن المصدر...</option>
                                <option value="1">المستودع الرئيسي</option>
                                <option value="2">مستودع قطع الغيار</option>
                            </select>
                        </div>
                        <div>
                            <x-label for="to_warehouse_id" value="إلى مخزن" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1" />
                            <select id="to_warehouse_id" x-model="to_wh" class="block w-full rounded-lg border-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-bold text-sm">
                                <option value="">اختر مخزن الهدف...</option>
                                <option value="1">المستودع الرئيسي</option>
                                <option value="2">مستودع قطع الغيار</option>
                            </select>
                        </div>
                    </div>
                    
                    <template x-if="from_wh && to_wh && from_wh === to_wh">
                        <div class="mt-4 p-3 bg-rose-50 border border-rose-100 rounded-lg flex items-center gap-3 text-rose-600 text-xs font-bold">
                            <i class="fa fa-exclamation-circle"></i>
                            <span>لا يمكن التحويل لنفس المخزن! يرجى اختيار مخزن مصدر وهدف مختلفين.</span>
                        </div>
                    </template>
                </div>

                <!-- Items Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 mb-8">
                    <div class="px-8 py-5 border-b border-gray-50 flex items-center justify-between">
                        <h3 class="font-black text-slate-800 border-l-4 rtl:border-l-0 rtl:border-r-4 border-indigo-500 pl-4 rtl:pr-4 uppercase tracking-widest text-sm">الأصناف المراد تحويلها</h3>
                        <button type="button" @click="addItem()" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-indigo-100 transition">
                            <i class="fa fa-plus mr-2 rtl:ml-2 rtl:mr-0"></i> إضافة صنف
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">الصنف</th>
                                    <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest w-40">الكمية</th>
                                    <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest w-40">الوحدة</th>
                                    <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">ملاحظات</th>
                                    <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest w-16"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="hover:bg-gray-50/20 transition">
                                        <td class="px-8 py-4 whitespace-nowrap">
                                            <select x-model="item.id" class="block w-full rounded-lg border-transparent bg-gray-50 focus:bg-white focus:ring-indigo-500 focus:border-indigo-500 font-bold text-sm">
                                                <option value="">اختر الصنف...</option>
                                                <option value="1">ضاغط كوبلاند 5 حصان</option>
                                                <option value="2">مروحة تبريد EBM</option>
                                            </select>
                                        </td>
                                        <td class="px-8 py-4 whitespace-nowrap">
                                            <input type="number" x-model.number="item.qty" class="block w-full rounded-lg border-transparent bg-gray-50 focus:bg-white focus:ring-indigo-500 focus:border-indigo-500 font-black text-sm text-center">
                                        </td>
                                        <td class="px-8 py-4 whitespace-nowrap text-xs font-bold text-slate-500">قطعة</td>
                                        <td class="px-8 py-4 whitespace-nowrap">
                                            <input type="text" x-model="item.note" class="block w-full rounded-lg border-transparent bg-gray-50 focus:bg-white focus:ring-indigo-500 focus:border-indigo-500 font-bold text-sm" placeholder="اختياري...">
                                        </td>
                                        <td class="px-8 py-4 whitespace-nowrap text-right">
                                            <button type="button" @click="removeItem(index)" class="text-rose-400 hover:text-rose-600 transition">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-indigo-600 p-8 rounded-2xl shadow-xl flex items-center justify-between text-white">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4 rtl:ml-4 rtl:mr-0">
                            <i class="fa fa-exchange-alt text-xl"></i>
                        </div>
                        <p class="text-sm font-bold opacity-90">سوف يتم نقل الكميات من المخزن المصدر إلى المخزن الهدف فور الاعتماد.</p>
                    </div>
                    <button type="submit" :disabled="!from_wh || !to_wh || from_wh === to_wh" class="px-8 py-3 bg-white text-indigo-600 disabled:opacity-50 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-50 transition shadow-lg">اعتماد التحويل</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function stockMovementForm() {
            return {
                from_wh: '',
                to_wh: '',
                items: [{ id: '', qty: 1, note: '' }],
                addItem() { this.items.push({ id: '', qty: 1, note: '' }); },
                removeItem(index) { if (this.items.length > 1) this.items.splice(index, 1); }
            }
        }
    </script>
</x-app-layout>
