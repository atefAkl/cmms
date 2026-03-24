<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة شراء #{{ $procurement->reference_number ?? $procurement->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .print-shadow-none { shadow: none; border: 1px solid #eee; }
        }
    </style>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-4xl mx-auto bg-white p-10 rounded-3xl shadow-xl border border-gray-100 print-shadow-none">
        <!-- Header -->
        <div class="flex justify-between items-start border-b-4 border-indigo-600 pb-8 mb-8">
            <div>
                <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tighter mb-2">فاتورة شراء</h1>
                <p class="text-indigo-600 font-bold tracking-widest text-sm uppercase">نظام إدارة الصيانة والمخازن</p>
            </div>
            <div class="text-left py-2 px-4 bg-gray-50 rounded-2xl border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">رقم المرجع</p>
                <p class="text-xl font-black text-gray-900">#{{ $procurement->reference_number ?? str_pad($procurement->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-2 gap-12 mb-12">
            <div>
                <h3 class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-3">بيانات المورد</h3>
                <p class="text-lg font-black text-gray-900 mb-1">{{ $procurement->supplier->name ?? 'مورد متنوع' }}</p>
                <p class="text-sm text-gray-500 font-bold leading-relaxed">{{ $procurement->supplier->contact_name ?? '' }}</p>
                <p class="text-sm text-gray-500 font-bold leading-relaxed">{{ $procurement->supplier->phone ?? '' }}</p>
            </div>
            <div class="text-left">
                <h3 class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-3">تفاصيل الأمر</h3>
                <div class="space-y-2">
                    <p class="text-sm font-bold text-gray-600 flex justify-between">
                        <span>تاريخ التحرير:</span>
                        <span class="text-gray-900 font-black">{{ $procurement->transaction_date }}</span>
                    </p>
                    <p class="text-sm font-bold text-gray-600 flex justify-between">
                        <span>مخزن الاستلام:</span>
                        <span class="text-gray-900 font-black">{{ $procurement->warehouse->name }}</span>
                    </p>
                    <p class="text-sm font-bold text-gray-600 flex justify-between">
                        <span>حالة الدفع:</span>
                        <span class="text-gray-900 font-black uppercase">{{ $procurement->payment_status }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="mb-12">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest">
                        <th class="px-6 py-4 rounded-tr-2xl">الصنف / الوصف</th>
                        <th class="px-6 py-4">الرقم التسلسلي</th>
                        <th class="px-6 py-4 text-center">الكمية</th>
                        <th class="px-6 py-4 text-center">سعر الوحدة</th>
                        <th class="px-6 py-4 text-left rounded-tl-2xl">الإجمالي</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-x border-b border-gray-100 rounded-b-2xl overflow-hidden">
                    @foreach($procurement->items as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <span class="font-black text-gray-900 text-sm block">{{ $item->item->name }}</span>
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $item->item->category->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-600">
                            {{ $item->serial_number ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-black text-gray-900">
                            {{ number_format($item->quantity, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-black text-gray-600">
                            {{ number_format($item->unit_cost, 2) }}
                        </td>
                        <td class="px-6 py-4 text-left text-sm font-black text-indigo-600">
                            {{ number_format($item->quantity * $item->unit_cost, 2) }} SAR
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-900 text-white">
                        <td colspan="4" class="px-6 py-6 text-left font-black uppercase tracking-widest text-xs rounded-br-2xl">إجمالي القيمة</td>
                        <td class="px-6 py-6 text-left font-black text-2xl rounded-bl-2xl">
                            {{ number_format($procurement->total_cost, 2) }} SAR
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer -->
        <div class="grid grid-cols-2 gap-12 pt-12 border-t-2 border-dashed border-gray-100">
            <div>
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">ملاحظات</h3>
                <p class="text-sm text-gray-600 font-bold leading-relaxed italic">
                    {{ $procurement->notes ?? 'لا توجد ملاحظات إضافية' }}
                </p>
            </div>
            <div class="text-left flex flex-col items-end">
                <div class="w-48 h-24 border-2 border-gray-100 rounded-2xl flex items-center justify-center text-[10px] font-black text-gray-300 uppercase tracking-widest mb-2">
                    ختم الاعتماد
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">توقيع المستلم</p>
            </div>
        </div>

        <!-- Action Button -->
        <div class="mt-12 flex justify-center no-print">
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-indigo-600/20 transition hover:-translate-y-1">
                طباعة الفاتورة الآن
            </button>
        </div>
    </div>
</body>
</html>
