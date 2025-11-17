<div class="min-h-screen bg-gradient-to-b from-indigo-50 to-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 sm:mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Complete Your KYC</h1>
                    <p class="mt-2 text-sm text-gray-600">Provide accurate details to verify your membership and unlock all features.</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-100 text-indigo-700 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Step {{ max(1, min(7, $currentStep)) }} of 7
                    </span>
                </div>
            </div>
        </div>
        @if($name)
            <div class="mb-4 p-4 rounded-lg bg-blue-50 text-blue-700">
                Welcome back! You can continue your membership application from where you left off.
            </div>
        @endif

        @php $steps = ['Personal Info','Family Details','Address','Nominee','Bank Details','Documents','Photo']; @endphp
        <div class="mb-8">
            <div class="relative">
                <div class="h-2 w-full bg-gray-200 rounded-full"></div>
                <div class="h-2 bg-indigo-500 rounded-full absolute top-0 left-0" style="width: {{ max(0, (($currentStep-1)/6)*100) }}%"></div>
                <div class="mt-3 grid grid-cols-7 gap-2">
                    @foreach($steps as $i => $step)
                        <div class="text-[11px] sm:text-xs text-gray-600 {{ $currentStep-1 >= $i ? 'font-semibold text-indigo-700' : '' }}">{{ $step }}</div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Form Steps -->
        <div class="bg-white shadow rounded-lg p-6 sm:p-8">
            <form wire:submit.prevent="{{ $currentStep < 7 ? 'nextStep' : 'register' }}">
                <x-global.loader wire:loading.flex wire:target="register" message="Registering..." />
                <x-global.loader wire:loading.flex wire:target="nextStep" message="Processing..." />
                <x-global.loader wire:loading.flex wire:target="previousStep" message="Loading previous step..." />
                <x-global.loader wire:loading.flex wire:target="save" message="Saving..." />
                <x-global.loader wire:loading.flex wire:target="ifsc" message="Fetching bank details..." />
                <!-- Step 0: E-PIN Assignment -->
                @if($currentStep === 0)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Assign Your E-PIN</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">E-PIN</label>
                            <input type="text" wire:model.live="e_pin" maxlength="6" class="mt-1 block w-full rounded-lg border-2 border-gray-200 px-3 py-2" placeholder="Enter 6-digit E-PIN">
                            @error('e_pin') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            <p class="mt-1 text-xs text-gray-500">E-PIN must belong to your account and be unused</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Step 1: Personal Information -->
                @if($currentStep === 1)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Personal Information</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" wire:model.live="name" class="mt-1 block w-full rounded-lg border-2 border-gray-200 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">Must be at least 3 characters long</p>
                            @error('name') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" wire:model.live="email" class="mt-1 block w-full rounded-lg border-2 border-gray-200 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">Must be a valid email address</p>
                            @error('email') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mobile Number</label>
                            <input type="tel" wire:model.live="mobile" class="mt-1 block w-full rounded-lg border-2 border-gray-200 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">10 digit number starting with 6-9</p>
                            @error('mobile') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                            <input type="tel" wire:model="whatsapp" class="mt-1 block w-full rounded-lg border-2 border-gray-200 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">Optional. If different from mobile number</p>
                            @error('whatsapp') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                       
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" wire:model="date_of_birth" class="mt-1 block w-full rounded-lg border-2 border-gray-200 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">Must be at least 18 years old</p>
                            @error('date_of_birth') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gender</label>
                            <select wire:model="gender" class="mt-1 block w-full rounded-lg border-2 border-gray-200 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Step 2: Family Details -->
                @if($currentStep === 2)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Family Details</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Father's Name</label>
                            <input type="text" wire:model="father_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                            @error('father_name') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mother's Name</label>
                            <input type="text" wire:model="mother_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                            @error('mother_name') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nationality</label>
                            <select wire:model="nationality" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                                <option value="">Select Nationality</option>
                                <option value="Indian">Indian</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('nationality') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Marital Status</label>
                            <select wire:model="marital_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                                <option value="">Select Status</option>
                                <option value="single">Single</option>
                                <option value="married">Married</option>
                                <option value="divorced">Divorced</option>
                                <option value="widowed">Widowed</option>
                            </select>
                            @error('marital_status') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Religion</label>
                            <select wire:model="religion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                                <option value="">Select Religion</option> 
                                <option value="Hindu">Hindu</option>
                                <option value="Muslim">Muslim</option>
                                <option value="Christian">Christian</option>
                                <option value="Sikh">Sikh</option>
                                <option value="Buddhist">Buddhist</option>
                                <option value="Jain">Jain</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('religion') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                @endif

                <!-- Step 3: Address -->
                @if($currentStep === 3)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Address Details</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Home Address</label>
                            <textarea wire:model="home_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2"></textarea>
                            @error('home_address') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text"
                                    wire:model="city"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                                @error('city') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text"
                                    wire:model="state"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                                @error('state') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Step 4: Nominee -->
                @if($currentStep === 4)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Nominee Details</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nominee Name</label>
                            <input type="text" wire:model="nominee_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                            @error('nominee_name') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Relation with Nominee</label>
                            <select wire:model="nominee_relation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                                <option value="">Select Relation</option>
                                @foreach($nominee_relations as $relation)
                                    <option value="{{ $relation }}">{{ $relation }}</option>
                                @endforeach
                            </select>
                            @error('nominee_relation') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                @endif

                <!-- Step 5: Bank Details -->
                @if($currentStep === 5)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Bank Details</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">IFSC Code</label>
                            <div class="relative">
                                <input type="text"
                                    wire:model.blur="ifsc"
                                    maxlength="11"
                                    pattern="^[A-Z]{4}0[A-Z0-9]{6}$"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2 uppercase"
                                    placeholder="Enter IFSC code">
                                <div wire:loading wire:target="ifsc" class="absolute right-2 top-3">
                                    <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">11 character bank IFSC code</p>
                            @error('ifsc') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bank Name</label>
                            <input type="text" wire:model="bank_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                            @error('bank_name') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Branch Name</label>
                            <input type="text" wire:model="branch_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                            @error('branch_name') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Account Number</label>
                            <input type="text" wire:model="account_no" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                            @error('account_no') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>


                    </div>
                </div>
                @endif

                <!-- Step 6: Documents -->
                @if($currentStep === 6)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Document Details</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">PAN Card Number</label>
                            <input type="text" wire:model="pancard" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                            <p class="mt-1 text-xs text-gray-500">Valid 10 character PAN number</p>
                            @error('pancard') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Aadhar Card Number</label>
                            <input type="text" wire:model="aadhar_card" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                            <p class="mt-1 text-xs text-gray-500">Valid 12 digit Aadhar number</p>
                            @error('aadhar_card') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                @endif

                <!-- Step 7: Photo Upload and Terms -->
                @if($currentStep === 7)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Photo Upload & Terms</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Profile Photo</label>
                            <div class="mt-2 flex items-center space-x-4">
                                @if($existingImage && !$image)
                                    <div class="relative">
                                        <img src="{{ Storage::url($existingImage) }}"
                                             class="h-24 w-24 object-cover rounded-lg border-2 border-gray-200">
                                        <button type="button"
                                                onclick="document.getElementById('photo-upload').click()"
                                                class="absolute -bottom-2 -right-2 p-1.5 rounded-full bg-indigo-600 text-white hover:bg-indigo-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif

                                @if($image)
                                    <div class="relative">
                                        <img src="{{ $image->temporaryUrl() }}"
                                             class="h-24 w-24 object-cover rounded-lg border-2 border-gray-200">
                                        <button type="button"
                                                wire:click="$set('image', null)"
                                                class="absolute -bottom-2 -right-2 p-1.5 rounded-full bg-red-600 text-white hover:bg-red-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <input type="file"
                                           id="photo-upload"
                                           wire:model="image"
                                           accept="image/*"
                                           class="hidden">
                                    <button type="button"
                                            onclick="document.getElementById('photo-upload').click()"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        {{ $existingImage || $image ? 'Change Photo' : 'Upload Photo' }}
                                    </button>
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG up to 1MB</p>
                                </div>
                            </div>
                            @error('image') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" wire:model="terms_and_condition"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <div class="ml-3">
                                <label class="text-sm text-gray-700">
                                    I accept the <a href="#" class="text-indigo-600 hover:text-indigo-500">terms and conditions</a>
                                </label>
                                @error('terms_and_condition') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-8">
                    @if($currentStep > 1)
                        <button type="button" wire:click="previousStep" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Previous
                        </button>
                    @endif

                    <div class="ml-auto flex gap-2">
                        <button type="button" wire:click="save" class="px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-100 border border-indigo-200 rounded-md hover:bg-indigo-200">
                            Save
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                            {{ $currentStep < 7 ? 'Next' : 'Complete Registration' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
