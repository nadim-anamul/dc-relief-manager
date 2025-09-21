<x-main-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('relief-applications.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Submit Relief Application</h1>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-responsive-card 
            title="Relief Application Form"
            subtitle="Please fill out all required fields to submit your relief application."
        >
            <form 
                action="{{ route('relief-applications.store') }}" 
                method="POST" 
                enctype="multipart/form-data" 
                class="space-y-8"
                x-data="reliefApplicationForm()"
                @submit="loading = true"
            >
                @csrf

                <!-- Organization Information Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="mr-2 h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Organization Information
                    </h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Organization Name -->
                        <div class="lg:col-span-2">
                            <x-form-input
                                id="organization_name"
                                name="organization_name"
                                label="Organization Name"
                                placeholder="Enter organization name"
                                required
                                :error="$errors->first('organization_name')"
                                help="Enter the full name of your organization"
                            />
                        </div>

                        <!-- Organization Type -->
                        <div class="lg:col-span-2">
                            <x-form-select
                                id="organization_type_id"
                                name="organization_type_id"
                                label="Organization Type"
                                placeholder="Select organization type"
                                required
                                :error="$errors->first('organization_type_id')"
                                :options="$organizationTypes->pluck('name', 'id')->toArray()"
                                help="Select the type of organization you represent"
                            />
                        </div>

                        <!-- Application Date -->
                        <div>
                            <x-form-input
                                id="date"
                                name="date"
                                type="date"
                                label="Application Date"
                                required
                                :error="$errors->first('date')"
                                help="Date of application submission"
                            />
                        </div>

                        <!-- Relief Type -->
                        <div>
                            <x-form-select
                                id="relief_type_id"
                                name="relief_type_id"
                                label="Relief Type"
                                placeholder="Select relief type"
                                required
                                :error="$errors->first('relief_type_id')"
                                :options="$reliefTypes->pluck('name', 'id')->toArray()"
                                help="Select the type of relief needed"
                            />
                        </div>
                    </div>
                </div>

                <!-- Location Information Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="mr-2 h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Location Information
                    </h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- District -->
                        <div>
                            <x-form-select
                                id="zilla_id"
                                name="zilla_id"
                                label="District"
                                placeholder="Select district"
                                required
                                :error="$errors->first('zilla_id')"
                                :options="$zillas->pluck('name', 'id')->toArray()"
                                help="Select the district where relief is needed"
                                x-model="selectedZilla"
                                @change="loadUpazilas()"
                            />
                        </div>

                        <!-- Upazila -->
                        <div>
                            <x-form-select
                                id="upazila_id"
                                name="upazila_id"
                                label="Upazila"
                                placeholder="Select upazila"
                                required
                                :error="$errors->first('upazila_id')"
                                :options="[]"
                                help="Select the upazila where relief is needed"
                                x-model="selectedUpazila"
                                @change="loadUnions()"
                                :disabled="!selectedZilla"
                            />
                        </div>

                        <!-- Union -->
                        <div>
                            <x-form-select
                                id="union_id"
                                name="union_id"
                                label="Union"
                                placeholder="Select union"
                                required
                                :error="$errors->first('union_id')"
                                :options="[]"
                                help="Select the union where relief is needed"
                                x-model="selectedUnion"
                                @change="loadWards()"
                                :disabled="!selectedUpazila"
                            />
                        </div>

                        <!-- Ward -->
                        <div>
                            <x-form-select
                                id="ward_id"
                                name="ward_id"
                                label="Ward"
                                placeholder="Select ward"
                                required
                                :error="$errors->first('ward_id')"
                                :options="[]"
                                help="Select the ward where relief is needed"
                                :disabled="!selectedUnion"
                            />
                        </div>
                    </div>
                </div>

                <!-- Application Details Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="mr-2 h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Application Details
                    </h4>
                    
                    <div class="space-y-6">
                        <!-- Subject -->
                        <div>
                            <x-form-input
                                id="subject"
                                name="subject"
                                label="Subject"
                                placeholder="Brief description of the relief request"
                                required
                                :error="$errors->first('subject')"
                                help="Provide a clear, concise subject for your application"
                            />
                        </div>

                        <!-- Details -->
                        <div>
                            <x-form-textarea
                                id="details"
                                name="details"
                                label="Detailed Description"
                                placeholder="Provide detailed information about the relief needed, including the situation, number of people affected, and specific requirements"
                                required
                                :error="$errors->first('details')"
                                help="Provide comprehensive details about the relief needed (minimum 50 characters)"
                                rows="5"
                            />
                        </div>

                        <!-- Amount Requested -->
                        <div>
                            <x-form-input
                                id="amount_requested"
                                name="amount_requested"
                                type="number"
                                label="Amount Requested (৳)"
                                placeholder="Enter amount in Bangladeshi Taka"
                                required
                                :error="$errors->first('amount_requested')"
                                help="Enter the amount of financial assistance needed (minimum ৳1,000)"
                                min="1000"
                                max="10000000"
                                step="100"
                            />
                        </div>
                    </div>
                </div>

                <!-- Applicant Information Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="mr-2 h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Applicant Information
                    </h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Applicant Name -->
                        <div>
                            <x-form-input
                                id="applicant_name"
                                name="applicant_name"
                                label="Applicant Name"
                                placeholder="Enter your full name"
                                required
                                :error="$errors->first('applicant_name')"
                                help="Enter the full name of the person submitting this application"
                            />
                        </div>

                        <!-- Applicant Designation -->
                        <div>
                            <x-form-input
                                id="applicant_designation"
                                name="applicant_designation"
                                label="Designation/Position"
                                placeholder="Enter your position in the organization"
                                :error="$errors->first('applicant_designation')"
                                help="Your position or role in the organization"
                            />
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <x-form-input
                                id="applicant_phone"
                                name="applicant_phone"
                                type="tel"
                                label="Phone Number"
                                placeholder="01XXXXXXXXX"
                                required
                                :error="$errors->first('applicant_phone')"
                                help="Enter a valid Bangladeshi mobile number"
                            />
                        </div>

                        <!-- Applicant Address -->
                        <div class="lg:col-span-2">
                            <x-form-textarea
                                id="applicant_address"
                                name="applicant_address"
                                label="Applicant Address"
                                placeholder="Enter your complete address"
                                required
                                :error="$errors->first('applicant_address')"
                                help="Your complete residential address"
                                rows="3"
                            />
                        </div>

                        <!-- Organization Address -->
                        <div class="lg:col-span-2">
                            <x-form-textarea
                                id="organization_address"
                                name="organization_address"
                                label="Organization Address"
                                placeholder="Enter organization's complete address"
                                required
                                :error="$errors->first('organization_address')"
                                help="Complete address of your organization"
                                rows="3"
                            />
                        </div>
                    </div>
                </div>

                <!-- File Upload Section -->
                <div class="pb-8">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="mr-2 h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Supporting Documents
                    </h4>
                    
                    <div>
                        <label for="application_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Supporting Documents
                            <span class="text-gray-500">(Optional)</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="application_file" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a file</span>
                                        <input id="application_file" name="application_file" type="file" class="sr-only" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" @change="handleFileSelect($event)">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    PDF, DOC, DOCX, JPG, JPEG, PNG up to 10MB
                                </p>
                                <p x-show="selectedFile" class="text-sm text-green-600 dark:text-green-400" x-text="selectedFile"></p>
                            </div>
                        </div>
                        @error('application_file')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('relief-applications.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <x-loading-button 
                        type="submit" 
                        variant="primary" 
                        size="lg"
                        :loading="false"
                        loading-text="Submitting Application..."
                        icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 19l9 2-9-18-9 18 9-2zm0 0v-8'></path>"
                    >
                        Submit Application
                    </x-loading-button>
                </div>
            </form>
        </x-responsive-card>
    </div>

    <script>
        function reliefApplicationForm() {
            return {
                selectedZilla: '',
                selectedUpazila: '',
                selectedUnion: '',
                selectedWard: '',
                selectedFile: '',
                loading: false,

                async loadUpazilas() {
                    if (!this.selectedZilla) return;
                    
                    try {
                        const response = await fetch(`/upazilas-by-zilla/${this.selectedZilla}`);
                        const upazilas = await response.json();
                        
                        const select = document.getElementById('upazila_id');
                        select.innerHTML = '<option value="">Select upazila</option>';
                        
                        upazilas.forEach(upazila => {
                            const option = document.createElement('option');
                            option.value = upazila.id;
                            option.textContent = upazila.name;
                            select.appendChild(option);
                        });
                        
                        this.selectedUpazila = '';
                        this.selectedUnion = '';
                        this.selectedWard = '';
                    } catch (error) {
                        console.error('Error loading upazilas:', error);
                    }
                },

                async loadUnions() {
                    if (!this.selectedUpazila) return;
                    
                    try {
                        const response = await fetch(`/unions-by-upazila/${this.selectedUpazila}`);
                        const unions = await response.json();
                        
                        const select = document.getElementById('union_id');
                        select.innerHTML = '<option value="">Select union</option>';
                        
                        unions.forEach(union => {
                            const option = document.createElement('option');
                            option.value = union.id;
                            option.textContent = union.name;
                            select.appendChild(option);
                        });
                        
                        this.selectedUnion = '';
                        this.selectedWard = '';
                    } catch (error) {
                        console.error('Error loading unions:', error);
                    }
                },

                async loadWards() {
                    if (!this.selectedUnion) return;
                    
                    try {
                        const response = await fetch(`/wards-by-union/${this.selectedUnion}`);
                        const wards = await response.json();
                        
                        const select = document.getElementById('ward_id');
                        select.innerHTML = '<option value="">Select ward</option>';
                        
                        wards.forEach(ward => {
                            const option = document.createElement('option');
                            option.value = ward.id;
                            option.textContent = ward.name;
                            select.appendChild(option);
                        });
                        
                        this.selectedWard = '';
                    } catch (error) {
                        console.error('Error loading wards:', error);
                    }
                },

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.selectedFile = `Selected: ${file.name}`;
                    } else {
                        this.selectedFile = '';
                    }
                }
            }
        }
    </script>
</x-main-layout>
