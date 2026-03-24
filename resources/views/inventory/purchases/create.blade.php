<x-app-layout>
    <x-page-header title="تسجيل مشتريات جديدة">
        <div class="flex space-x-3 rtl:space-x-reverse">
            <a href="{{ route('inventory.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-black text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition shadow-sm">
                إلغاء
            </a>
            <button type="submit" form="purchase-form" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-black text-xs text-white uppercase tracking-widest hover:bg-emerald-500 transition shadow-sm">
                حفظ واعتماد
            </button>
        </div>
    </x-page-header>

    <div class="py-6 bg-gray-50/50" x-data="purchaseEntryForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <form id="purchase-form" action="#" method="POST">
                @csrf
                
                <!-- Document Header -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <x-label for="doc_number" value="رقم المستند" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1" />
                            <x-input id="doc_number" type="text" value="PO-{{ date('Ymd-Hi') }}" class="block w-full bg-gray-50 font-black text-slate-500" readonly />
                        </div>
                        <div>
                            <x-label for="doc_date" value="التاريخ" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1" />
                            <x-input id="doc_date" type="date" value="{{ date('Y-m-d') }}" class="block w-full font-black" required />
                        </div>
                        <div>
                            <x-label for="supplier_id" value="المورد" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1" />
                            <select id="supplier_id" class="block w-full rounded-lg border-gray-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 font-bold text-sm">
                                <option value="">اختر المورد...</option>
                                <option value="1">شركة التوريدات العالمية</option>
                                <option value="2">مؤسسة الأمل التجارية</option>
                            </select>
                        </div>
                        <div>
                            <x-label for="warehouse_id" value="المخزن المستلم" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1" />
                            <select id="warehouse_id" class="block w-full rounded-lg border-gray-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 font-bold text-sm">
                                <option value="">اختر المخزن...</option>
                                <option value="1">المستودع الرئيسي</option>
                                <option value="2">مستودع قطع الغيار</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 mb-8">
                    <div class="px-8 py-5 border-b border-gray-50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <h3 class="font-black text-slate-800 border-l-4 rtl:border-l-0 rtl:border-r-4 border-emerald-500 pl-4 rtl:pr-4 uppercase tracking-widest text-sm">أصناف الفاتورة</h3>
                            <template x-if="hasSerialMismatch()">
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-black animate-pulse">
                                    <i class="fa fa-exclamation-triangle mr-1"></i> عدد السيريالات لا يطابق الكمية
                                </span>
                            </template>
                        </div>
                        <button type="button" @click="addItem()" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-emerald-100 transition">
                            <i class="fa fa-plus mr-2 rtl:ml-2 rtl:mr-0"></i> إضافة صنف
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">الصنف / تفاصيل الفرادة</th>
                                    <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest w-32 text-center">الكمية</th>
                                    <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest w-40 text-center">سعر الوحدة</th>
                                    <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest w-40 text-center">الإجمالي</th>
                                    <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest w-24">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="hover:bg-gray-50/20 transition group">
                                        <td class="px-8 py-4">
                                            <div class="flex flex-col gap-2">
                                                <select x-model="item.id" class="block w-full rounded-lg border-transparent bg-gray-50 focus:bg-white focus:ring-emerald-500 focus:border-emerald-500 font-bold text-sm transition">
                                                    <option value="">اختر الصنف...</option>
                                                    <option value="1">ضاغط كوبلاند 5 حصان</option>
                                                    <option value="2">مروحة تبريد EBM</option>
                                                </select>
                                                
                                                <div class="flex items-center gap-4 mt-1">
                                                    <label class="flex items-center gap-2 cursor-pointer">
                                                        <input type="checkbox" x-model="item.is_unique" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-wide">هذا المنتج فريد (لكل وحدة سيريال)</span>
                                                    </label>
                                                    
                                                    <template x-if="item.is_unique">
                                                        <div class="flex items-center gap-2 flex-1">
                                                            <div class="relative flex-1">
                                                                <input type="text" x-model="item.serial" placeholder="السيريال نمبر..." 
                                                                    class="block w-full rounded-lg border-emerald-100 bg-emerald-50/30 py-1.5 focus:ring-emerald-500 focus:border-emerald-500 font-black text-xs">
                                                            </div>
                                                            <button type="button" @click="cloneItem(index)" class="px-2 py-1 bg-slate-100 text-slate-600 rounded border border-slate-200 text-[9px] font-black uppercase hover:bg-slate-200 transition" title="نسخ مع زيادة الترقيم">
                                                                <i class="fa fa-copy mr-1"></i> نسخ
                                                            </button>
                                                            <button type="button" @click="item.show_generator = !item.show_generator" class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded border border-emerald-200 text-[9px] font-black uppercase hover:bg-emerald-200 transition">
                                                                توليد تسلسل
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>

                                                <!-- Batch Generator Panel -->
                                                <template x-if="item.is_unique && item.show_generator">
                                                    <div class="mt-2 p-3 bg-slate-50 border border-slate-200 rounded-lg grid grid-cols-4 gap-3 items-end shadow-inner">
                                                        <div>
                                                            <label class="block text-[9px] font-black text-slate-400 mb-1">بداية السيريال</label>
                                                            <input type="text" x-model="item.gen_start" class="block w-full rounded border-gray-200 py-1 text-xs font-bold">
                                                        </div>
                                                        <div>
                                                            <label class="block text-[9px] font-black text-slate-400 mb-1">الزيادة</label>
                                                            <input type="number" x-model.number="item.gen_step" class="block w-full rounded border-gray-200 py-1 text-xs font-bold text-center">
                                                        </div>
                                                        <div>
                                                            <label class="block text-[9px] font-black text-slate-400 mb-1">العدد</label>
                                                            <input type="number" x-model.number="item.gen_count" class="block w-full rounded border-gray-200 py-1 text-xs font-bold text-center">
                                                        </div>
                                                        <button type="button" @click="batchGenerate(index)" class="bg-slate-800 text-white rounded py-1 px-3 text-[10px] font-black uppercase hover:bg-black transition">تنفيذ</button>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4 whitespace-nowrap">
                                            <div class="flex flex-col items-center">
                                                <input type="number" x-model.number="item.qty" class="block w-24 rounded-lg border-transparent bg-gray-50 focus:bg-white focus:ring-emerald-500 focus:border-emerald-500 font-black text-sm text-center">
                                                <span class="text-[9px] text-slate-400 font-bold mt-1">وحدة</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4 whitespace-nowrap">
                                            <input type="number" x-model.number="item.price" class="block w-full rounded-lg border-transparent bg-gray-50 focus:bg-white focus:ring-emerald-500 focus:border-emerald-500 font-black text-sm text-center">
                                        </td>
                                        <td class="px-8 py-4 whitespace-nowrap text-center">
                                            <div class="text-xs font-black text-slate-800" x-text="(item.qty * item.price).toLocaleString() + ' ج.م'"></div>
                                        </td>
                                        <td class="px-8 py-4 whitespace-nowrap text-center">
                                            <button type="button" @click="removeItem(index)" class="text-rose-400 hover:text-rose-600 transition opacity-0 group-hover:opacity-100">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot class="bg-slate-50/50">
                                <tr>
                                    <td class="px-8 py-4 text-left font-black text-slate-500 uppercase tracking-wider text-xs">إجمالي الفاتورة</td>
                                    <td colspan="4" class="px-8 py-4 text-right">
                                        <div class="text-xl font-black text-emerald-600" x-text="calculateGrandTotal().toLocaleString() + ' ج.م'"></div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Footer Summary -->
                <div class="bg-emerald-600 p-8 rounded-2xl shadow-xl flex items-center justify-between text-white border-b-4 border-emerald-800 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/5 pointer-events-none"></div>
                    <div class="relative z-10">
                        <h4 class="text-xs font-black uppercase tracking-widest opacity-75 mb-1">المبلغ الإجمالي المستحق</h4>
                        <p class="text-3xl font-black" x-text="calculateGrandTotal().toLocaleString() + ' ج.م'"></p>
                    </div>
                    <div class="relative z-10 flex gap-4">
                        <button type="button" class="px-6 py-3 bg-emerald-700/50 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-emerald-700 transition border border-emerald-500/30 group">
                            <i class="fa fa-save mr-2 rtl:ml-2 rtl:mr-0 opacity-50 group-hover:opacity-100 transition"></i> حفظ كمسودة
                        </button>
                        <button type="submit" class="px-8 py-3 bg-white text-emerald-600 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-emerald-50 transition shadow-lg flex items-center gap-2">
                             حفظ واعتماد <i class="fa fa-paper-plane opacity-50"></i>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <script>
        function purchaseEntryForm() {
            return {
                items: [
                    { 
                        id: '', qty: 1, price: 0, 
                        is_unique: false, serial: '', 
                        show_generator: false, gen_start: '', gen_step: 1, gen_count: 5 
                    }
                ],
                addItem() {
                    this.items.push({ 
                        id: '', qty: 1, price: 0, is_unique: false, serial: '', 
                        show_generator: false, gen_start: '', gen_step: 1, gen_count: 5 
                    });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                cloneItem(index) {
                    const original = this.items[index];
                    const nextSerial = this.incrementSerial(original.serial);
                    
                    // Create clone
                    const clone = JSON.parse(JSON.stringify(original));
                    clone.serial = nextSerial;
                    clone.show_generator = false;
                    
                    // Insert after index
                    this.items.splice(index + 1, 0, clone);
                },
                incrementSerial(serial) {
                    if (!serial) return '';
                    // Find numeric part at the end
                    const match = serial.match(/(.*?)(\d+)(\D*)$/);
                    if (match) {
                        const prefix = match[1];
                        const numeric = match[2];
                        const suffix = match[3];
                        const nextVal = (parseInt(numeric) + 1).toString().padStart(numeric.length, '0');
                        return prefix + nextVal + suffix;
                    }
                    return serial; // Fallback
                },
                batchGenerate(index) {
                    const item = this.items[index];
                    let currentSerial = item.gen_start || item.serial;
                    const step = item.gen_step || 1;
                    const count = item.gen_count || 1;

                    // Update current row first
                    item.serial = currentSerial;
                    item.qty = 1;

                    // Generate subsequent rows
                    for(let i = 1; i < count; i++) {
                        currentSerial = this.incrementSerialByStep(currentSerial, step);
                        const clone = JSON.parse(JSON.stringify(item));
                        clone.serial = currentSerial;
                        clone.show_generator = false;
                        this.items.splice(index + i, 0, clone);
                    }
                    item.show_generator = false;
                },
                incrementSerialByStep(serial, step) {
                    const match = serial.match(/(.*?)(\d+)(\D*)$/);
                    if (match) {
                        const prefix = match[1];
                        const numeric = match[2];
                        const suffix = match[3];
                        const nextVal = (parseInt(numeric) + step).toString().padStart(numeric.length, '0');
                        return prefix + nextVal + suffix;
                    }
                    return serial;
                },
                hasSerialMismatch() {
                    // Group by product ID and sum Qty vs Count of unique rows
                    const products = {};
                    this.items.forEach(it => {
                        if (!it.id) return;
                        if (!products[it.id]) products[it.id] = { qty: 0, unique_rows: 0, is_tracking: false };
                        products[it.id].qty += it.qty;
                        if (it.is_unique) {
                            products[it.id].unique_rows++;
                            products[it.id].is_tracking = true;
                        }
                    });
                    
                    // Check if any tracked product has mismatch
                    for (let id in products) {
                        if (products[id].is_tracking && products[id].qty !== products[id].unique_rows) {
                            return true;
                        }
                    }
                    return false;
                },
                calculateGrandTotal() {
                    return this.items.reduce((sum, item) => sum + (item.qty * item.price), 0);
                }
            }
        }
    </script>
</x-app-layout>
