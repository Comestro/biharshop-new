<!-- Card (keep this in your Blade view) -->
<div id="memberCard" class="relative w-full max-w-sm mx-auto">
    <button onclick="downloadCardAsPng()"
        class="absolute -top-4 -right-4 bg-white text-teal-700 shadow-lg rounded-full p-2 hover:scale-110 transition z-10">
        <!-- download icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
        </svg>
    </button>
    <div class="w-full max-w-xs mx-auto bg-white border border-gray-300 shadow-xl rounded-xl overflow-hidden">

        <!-- TOP HEADER / LOGO -->
        <div class="p-4 pb-2 text-center">
            <img src="https://via.placeholder.com/80x60?text=LOGO" class="mx-auto h-14 object-contain" alt="Logo">

            <h2 class="text-lg font-semibold text-gray-800">BIHARSHOP PVT. LTD.</h2>
            <p class="text-xs text-gray-500 -mt-1">Membership Identity Card</p>
        </div>

        <!-- PHOTO -->
        <div class="flex justify-center mt-3">
            <div class="h-28 w-24 border border-gray-300 rounded-md overflow-hidden shadow">
                <img src="https://via.placeholder.com/200x240.png?text=Photo" class="h-full w-full object-cover"
                    alt="Profile Photo">
            </div>
        </div>

        <!-- DETAILS SECTION -->
        <div class="px-5 mt-4 text-sm">

            <div class="flex justify-between py-1 border-b border-gray-200">
                <span class="font-medium text-gray-700">Name</span>
                <span class="text-gray-800">Ankur Kumar</span>
            </div>

            <div class="flex justify-between py-1 border-b border-gray-200">
                <span class="font-medium text-gray-700">Member ID</span>
                <span class="text-gray-800">MBR102934</span>
            </div>

            <div class="flex justify-between py-1 border-b border-gray-200">
                <span class="font-medium text-gray-700">Blood Group</span>
                <span class="text-gray-800">B+</span>
            </div>

            <div class="flex justify-between py-1 border-b border-gray-200">
                <span class="font-medium text-gray-700">Mobile No</span>
                <span class="text-gray-800">+91 9876543210</span>
            </div>

        </div>

        <!-- SIGNATURE -->
        <div class="text-center mt-5 mb-1">
            <img src="https://via.placeholder.com/80x40?text=Sign" class="mx-auto opacity-80" alt="Signature">

            <p class="text-xs text-gray-600 mt-1">Authorised Signatory</p>
        </div>

        <!-- BOTTOM FOOTER -->
        <div class="bg-blue-700 text-white text-center text-xs p-3 mt-3">
            <p class="font-semibold">BIHARSHOP PVT. LTD.</p>

            <p>Add: Patna, Bihar - 800001</p>
            <p>Tel: +91 6200XXXXXX &nbsp; | &nbsp; Mob: +91 98XXXXXX21</p>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<script>
    window.downloadCardAsPng = function () {
        const card = document.getElementById('memberCard');
        html2canvas(card, { scale: 3, backgroundColor: null }).then((canvas) => {
            const link = document.createElement('a');
            link.download = 'member-card.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    };
</script>