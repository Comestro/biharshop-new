<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Admin Panel</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Sign in to your administrator account
            </p>
        </div>

        <div class="mt-8 bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            @if (session()->has('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-sm text-red-600 rounded-md p-4">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit="login" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email address</label>
                    <div class="mt-1">
                        <input wire:model="email" type="email" required 
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1">
                        <input wire:model="password" type="password" required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    @error('password') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Sign in
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                    ← Back to homepage
                </a>
            </div>
        </div>
    </div>
</div>
