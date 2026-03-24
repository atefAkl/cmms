@php
    $procurementItems = $procurement->items->map(function($item) {
        return [
            'id' => $item->id,
            'inventory_item_id' => $item->inventory_item_id,
            'name' => $item->item->name,
            'category_name' => $item->item->category->name,
            'uom' => $item->item->uom,
            'quantity' => (float)$item->quantity,
            'unit_cost' => (float)$item->unit_cost,
            'serial_number' => $item->serial_number
        ];
    });
@endphp

<x-app-layout>
    <div x-data="procurementEditor({{ Js::from($procurementItems) }})">
    <x-page-header 
        :title="'تفاصيل أمر الشراء #' . ($procurement->reference_number ?? str_pad($procurement->id, 5, '0', STR_PAD_LEFT))"
        :backRoute="route('procurement.index')"
    >
        <div class="flex space-x-3 rtl:space-x-reverse">
            @if($procurement->approval_status === 'pending')
                <form action="{{ route('procurement.approve', $procurement) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-black text-xs uppercase tracking-widest shadow transition flex items-center">
                        اعتماد الطلب
                    </button>
                </form>
                <form action="{{ route('procurement.reject', $procurement) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg font-black text-xs uppercase tracking-widest shadow transition flex items-center">
                        رفض
                    </button>
                </form>
            @endif

            {{-- Receive/Confirm Button --}}
            @if(($procurement->approval_status === 'approved' && $procurement->status === 'pending') || $procurement->status === 'editing')
                <form action="{{ route('procurement.receive', $procurement) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-black text-xs uppercase tracking-widest shadow transition flex items-center">
                        <i class="fa fa-check mr-1 rtl:ml-1 rtl:mr-0"></i>
                        {{ $procurement->status === 'editing' ? 'تأكيد الاستلام وتعديل الرصيد' : 'تأكيد الاستلام وتحديث المخزن' }}
                    </button>
                </form>
            @endif

            {{-- Allow Edit (Amber) Button --}}
            @if($procurement->status === 'received' || ($procurement->status === 'pending' && $procurement->approval_status === 'approved'))
                @if($procurement->items->count() > 0 && $procurement->status !== 'editing')
                    <button @click="showPasswordModal = true" 
                        class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg font-black text-xs uppercase tracking-widest shadow transition flex items-center">
                        <i class="fa fa-unlock mr-1 rtl:ml-1 rtl:mr-0"></i>
                        إتاحة النموذج للتعديل
                    </button>
                @endif
            @endif

            {{-- Edit Items (Toggle) Button --}}
            <button @click="isEditMode = !isEditMode" 
                x-show="isEditMode || 
                    (['pending', 'editing'].includes('{{ $procurement->status }}') && '{{ $procurement->approval_status }}' !== 'approved') || 
                    '{{ $procurement->status }}' === 'editing' || 
                    {{ $procurement->items->count() === 0 ? 'true' : 'false' }}"
                :class="isEditMode ? 'bg-rose-600 shadow-rose-200' : 'bg-emerald-600 shadow-emerald-200'"
                class="px-4 py-2 rounded-lg font-black text-xs uppercase tracking-widest shadow-lg transition active:scale-95 flex items-center">
                <i class="fa ms-2" :class="isEditMode ? 'fa-times' : 'fa-edit'"></i>
                <span x-text="isEditMode ? 'إيقاف التعديل' : 'تفعيل التعديل'"></span>
            </button>

            <a href="{{ route('procurement.print', $procurement) }}" target="_blank" class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg font-black text-xs uppercase tracking-widest shadow-sm transition hover:bg-gray-50 flex items-center">
                <i class="fa fa-print mr-1 rtl:ml-1 rtl:mr-0"></i>
                طباعة PDF
            </a>
        </div>
    </x-page-header>

    <div class="py-8">
        <div class="max-w-7xl mx-auto space-y-8 px-4 sm:px-6 lg:px-8">
            
            <!-- Header Info Card -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">المورد</label>
                    <p class="font-bold text-gray-900 border-l-4 rtl:border-l-0 rtl:border-r-4 border-indigo-600 pl-3 rtl:pr-3 leading-tight text-sm">{{ $procurement->supplier->name ?? 'مورد متنوع' }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">مخزن الوجهة</label>
                    <p class="font-bold text-gray-900 border-l-4 rtl:border-l-0 rtl:border-r-4 border-indigo-600 pl-3 rtl:pr-3 leading-tight text-sm">{{ $procurement->warehouse->name }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">التاريخ</label>
                    <p class="font-bold text-gray-900 border-l-4 rtl:border-l-0 rtl:border-r-4 border-indigo-600 pl-3 rtl:pr-3 leading-tight text-sm">{{ $procurement->transaction_date }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">حالة الموافقة</label>
                    <span class="inline-flex px-3 py-1 text-[10px] font-black uppercase tracking-wider rounded-full {{ $procurement->approval_status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($procurement->approval_status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ $procurement->approval_status === 'approved' ? 'معتمد' : ($procurement->approval_status === 'rejected' ? 'مرفوض' : 'قيد الانتظار') }}
                    </span>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">الإجراءات</label>
                    <button @click="showEditHeader = true" class="text-xs font-black text-indigo-600 hover:text-indigo-800 underline uppercase tracking-widest">تعديل البيانات</button>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="px-4 py-2 bg-slate-900 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                            <i class="fa fa-list-ul"></i>
                        </div>
                        <h3 class="font-black uppercase tracking-widest text-md">إدارة أصناف الفاتورة</h3>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-md text-slate-400 font-black uppercase tracking-widest" x-text="items.length + ' أصناف مسجلة'"></span>
                        <template x-if="isProcessing">
                            <span class="text-sm text-indigo-400 font-black animate-pulse">جاري المعالجة...</span>
                        </template>
                    </div>
                </div>
                
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-sm font-black uppercase text-slate-400 tracking-widest border-b border-gray-100">
                                <th class="p-2 text-right min-w-[400px]">الصنف / الموديل</th>
                                <th class="p-2 text-right">الرقم التسلسلي (S/N)</th>
                                <th class="p-2 text-center" style="width: 100px;">الكمية</th>
                                <th class="p-2 text-center" style="width: 100px;">سعر الوحدة</th>
                                <th class="p-2 text-center" style="width: 100px;">الإجمالي</th>
                                <th class="p-2 w-24 text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <!-- ENTRY ROW (نموذج الإدخال السريع) -->
                            <tr x-show="isEditMode" x-transition class="bg-indigo-50/30 border-b-2 border-indigo-100/50 group">
                                <td class="p-1">
                                    <select x-model="entry.inventory_item_id" @change="onEntryItemChange()" class="block w-full rounded-xl border-indigo-100 bg-white py-2 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">-- اختر الصنف --</option>
                                        @foreach($inventoryItems as $invItem)
                                            <option value="{{ $invItem->id }}" data-cost="{{ $invItem->cost }}">{{ $invItem->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-1">
                                    <input type="text" x-model="entry.serial_number" @input="if(entry.serial_number) entry.quantity = 1" placeholder="أدخل السيريال..." class="block w-full rounded-xl border-indigo-100 py-2 text-sm font-black placeholder-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                </td>
                                <td class="p-1">
                                    <input type="number" x-model.number="entry.quantity" :disabled="entry.serial_number" class="block w-full rounded-xl border-indigo-100 py-2 text-sm font-black text-center focus:ring-indigo-500 focus:border-indigo-500 disabled:opacity-50">
                                </td>
                                <td class="p-1">
                                    <input type="number" x-model.number="entry.unit_cost" class="block w-full rounded-xl border-indigo-100 py-2 text-sm font-black text-center focus:ring-indigo-500 focus:border-indigo-500">
                                </td>
                                <td class="p-1 text-center">
                                    <div class="text-xs font-black text-indigo-900" x-text="(entry.quantity * entry.unit_cost).toLocaleString() + ' SAR'"></div>
                                </td>
                                <td class="p-1">
                                    <div class="flex gap-2 justify-center">
                                        <button @click="submitEntry(false)" class="shadow-md shadow-emerald-100 transition active:scale-95 flex items-center justify-center w-10 h-10" title="إدخال">
                                            <i class="fa fa-plus text-sm"></i>
                                        </button>
                                        <button @click="submitEntry(true)" class="shadow-md shadow-indigo-100 transition active:scale-95 flex items-center justify-center w-10 h-10" title="نسخ">
                                            <i class="fa fa-copy text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- DATA ROWS -->
                            <template x-for="(item, index) in items" :key="item.id || index">
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="p-1">
                                        <select x-model="item.inventory_item_id" @change="onRowItemChange(index)" class="block w-full rounded-lg border-transparent hover:border-gray-200 bg-gray-50/50 py-1 text-xs font-black focus:bg-white focus:ring-indigo-500">
                                            @foreach($inventoryItems as $invItem)
                                                <option value="{{ $invItem->id }}" data-cost="{{ $invItem->cost }}">{{ $invItem->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-1">
                                        <input type="text" x-model="item.serial_number" @input="if(item.serial_number) item.quantity = 1" class="block w-full rounded-lg border-transparent hover:border-gray-200 bg-gray-50/50 py-1.5 text-xs font-black focus:bg-white focus:ring-indigo-500">
                                    </td>
                                    <td class="p-1">
                                        <input type="number" x-model.number="item.quantity" :disabled="item.serial_number" class="block w-full rounded-lg border-transparent bg-gray-50/50 py-1.5 text-xs font-black text-center focus:bg-white focus:ring-indigo-500 disabled:opacity-50">
                                    </td>
                                    <td class="p-1">
                                        <input type="number" x-model.number="item.unit_cost" class="block w-full rounded-lg border-transparent bg-gray-50/50 py-1.5 text-xs font-black text-center focus:bg-white focus:ring-indigo-500">
                                    </td>
                                    <td class="p-1 text-center">
                                        <div class="text-xs font-black text-slate-900" x-text="(item.quantity * item.unit_cost).toLocaleString() + ' SAR'"></div>
                                    </td>
                                    <td class="p-1">
                                        <div x-show="isEditMode" x-transition class="flex gap-2 justify-center transition">
                                            <button @click="updateRow(index)" class="shadow-md shadow-blue-100 transition active:scale-95 flex items-center justify-center w-10 h-10" title="تحديث">
                                                <i class="fa fa-sync-alt text-xs"></i>
                                            </button>
                                            <button @click="removeItem(index)" class="shadow-md shadow-rose-100 transition active:scale-95 flex items-center justify-center w-10 h-10" title="حذف">
                                                <i class="fa fa-trash-alt text-xs"></i>
                                            </button>
                                        </div>
                                        <div x-show="!isEditMode">
                                            @if($procurement->status === 'received')
                                                <div class="text-center text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">تم القفل (استلام)</div>
                                            @else
                                                <div class="text-center text-[10px] text-gray-400 font-bold uppercase tracking-widest">عرض فقط</div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-900 shadow-2xl">
                                <td colspan="4" class="px-8 py-6 text-left font-black uppercase tracking-[0.2em] text-xs opacity-60">إجمالي فاتورة المشتريات</td>
                                <td class="px-8 py-6 text-right font-black text-2xl border-r border-white/10" x-text="calculateGrandTotal().toLocaleString() + ' SAR'"></td>
                                <td class="bg-indigo-600"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 flex items-start gap-4 mx-4">
                <i class="fa fa-info-circle text-amber-500 mt-1"></i>
                <div>
                    <h4 class="text-xs font-black text-amber-900 uppercase mb-1">تذكير بإجراءات المستودع</h4>
                    <p class="text-[10px] text-amber-800 font-bold leading-relaxed uppercase tracking-wide">هذه الشاشة مخصصة لإدارة "البيانات المالية" للفاتورة. سيتم تحديث مخزون المستودع فعلياً فقط بعد الضغط على زر "تأكيد الاستلام" في أعلى الصفحة.</p>
                </div>
            </div>
    </div>

    <!-- Edit Header Modal -->
    <template x-if="showEditHeader">
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showEditHeader = false"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-right overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                    <form action="{{ route('procurement.update', $procurement) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="bg-indigo-600 px-8 py-6">
                            <h3 class="text-lg font-black text-white uppercase tracking-widest">تعديل بيانات الفاتورة</h3>
                        </div>

                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">المورد</label>
                                <select name="supplier_id" class="block w-full rounded-xl border-gray-100 py-3 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- مورد متنوع --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $procurement->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">مخزن الوجهة</label>
                                <select name="warehouse_id" required class="block w-full rounded-xl border-gray-100 py-3 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500">
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ $procurement->warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">رقم المرجع</label>
                                <input type="text" name="reference_number" value="{{ $procurement->reference_number }}" class="block w-full rounded-xl border-gray-100 py-3 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">التاريخ</label>
                                <input type="date" name="transaction_date" value="{{ $procurement->transaction_date }}" required class="block w-full rounded-xl border-gray-100 py-3 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">حالة الدفع</label>
                                <select name="payment_status" required class="block w-full rounded-xl border-gray-100 py-3 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="unpaid" {{ $procurement->payment_status == 'unpaid' ? 'selected' : '' }}>غير مدفوع</option>
                                    <option value="partially_paid" {{ $procurement->payment_status == 'partially_paid' ? 'selected' : '' }}>مدفوع جزئياً</option>
                                    <option value="paid" {{ $procurement->payment_status == 'paid' ? 'selected' : '' }}>مدفوع بالكامل</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">ملاحظات إضافية</label>
                                <textarea name="notes" rows="3" class="block w-full rounded-xl border-gray-100 py-3 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500">{{ $procurement->notes }}</textarea>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-8 py-6 flex flex-row-reverse gap-3">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-100 transition active:scale-95">
                                حفظ التغييرات
                            </button>
                            <button type="button" @click="showEditHeader = false" class="bg-white border border-gray-200 text-gray-500 px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gray-50 transition">
                                إلغاء
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <!-- Password Verification Modal -->
    <template x-if="showPasswordModal">
        <div class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900 bg-opacity-75 backdrop-blur-sm transition-opacity" @click="showPasswordModal = false"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-3xl text-right overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                    <div class="bg-amber-500 px-8 py-6 flex items-center justify-between">
                        <h3 class="text-lg font-black text-white uppercase tracking-widest">تأكيد صلاحية التعديل</h3>
                        <i class="fa fa-lock text-white/50 text-2xl"></i>
                    </div>

                    <div class="p-8">
                        <p class="text-xs font-bold text-slate-500 mb-6 leading-relaxed">يرجى إدخال كلمة المرور للمتابعة. هذا الإجراء سيقوم بتجميد رصيد هذه الأصناف في المخزن مؤقتاً حتى يتم إغلاق الفاتورة مرة أخرى.</p>
                        
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">كلمة المرور</label>
                            <input type="password" x-model="password" @keydown.enter="submitEnableEditing()" autofocus
                                class="block w-full rounded-xl border-gray-200 py-3 text-center text-lg font-black tracking-[0.5em] focus:ring-amber-500 focus:border-amber-500">
                        </div>
                    </div>

                    <div class="bg-gray-50 px-8 py-6 flex flex-row-reverse gap-3">
                        <button type="button" @click="submitEnableEditing()" 
                            class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest shadow-xl shadow-amber-100 transition active:scale-95 flex items-center gap-2">
                            <span x-show="!isProcessing">فتح التعديل</span>
                            <span x-show="isProcessing">جاري التحقق...</span>
                            <i class="fa fa-arrow-left" x-show="!isProcessing"></i>
                        </button>
                        <button type="button" @click="showPasswordModal = false" class="bg-white border border-gray-200 text-gray-500 px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gray-50 transition">
                            إلغاء
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

    <script>
        function procurementEditor(initialItems) {
            const urlParams = new URLSearchParams(window.location.search);
            return {
                items: initialItems,
                procurementId: {{ $procurement->id }},
                isProcessing: false,
                showEditHeader: false,
                showPasswordModal: false,
                password: '',
                isEditMode: urlParams.get('edit') === '1',
                entry: {
                    inventory_item_id: '',
                    serial_number: '',
                    quantity: 1,
                    unit_cost: 0
                },

                async submitEnableEditing() {
                    if (!this.password) return;
                    this.isProcessing = true;
                    try {
                        const response = await fetch(`{{ route('procurement.enableEditing', $procurement) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ password: this.password })
                        });

                        const result = await response.json();
                        if (response.ok && result.success) {
                            window.location.reload();
                        } else {
                            alert(result.message || 'خطأ في التحقق');
                        }
                    } catch (e) {
                        alert('حدث خطأ في الاتصال');
                    } finally {
                        this.isProcessing = false;
                        this.showPasswordModal = false;
                    }
                },

                onEntryItemChange() {
                    const select = event.target;
                    const selected = select.options[select.selectedIndex];
                    if (selected.value) {
                        this.entry.unit_cost = parseFloat(selected.dataset.cost) || 0;
                    }
                },

                onRowItemChange(index) {
                    const select = event.target;
                    const selected = select.options[select.selectedIndex];
                    if (selected.value) {
                        this.items[index].unit_cost = parseFloat(selected.dataset.cost) || 0;
                    }
                },

                async submitEntry(isCopy) {
                    if (!this.entry.inventory_item_id) {
                        alert('يرجى اختيار صنف أولاً');
                        return;
                    }

                    // Force quantity to 1 if serial is present
                    if (this.entry.serial_number) {
                        this.entry.quantity = 1;
                    }

                    this.isProcessing = true;
                    try {
                        const response = await fetch(`{{ route('procurement.addItem', $procurement) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.entry)
                        });

                        const result = await response.json();
                        if (response.ok && result.success) {
                            this.items.unshift(result.item);
                            
                            if (isCopy) {
                                this.entry.serial_number = this.incrementSerial(this.entry.serial_number);
                            } else {
                                this.entry = { inventory_item_id: '', serial_number: '', quantity: 1, unit_cost: 0 };
                            }
                        } else {
                            const errorMsg = result.errors?.serial_number?.[0] || result.message || 'خطأ أثناء الإضافة';
                            alert(errorMsg);
                        }
                    } catch (e) {
                        alert('حدث خطأ في الاتصال');
                    } finally {
                        this.isProcessing = false;
                    }
                },

                async updateRow(index) {
                    const item = this.items[index];

                    // Force quantity to 1 if serial is present
                    if (item.serial_number) {
                        item.quantity = 1;
                    }

                    this.isProcessing = true;
                    try {
                        const response = await fetch(`/procurement/${this.procurementId}/items/${item.id}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                inventory_item_id: item.inventory_item_id,
                                quantity: item.quantity,
                                unit_cost: item.unit_cost,
                                serial_number: item.serial_number
                            })
                        });

                        if (response.ok) {
                            alert('تم التحديث بنجاح');
                        } else {
                            const result = await response.json();
                            const errorMsg = result.errors?.serial_number?.[0] || 'فشل التحديث';
                            alert(errorMsg);
                        }
                    } catch (e) {
                        alert('خطأ في الاتصال');
                    } finally {
                        this.isProcessing = false;
                    }
                },

                async removeItem(index) {
                    const item = this.items[index];
                    if (confirm('هل أنت متأكد من حذف هذا الصنف من الفاتورة؟')) {
                        this.isProcessing = true;
                        try {
                            const response = await fetch(`/procurement/${this.procurementId}/items/${item.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });

                            if (response.ok) {
                                this.items.splice(index, 1);
                            } else {
                                alert('فشل الحذف من الخادم');
                            }
                        } catch (e) {
                            alert('خطأ في الاتصال');
                        } finally {
                            this.isProcessing = false;
                        }
                    }
                },

                incrementSerial(serial) {
                    if (!serial) return '';
                    const match = serial.match(/(.*?)(\d+)(\D*)$/);
                    if (match) {
                        const prefix = match[1];
                        const numeric = match[2];
                        const suffix = match[3];
                        const nextVal = (parseInt(numeric) + 1).toString().padStart(numeric.length, '0');
                        return prefix + nextVal + suffix;
                    }
                    return serial;
                },

                calculateGrandTotal() {
                    return this.items.reduce((sum, item) => sum + (item.quantity * item.unit_cost), 0);
                }
            }
        }
    </script>
    </div>
</x-app-layout>

