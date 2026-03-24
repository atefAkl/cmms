<x-app-layout>
    <x-page-header title="لوحة تحكم المخازن">
        <div class="flex space-x-3 rtl:space-x-reverse">
            <a href="#" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-black text-xs text-white uppercase tracking-widest hover:bg-emerald-500 transition shadow-sm">
                <i class="fa fa-file-invoice mr-2 rtl:ml-2 rtl:mr-0"></i> تقرير الجرد
            </a>
        </div>
    </x-page-header>

    <div class="py-6 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Quick Actions -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <a href="{{ route('inventory.purchases.create') }}" class="flex flex-col items-center justify-center p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition group">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-3 group-hover:bg-emerald-100 transition">
                        <i class="fa fa-shopping-cart text-emerald-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-black text-slate-700 uppercase tracking-wide">تسجيل مشتريات</span>
                </a>
                
                <a href="{{ route('inventory.stock-in.create') }}" class="flex flex-col items-center justify-center p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition group">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-3 group-hover:bg-blue-100 transition">
                        <i class="fa fa-arrow-down text-blue-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-black text-slate-700 uppercase tracking-wide">أمر إدخال</span>
                </a>

                <a href="{{ route('inventory.stock-out.create') }}" class="flex flex-col items-center justify-center p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-amber-200 transition group">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center mb-3 group-hover:bg-amber-100 transition">
                        <i class="fa fa-arrow-up text-amber-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-black text-slate-700 uppercase tracking-wide">أمر إخراج</span>
                </a>

                <a href="{{ route('inventory.transfers.create') }}" class="flex flex-col items-center justify-center p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-indigo-200 transition group">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center mb-3 group-hover:bg-indigo-100 transition">
                        <i class="fa fa-exchange-alt text-indigo-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-black text-slate-700 uppercase tracking-wide">سند تحويل</span>
                </a>

                <a href="#" class="flex flex-col items-center justify-center p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-rose-200 transition group">
                    <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center mb-3 group-hover:bg-rose-100 transition">
                        <i class="fa fa-balance-scale text-rose-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-black text-slate-700 uppercase tracking-wide">تسوية مخزون</span>
                </a>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center">
                    <div class="p-4 bg-emerald-50 rounded-2xl mr-4 rtl:ml-4 rtl:mr-0 text-emerald-600">
                        <i class="fa fa-boxes text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">إجمالي الأصناف</p>
                        <h4 class="text-2xl font-black text-slate-800">1,240</h4>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center">
                    <div class="p-4 bg-rose-50 rounded-2xl mr-4 rtl:ml-4 rtl:mr-0 text-rose-600">
                        <i class="fa fa-exclamation-triangle text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">أصناف منخفضة الرصيد</p>
                        <h4 class="text-2xl font-black text-slate-800">18</h4>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center">
                    <div class="p-4 bg-blue-50 rounded-2xl mr-4 rtl:ml-4 rtl:mr-0 text-blue-600">
                        <i class="fa fa-warehouse text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">المستودعات النشطة</p>
                        <h4 class="text-2xl font-black text-slate-800">4</h4>
                    </div>
                </div>
            </div>

            <!-- Recent Movements Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="px-8 py-5 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="font-black text-slate-800 border-l-4 rtl:border-l-0 rtl:border-r-4 border-emerald-500 pl-4 rtl:pr-4 uppercase tracking-widest text-sm">آخر الحركات المخزنية</h3>
                    <a href="#" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-800 transition">عرض الكل <i class="fa fa-arrow-left mt-1 rtl:mr-1"></i></a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">نوع الحركة</th>
                                <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">رقم المستند</th>
                                <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">التاريخ</th>
                                <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">المستودع</th>
                                <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach([1,2,3,4,5] as $row)
                            <tr class="hover:bg-gray-50/50 transition cursor-pointer">
                                <td class="px-8 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full {{ $row % 2 == 0 ? 'bg-emerald-500' : 'bg-amber-500' }}"></div>
                                        <span class="text-xs font-bold text-slate-700">{{ $row % 2 == 0 ? 'توريد مشتريات' : 'صرف قطع غيار' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-xs font-black text-slate-500">TRX-00{{ $row }}</td>
                                <td class="px-8 py-4 whitespace-nowrap text-xs font-bold text-slate-600">2026-03-24</td>
                                <td class="px-8 py-4 whitespace-nowrap text-xs font-bold text-slate-600">المستودع الرئيسي</td>
                                <td class="px-8 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-100">مكتمل</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
