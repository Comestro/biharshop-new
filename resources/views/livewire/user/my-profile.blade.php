<!-- Profile Page with Sidebar (Tailwind CSS, No JS) -->
<!-- Add Font Awesome CDN in <head> -->
<!-- <script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script> -->

<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div
        class="max-w-6xl mx-auto bg-white rounded-xl shadow border border-gray-200 flex flex-col md:flex-row overflow-hidden">

        <!-- Sidebar -->
        <livewire:user.component.sidebar />


        <!-- Main Profile Content -->
        <main class="flex-1 p-6">
            <!-- Profile Header -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6 mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-24 h-24 rounded-full overflow-hidden border border-gray-200 shadow-sm">
                        <img src="https://via.placeholder.com/150" alt="Profile" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Ankur Jha</h2>
                        <p class="text-gray-600 text-sm">ankur@example.com</p>
                        <p class="text-gray-600 text-sm mt-1">+91 98765 43210</p>
                    </div>
                </div>
                <a href="#"
                    class="bg-teal-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-teal-800 transition">Edit
                    Profile</a>
            </div>

            <!-- Profile Form -->
            <form action="#" method="POST" class="space-y-8">
                <!-- Personal Info -->
                <div>
                    <h3 class="text-gray-800 font-medium mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Full Name</label>
                            <input type="text" name="name" value="Ankur Jha"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 shadow-sm focus:ring-teal-300 focus:border-teal-500 outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Email</label>
                            <input type="email" name="email" value="ankur@example.com"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 shadow-sm focus:ring-teal-300 focus:border-teal-500 outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Phone</label>
                            <input type="text" name="phone" value="+91 98765 43210"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 shadow-sm focus:ring-teal-300 focus:border-teal-500 outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Date of Birth</label>
                            <input type="date" name="dob" value="1999-01-01"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 shadow-sm focus:ring-teal-300 focus:border-teal-500 outline-none" />
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <h3 class="text-gray-800 font-medium mb-3">Address</h3>
                    <textarea name="address" rows="3"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 shadow-sm focus:ring-teal-300 focus:border-teal-500 outline-none">12, Gandhi Nagar, Patna, Bihar - 800001</textarea>
                </div>

                <!-- Change Password -->
                <div class="border-t border-gray-100 pt-6">
                    <h3 class="text-gray-800 font-medium mb-4">Change Password</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Current Password</label>
                            <input type="password" name="current_password"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 shadow-sm focus:ring-teal-300 focus:border-teal-500 outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">New Password</label>
                            <input type="password" name="new_password"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 shadow-sm focus:ring-teal-300 focus:border-teal-500 outline-none" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Confirm Password</label>
                            <input type="password" name="confirm_password"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 shadow-sm focus:ring-teal-300 focus:border-teal-500 outline-none" />
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="bg-teal-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-teal-800 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>