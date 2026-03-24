<x-app-layout>
    <x-page-header title="أمر إدخال بضاعة">
        <div class="flex space-x-3 rtl:space-x-reverse">
            <a href="{{ route('inventory.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-black text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition shadow-sm">
                إلغاء
            </a>
            <button type="submit" form="stock-in-form" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-black text-xs text-white uppercase tracking-widest hover:bg-blue-500 transition shadow-sm">
                اعتماد الإدخال
            </button>
        </div>
    </x-page-header>

    <div class="py-6 bg-gray-50/50" x-data="stockMovementForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <form id="stock-in-form" action="#" method="POST">
                @csrf
                
                <!-- Document Header -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <x-label for="doc_number" value="رقم المستند" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1" />
                            <x-input id="doc_number" type="text" value="IN-{{ date('Ymd-Hi') }}" class="block w-full bg-gray-50 font-black text-slate-500" readonly />
                        </div>
                        <div>
                            <x-label for="doc_date" value="التاريخ" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1" />
                            <x-input id="doc_date" type="date" value="{{ date('Y-m-d') }}" class="block w-full font-black" required />
                        </div>
                        <div>
                            <x-label for="warehouse_id" value="المخزن" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1" />
                            <select id="warehouse_id" class="block w-full rounded-lg border-gray-200 shadow-sm focus:ring-blue-500 focus:border-blue-500 font-bold text-sm">
                                <option value="">اختر المخزن...</option>
                                <option value="1">المستودع الرئيسي</option>
                                <option value="2">مستودع قطع الغيار</option>
                            </select>
                        </div>
                        <div>
                            <x-label for="reason" value="سبب الإدخال" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1" />
                            <select id="reason" class="block w-full rounded-lg border-gray-200 shadow-sm focus:ring-blue-500 focus:border-blue-500 font-bold text-sm">
                                <option value="return">مرتجع من فني</option>
                                <option value="inventory_adj">تسوية جردية (إضافة)</option>
                                <option value="initial_stock">رصيد أول المدة</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 mb-8">
                    <div class="px-8 py-5 border-b border-gray-50 flex items-center justify-between">
                        <h3 class="font-black text-slate-800 border-l-4 rtl:border-l-0 rtl:border-r-4 border-blue-500 pl-4 rtl:pr-4 uppercase tracking-widest text-sm">الأصناف المراد إدخالها</h3>
                        <button type="button" @click="addItem()" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-blue-100 transition">
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
                                            <select x-model="item.id" class="block w-full rounded-lg border-transparent bg-gray-50 focus:bg-white focus:ring-blue-500 focus:border-blue-500 font-bold text-sm">
                                                <option value="">اختر الصنف...</option>
                                                <option value="1">ضاغط كوبلاند 5 حصان</option>
                                                <option value="2">مروحة تبريد EBM</option>
                                            </select>
                                        </td>
                                        <td class="px-8 py-4 whitespace-nowrap">
                                            <input type="number" x-model.number="item.qty" class="block w-full rounded-lg border-transparent bg-gray-50 focus:bg-white focus:ring-blue-500 focus:border-blue-500 font-black text-sm text-center">
                                        </td>
                                        <td class="px-8 py-4 whitespace-nowrap text-xs font-bold text-slate-500">قطعة</td>
                                        <td class="px-8 py-4 whitespace-nowrap">
                                            <input type="text" x-model="item.note" class="block w-full rounded-lg border-transparent bg-gray-50 focus:bg-white focus:ring-blue-500 focus:border-blue-500 font-bold text-sm" placeholder="اختياري...">
                                        </td>
                                        <td class="px-8 py-4 whitespace-nowrap text-right">
                                            <button type="button" @click="removeItem(index)" class="text-rose-400 hover:text-rose-600 transition">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-blue-600 p-8 rounded-2xl shadow-xl flex items-center justify-between text-white">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4 rtl:ml-4 rtl:mr-0">
                            <i class="fa fa-info-circle text-xl"></i>
                        </div>
                        <p class="text-sm font-bold opacity-90">يرجى التأكد من مطابقة الكميات الفعلية قبل اعتماد المستند.</p>
                    </div>
                    <button type="submit" class="px-8 py-3 bg-white text-blue-600 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-50 transition shadow-lg">اعتماد الإدخال</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function stockMovementForm() {
            return {
                items: [{ id: '', qty: 1, note: '' }],
                addItem() { this.items.push({ id: '', qty: 1, note: '' }); },
                removeItem(index) { if (this.items.length > 1) this.items.splice(index, 1); }
            }
        }
    </script>
</x-app-layout>
