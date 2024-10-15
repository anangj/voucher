<x-app-layout>
    <div>
        <div class="items-center justify-between block mb-6 sm:flex">
            <x-breadcrumb :pageTitle="$pageTitle" :breadcrumbItems="$breadcrumbItems" />
            <div class="text-end">
                <a class="btn inline-flex justify-center btn-dark rounded-[25px] items-center !p-2 !px-3" href="{{ route('packages.index') }}">
                    <iconify-icon class="mr-1 text-lg" icon="ic:outline-arrow-back"></iconify-icon>
                    </iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>

        <form action="{{route('packages.store')}}" method="POST" class="max-w-4xl m-auto" enctype="multipart/form-data">
            @csrf
            <div class="p-5 pb-6 bg-white rounded-md dark:bg-slate-800">
                <div class="grid sm:grid-cols-1 gap-x-8 gap-y-4">

                    <div class="form-group">
                        <label for="name">Package Voucher Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="total_distribute">{{ __('Total Vouchers to Distribute') }}</label>
                        <input type="number" id="total_distribute" name="total_distribute" class="form-control" value="{{ old('total_distribute') }}" required min="1">
                        <small class="form-text text-muted">This defines how many vouchers will be given to each patient.</small>
                    </div>

                    <div class="form-group">
                        <label for="voucher_type">Voucher Type</label>
                        <select class="form-control" id="voucher_type" name="voucher_type" required>
                            <option value="discount">Discount</option>
                            <option value="service">Service</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="max_sharing">Max Sharing</label>
                        <input type="number" class="form-control" id="max_sharing" name="max_sharing" required min="0">
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required min="0">
                    </div>

                    <div class="form-group">
                        <label for="image">{{ __('Upload Image') }}</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="form-text text-muted">Upload an image representing the package voucher.</small>
                    </div>

                    <div class="form-group">
                        <label for="tnc">{{ __('Terms and Conditions') }}</label>
                        <textarea class="form-control" id="tnc" name="tnc" rows="5" required>{{ old('tnc') }}</textarea>
                        <small class="form-text text-muted">Provide the terms and conditions for this package voucher.</small>
                    </div>
                    <div class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                        <div class="flex items-center justify-between px-3 py-2 border-b dark:border-gray-600">
                            <!-- Custom toolbar with buttons -->
                            <div id="custom-toolbar">
                                <button type="button" class="ql-bold p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h1m-1 4v2h-1m-2-10h1v4H7v2h3v4h1V8H8z"/></svg>
                                </button>
                                <button type="button" class="ql-italic p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 6l-4.5 12m-3-12L7 18"/></svg>
                                </button>
                                <button type="button" class="ql-link p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14h7m5 0h3m-8 0a5 5 0 1 1 8 0 5 5 0 1 1-8 0z"/></svg>
                                </button>
                            </div>
                        </div>
                     
                        <div class="px-4 py-2 bg-white rounded-b-lg dark:bg-gray-800">
                            <div id="editor-container" class="block w-full h-64 px-0 text-sm text-gray-800 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400">
                                <!-- The Quill editor will be rendered here -->
                            </div>
                        </div>
                     </div>
                     

                </div>
                <button type="submit" class="inline-flex justify-center w-full mt-4 btn btn-dark">
                    {{ __('Create Package Voucher') }}
                </button>
            </div>
        </form>

    </div>
    @push('scripts')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <script>
            const toolbarOptions = ['bold', 'italic', 'underline', 'list'];
            var quill = new Quill('#editor-container', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions 
                }
            });
        
            // To get the editor content when submitting the form
            document.querySelector('form').onsubmit = function() {
                // Ensure the textarea gets the HTML content
                var editorContent = quill.root.innerHTML;
                document.querySelector('textarea[name="content"]').value = editorContent;
            };
        </script>
        
    @endpush
    
</x-app-layout>
