<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full space-y-8">
        <form wire:submit.prevent="login" class="mt-8 space-y-6">
            <div>
                <label for="email">Email</label>
                <input wire:model="email" type="email" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300">
                @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password">Password</label>
                <input wire:model="password" type="password" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300">
                @error('password') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label>
                    <input wire:model="remember" type="checkbox">
                    Remember me
                </label>
            </div>

            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                Sign in
            </button>
        </form>
    </div>
</div>
