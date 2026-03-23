<x-app-layout>
    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header 
                title="New Temperature Profile" 
                description="Define optimal environmental constraints for automated storage logic."
                :backRoute="route('settings.temperature-profiles.index')"
            />
            
            <div class="bg-slate-900 rounded-md shadow border border-slate-800 p-8 mt-6 text-slate-200">
                <form method="POST" action="{{ route('settings.temperature-profiles.store') }}" 
                      x-data="{ min: '{{ old('min_temp', '') }}', max: '{{ old('max_temp', '') }}', target: '{{ old('target_temp', '') }}',
                                get isInvalid() { 
                                    if(this.min === '' || this.max === '' || this.target === '') return false;
                                    return (parseFloat(this.min) >= parseFloat(this.target)) || (parseFloat(this.target) >= parseFloat(this.max));
                                } 
                              }"
                      @submit="if(isInvalid) { $event.preventDefault(); alert('Thermal Logic Error: Ensure Min < Target < Max.'); }">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- General Info -->
                        <div class="space-y-6">
                            <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 border-b border-slate-800 pb-2">Profile Details</h3>
                            
                            <div>
                                <label for="name" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Profile Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                                       class="block w-full bg-slate-950 border-slate-700 text-slate-100 focus:ring-indigo-500 focus:border-indigo-500 rounded sm:text-sm font-bold shadow-inner" 
                                       placeholder="e.g. Deep Freeze O-Negative">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <label for="product_type" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Product Class</label>
                                <select id="product_type" name="product_type" required 
                                        class="block w-full bg-slate-950 border-slate-700 text-slate-100 focus:ring-indigo-500 focus:border-indigo-500 rounded sm:text-sm font-bold shadow-inner">
                                    <option value="chilled" {{ old('product_type') === 'chilled' ? 'selected' : '' }}>Chilled</option>
                                    <option value="frozen" {{ old('product_type') === 'frozen' ? 'selected' : '' }}>Frozen</option>
                                    <option value="custom" {{ old('product_type', 'custom') === 'custom' ? 'selected' : '' }}>Custom / Extreme</option>
                                </select>
                                <x-input-error :messages="$errors->get('product_type')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Thermal Spec -->
                        <div class="space-y-6">
                            <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 border-b border-slate-800 pb-2 flex justify-between">
                                Thermal Spec 
                                <span x-show="isInvalid" class="text-red-500 animate-pulse">Range Invalid</span>
                            </h3>

                            <div class="grid grid-cols-3 gap-4 bg-slate-800/50 p-4 rounded-lg border border-slate-700/50">
                                <div>
                                    <label for="min_temp" class="block text-[10px] text-center font-black uppercase tracking-widest text-blue-400 mb-1">Min °C</label>
                                    <input type="number" step="0.1" id="min_temp" name="min_temp" x-model="min" required 
                                           class="block w-full text-center bg-slate-950 border-blue-900/50 text-blue-100 focus:ring-blue-500 focus:border-blue-500 rounded sm:text-sm font-mono font-bold shadow-inner" 
                                           placeholder="-20.0">
                                    <x-input-error :messages="$errors->get('min_temp')" class="mt-2" />
                                </div>
                                <div class="relative">
                                    <label for="target_temp" class="block text-[10px] text-center font-black uppercase tracking-widest text-emerald-400 mb-1">Target °C</label>
                                    <input type="number" step="0.1" id="target_temp" name="target_temp" x-model="target" required 
                                           class="block w-full text-center bg-slate-950 border-emerald-900/50 text-emerald-100 focus:ring-emerald-500 focus:border-emerald-500 rounded text-lg font-mono font-black shadow-inner" 
                                           placeholder="-18.0">
                                    <x-input-error :messages="$errors->get('target_temp')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="max_temp" class="block text-[10px] text-center font-black uppercase tracking-widest text-red-400 mb-1">Max °C</label>
                                    <input type="number" step="0.1" id="max_temp" name="max_temp" x-model="max" required 
                                           class="block w-full text-center bg-slate-950 border-red-900/50 text-red-100 focus:ring-red-500 focus:border-red-500 rounded sm:text-sm font-mono font-bold shadow-inner" 
                                           placeholder="-15.0">
                                    <x-input-error :messages="$errors->get('max_temp')" class="mt-2" />
                                </div>
                            </div>
                            
                            <div>
                                <label for="tolerance" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Alarm Tolerance (±°C)</label>
                                <input type="number" step="0.1" min="0" id="tolerance" name="tolerance" value="{{ old('tolerance', '2.0') }}" required 
                                       class="block w-full bg-slate-950 border-slate-700 text-slate-100 focus:ring-indigo-500 focus:border-indigo-500 rounded sm:text-sm font-mono font-bold shadow-inner" 
                                       placeholder="2.0">
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1">Acceptable deviation range before alerting.</p>
                                <x-input-error :messages="$errors->get('tolerance')" class="mt-2" />
                            </div>
                        </div>

                    </div>

                    <div class="mt-8 pt-5 border-t border-slate-800 flex justify-end gap-3">
                        <a href="{{ route('settings.temperature-profiles.index') }}" class="px-4 py-2 bg-slate-800 text-slate-300 text-xs font-black uppercase tracking-widest rounded hover:bg-slate-700 transition">Cancel</a>
                        <button type="submit" :disabled="isInvalid" 
                                class="px-6 py-2 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded hover:bg-indigo-500 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-[0_0_15px_rgba(79,70,229,0.4)]">
                            Deploy Profile
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
