<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl mx-auto">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Complete Your Membership</h2>
            <p class="mt-2 text-gray-600">Make the payment to activate your membership</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 sm:p-8">
            <!-- Payment Information -->
            <div class="mb-8 p-4 bg-blue-50 rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm font-medium text-gray-700">Membership Fee</span>
                    <span class="text-lg font-bold text-blue-600">₹{{ number_format($amount, 2) }}</span>
                </div>
                <div class="space-y-1 text-sm text-gray-600">
                    <p>Please make the payment using one of these methods:</p>
                    <ul class="list-disc list-inside mt-2">
                        <li>UPI: biharshopecommerceprivatelimited@sbi</li>
                        <li>QR Code (scan below)</li>
                    </ul>
                </div>
            </div>

            <!-- QR Code Placeholder -->
            <div class="flex justify-center mb-8">
                <div class="p-4 border-2 border-dashed border-gray-300 rounded-lg">
                    <p class="text-gray-500">Payment QR Code</p>
                    <img src="{{ asset("qr.png") }}" alt="">
                </div>
            </div>

            <!-- Payment Confirmation Form -->
            <form wire:submit.prevent="submitPayment">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Transaction Number / UTR
                        </label>
                        <input type="text"
                               wire:model="transaction_no"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2"
                               placeholder="Enter your payment reference number">
                        @error('transaction_no')
                            <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Confirm Payment
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Text -->
        <p class="mt-4 text-center text-sm text-gray-600">
            Having trouble? <a href="#" class="text-indigo-600 hover:text-indigo-500">Contact support</a>
        </p>
    </div>
</div>
