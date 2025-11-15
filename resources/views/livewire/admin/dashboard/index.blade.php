<div class="w-full">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 px-4 sm:px-0">
            Welcome to Admin Dashboard
        </h2>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 px-4 sm:px-0">
            <!-- Total Members -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl transition duration-300 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-50">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dd class="flex items-baseline">
                                    <div class="text-3xl font-bold text-gray-900">
                                        {{ $stats['total_members'] }}
                                    </div>
                                </dd>
                                <dt class="text-sm font-medium text-gray-500 truncate mt-2">
                                    Total Members
                                </dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Verifications -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl transition duration-300 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-50">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dd class="flex items-baseline">
                                    <div class="text-3xl font-bold text-gray-900">
                                        {{ $stats['pending_verifications'] }}
                                    </div>
                                </dd>
                                <dt class="text-sm font-medium text-gray-500 truncate mt-2">
                                    Pending Verifications
                                </dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl transition duration-300 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-50">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dd class="flex items-baseline">
                                    <div class="text-3xl font-bold text-gray-900">
                                        {{ $stats['total_products'] }}
                                    </div>
                                </dd>
                                <dt class="text-sm font-medium text-gray-500 truncate mt-2">
                                    Total Products
                                </dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Categories -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl transition duration-300 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-50">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dd class="flex items-baseline">
                                    <div class="text-3xl font-bold text-gray-900">
                                        {{ $stats['total_categories'] }}
                                    </div>
                                </dd>
                                <dt class="text-sm font-medium text-gray-500 truncate mt-2">
                                    Total Categories
                                </dt>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
