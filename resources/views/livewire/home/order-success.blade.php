<div class="min-h-screen bg-gradient-to-br from-teal-50 to-white flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full text-center relative overflow-hidden">
        <!-- Floating glow circle background -->
        <div class="absolute -top-16 -right-16 w-40 h-40 bg-teal-200 rounded-full blur-3xl opacity-30 animate-pulse">
        </div>

        <!-- Animated Check Icon -->
        <div class="flex justify-center mb-6 relative">
            <div
                class="w-20 h-20 bg-gradient-to-br from-teal-500 to-teal-600 text-white rounded-full flex items-center justify-center animate-bounce-slow shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <!-- Heading -->
        <h2 class="text-3xl font-bold text-gray-800 mb-2 tracking-tight">Order Confirmed!</h2>
        <p class="text-gray-500 text-sm mb-8">We’ve received your order and it’s being processed.</p>

        <!-- Animated Card Info -->
        <div
            class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-5 text-left mb-8 shadow-inner animate-fade-in">
            <p class="text-sm text-gray-700"><span class="font-semibold">Order ID:</span> #ORD-852194</p>
            <p class="text-sm text-gray-700 mt-1"><span class="font-semibold">Payment:</span> UPI / Online</p>
            <p class="text-sm text-gray-700 mt-1"><span class="font-semibold">Expected Delivery:</span> 13 Oct, 2025</p>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="/orders"
                class="w-full bg-gradient-to-r from-teal-500 to-teal-600 text-white py-2.5 rounded-lg font-medium hover:opacity-90 transition transform hover:-translate-y-0.5">
                View My Orders
            </a>
            <a href="/shop"
                class="w-full border border-teal-600 text-teal-700 py-2.5 rounded-lg font-medium hover:bg-teal-50 transition transform hover:-translate-y-0.5">
                Continue Shopping
            </a>
        </div>

        <!-- Footer small note -->
        <p class="text-xs text-gray-400 mt-6">Need help? <a href="/support"
                class="text-teal-600 hover:underline">Contact Support</a></p>
    </div>
    <!-- Custom Animations -->
    <style>
        @keyframes bounce-slow {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        .animate-bounce-slow {
            animation: bounce-slow 2.5s infinite;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 1s ease-out;
        }
    </style>
</div>