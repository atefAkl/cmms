<x-app-layout>
    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header 
                title="Edit: {{ $temperature_profile->name }}" 
                description="Modify thermal configurations. Note: Updates apply immediately to assigned rooms."
                :backRoute="route('settings.temperature-profiles.index')"
            />
            
            <div class="bg-white rounded-md shadow border border-slate-800 mt-2 text-slate-200">
                <form method="POST" action="{{ route('settings.temperature-profiles.update', $temperature_profile) }}" 
                      x-data="{ min: '{{ old('min_temp', $temperature_profile->min_temp) }}', 
                                max: '{{ old('max_temp', $temperature_profile->max_temp) }}', 
                                target: '{{ old('target_temp', $temperature_profile->target_temp) }}',
                                get isInvalid() { 
                                    if(this.min === '' || this.max === '' || this.target === '') return false;
                                    return (parseFloat(this.min) >= parseFloat(this.target)) || (parseFloat(this.target) >= parseFloat(this.max));
                                } 
                              }"
                      @submit="if(isInvalid) { $event.preventDefault(); alert('Thermal Logic Error: Ensure Min < Target < Max.'); }">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- General Info -->
                        <div class="space-y-6">
                            <h3 class="text-md font-black uppercase tracking-widest text-slate-500 border-b border-slate-800 py-3 px-5">Profile Details</h3>
                            <div class="px-6">
                                <div>
                                    <x-input-label for="name" :value="__('Profile Name')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                    <x-text-input id="name" class="block w-full" type="text" name="name"
                                        :value="old('name', $temperature_profile->name)" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="product_type" :value="__('Product Class')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                    <select id="product_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="product_type" required autofocus>
                                        <option value="chilled" {{ old('product_type', $temperature_profile->product_type) === 'chilled' ? 'selected' : '' }}>Chilled</option>
                                        <option value="frozen" {{ old('product_type', $temperature_profile->product_type) === 'frozen' ? 'selected' : '' }}>Frozen</option>
                                        <option value="custom" {{ old('product_type', $temperature_profile->product_type) === 'custom' ? 'selected' : '' }}>Custom / Extreme</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('product_type')" class="mt-2" />
                                </div>
                            </div>
                            
                        </div>

                        <!-- Thermal Spec -->
                        <div class="space-y-6">
                            <h3 class="text-md font-black uppercase tracking-widest text-slate-500 border-b border-slate-800 py-3 px-5 flex justify-between">
                                Thermal Spec 
                                <span x-show="isInvalid" class="text-red-500 animate-pulse">Range Invalid</span>
                            </h3>

                            <div class="px-6 bg-blue-200">
                                <div class="flex justify-between gap-4 bg-blue-200 py-3 px-5 mx-4 rounded-lg border border-blue-700">
                                    <div>
                                        <x-input-label for="min_temp" :value="__('Min °C')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                        <x-text-input id="min_temp" class="block w-full text-center" type="number" step="0.1" name="min_temp" x-model="min" required />
                                        <x-input-error :messages="$errors->get('min_temp')" class="mt-2" />
                                    </div>
                                    <div class="relative">
                                        <x-input-label for="target_temp" :value="__('Target °C')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                        <x-text-input id="target_temp" class="block w-full text-center" type="number" step="0.1" name="target_temp" x-model="target" required />
                                        <x-input-error :messages="$errors->get('target_temp')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="max_temp" :value="__('Max °C')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                        <x-text-input id="max_temp" class="block w-full text-center" type="number" step="0.1" name="max_temp" x-model="max" required />
                                        <x-input-error :messages="$errors->get('max_temp')" class="mt-2" />
                                    </div>
                                </div>
                                
                                <div>
                                    <x-input-label for="tolerance" :value="__('Alarm Tolerance (±°C)')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                    <x-text-input value="{{ old('tolerance', $temperature_profile->tolerance) }}" id="tolerance" class="block w-full text-center" type="number" step="0.1" name="tolerance" x-model="tolerance" required />
                                    <p class="text-[9px] font-bold text-slate-500 tracking-widest mt-1">Acceptable deviation range before alerting.</p>
                                    <x-input-error :messages="$errors->get('tolerance')" class="mt-2" />
                                </div>
                            </div>
                            
                        </div>

                    </div>

                    <div class="mt-4 border-t border-slate-800 flex justify-end p-3">
                        <a href="{{ route('settings.temperature-profiles.index') }}" class="my-2 px-4 py-2 bg-slate-800 text-slate-300 text-xs font-black uppercase tracking-widest rounded hover:bg-slate-700 transition">Cancel</a>
                        <button type="submit" :disabled="isInvalid" 
                                class="my-2 px-6 py-2 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded hover:bg-indigo-500 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-[0_0_15px_rgba(79,70,229,0.4)]">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
