<div class="max-w-xl mx-auto bg-white shadow-md rounded-xl p-6">

    <h2 class="text-xl font-semibold text-indigo-700 mb-4">{{ Auth::user()->membership->epin_password == null ? 'Create' : 'Change' }} E-Pin Password</h2>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (Auth::user()->membership->epin_password == null)
        <form wire:submit.prevent="createPassword" class="space-y-4">

            {{-- New Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" wire:model.defer="new_password"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                @error('new_password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" wire:model.defer="new_password_confirmation"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg font-medium hover:bg-indigo-700 transition">
                Change Password
            </button>

        </form>
    @else

        <form wire:submit.prevent="changePassword" class="space-y-4">

            {{-- Current Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                <input type="password" wire:model.defer="current_password"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                @error('current_password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- New Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" wire:model.defer="new_password"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                @error('new_password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" wire:model.defer="new_password_confirmation"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg font-medium hover:bg-indigo-700 transition">
                Change Password
            </button>

        </form>
    @endif
</div>