<x-app-layout>
    <div>
        <div class="mb-6 ">
            <x-breadcrumb :pageTitle="$pageTitle" :breadcrumbItems="$breadcrumbItems" />
        </div>

        {{-- Alert start --}}
        @if (session('message'))
        <x-alert :message="session('message')" :type="'success'" />
        @endif
        {{-- Alert end --}}

        <div class="card">
            <header class=" card-header noborder">
                <div class="flex flex-wrap items-center justify-end gap-3">
                    {{-- Create Button start--}}
                    @can('package_voucher create')
                    <a class="btn inline-flex justify-center btn-dark rounded-[25px] items-center !p-2 !px-3" href="{{ route('packages.create') }}">
                        <iconify-icon icon="ic:round-plus" class="mr-1 text-lg">
                        </iconify-icon>
                        {{ __('New') }}
                    </a>
                    @endcan
                    {{--Refresh Button start--}}
                    <a class="btn inline-flex justify-center btn-dark rounded-[25px] items-center !p-2.5" href="{{ route('packages.index') }}">
                        <iconify-icon icon="mdi:refresh" class="text-xl "></iconify-icon>
                    </a>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-3 sm:flex lg:justify-end">
                    <div class="relative flex items-center w-full sm:w-auto">
                        <form id="searchForm" method="get" action="{{ route('packages.index') }}">
                            <input name="q" type="text" class="p-2 pl-8 border rounded-md inputField border-slate-200 dark:border-slate-700 dark:bg-slate-900" placeholder="Search" value="{{ request()->q }}">
                        </form>
                        <iconify-icon class="absolute text-textColor left-2 dark:text-white" icon="quill:search-alt"></iconify-icon>
                    </div>
                </div>
            </header>
            <div class="px-6 pb-6 card-body">
                <div class="-mx-6 overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="w-full divide-y table-fixed divide-slate-100 dark:divide-slate-700">
                                <thead class="bg-slate-200 dark:bg-slate-700">
                                    <tr>
                                        <th scope="col" class="table-th ">
                                            {{ __('Name') }}
                                        </th>
                                        {{-- <th scope="col" class="table-th ">
                                            {{ __('Description') }}
                                        </th> --}}
                                        <th scope="col" class="table-th ">
                                            {{ __('Total Distribute') }}
                                        </th>
                                        {{-- <th scope="col" class="table-th ">
                                            {{ __('Maximal Uses') }}
                                        </th> --}}
                                        <th scope="col" class="table-th ">
                                            {{ __('Maximal Sharing') }}
                                        </th>
                                        <th scope="col" class="table-th">
                                            {{ __('Action') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                    @forelse ($data as $item)
                                        <tr>
                                            <td class="table-td">
                                                {{$item->name}}
                                            </td>
                                            {{-- <td class="table-td">
                                                {{$item->description}}
                                            </td> --}}
                                            <td class="table-td">
                                                {{$item->total_distribute}}
                                            </td>
                                            {{-- <td class="table-td">
                                                {{$item->max_uses}}
                                            </td> --}}
                                            <td class="table-td">
                                                {{$item->max_sharing}}
                                            </td>
                                            <td class="table-td">
                                                <div class="flex space-x-1">
                                                    {{-- @can('voucher update') --}}
                                                    <a href="{{ route('packages.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                                        <iconify-icon icon="mdi:pencil-outline" class="text-xl"></iconify-icon>
                                                    </a>
                                                    {{-- @endcan --}}
    
                                                    {{-- @can('voucher show') --}}
                                                    <a href="{{ route('packages.show', $item->id) }}" class="btn btn-sm btn-primary">
                                                        <iconify-icon icon="mdi:eye-outline" class="text-xl"></iconify-icon>
                                                    </a>
                                                    {{-- @endcan --}}
    
                                                    {{-- @if ($voucher->status === 'unassigned')
                                                    <a href="{{ route('vouchers.assign', ['paketVoucherId' => $voucher->paket_voucher_id]) }}" class="btn btn-sm btn-success">
                                                        {{ __('Assign') }}
                                                    </a>
                                                    @endif --}}
    
                                                    {{-- @can('voucher delete') --}}
                                                    <form id="deleteForm{{$item->id}}" action="{{ route('packages.destroy', $item->id) }}" method="POST" onclick="sweetAlertDelete(event, 'deleteForm{{ $item->id }}')" type="submit">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <iconify-icon icon="mdi:delete-outline" class="text-xl"></iconify-icon>
                                                        </button>
                                                    </form>
                                                    {{-- @endcan --}}
                                                </div>
                                            </td>
                                            {{-- <td class="table-td">
                                                <div>
                                                    <div class="relative">
                                                      <div class="relative dropdown">
                                                        <button
                                                          class="block w-full text-xl text-center "
                                                          type="button"
                                                          id="tableDropdownMenuButton{{$item['id']}}"
                                                          data-bs-toggle="dropdown"
                                                          aria-expanded="false">
                                                          <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                                                        </button>
                                                        <ul class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700
                                                            shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                          <li>
                                                            <a
                                                              href="{{ route('packages.show', $item) }}"
                                                              class="block px-4 py-2 font-normal text-slate-600 dark:text-white font-Inter hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                              View</a>
                                                          </li>
                                                          <li>
                                                            <a
                                                              href="{{ route('packages.edit', ['package' => $item]) }}"
                                                              class="block px-4 py-2 font-normal text-slate-600 dark:text-white font-Inter hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                              Edit</a>
                                                          </li>
                                                          <li>
                                                            <form id="deleteForm{{ $item->id }}" method="POST" action="{{ route('packages.destroy', $item) }}" class="cursor-pointer">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a class="block px-4 py-2 font-normal text-slate-600 dark:text-white font-Inter hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white" onclick="sweetAlertDelete(event, 'deleteForm{{ $item->id }}')" type="submit">
                                                                    Delete
                                                                </a>
                                                            </form>
                                                          </li>
                                                        </ul>
                                                      </div>
                                                    </div>
                                                  </div>
                                            </td> --}}
                                        </tr>
                                    @empty
                                        <tr class="relative border border-slate-100 dark:border-slate-900">
                                            <td class="table-cell text-center" colspan="5">
                                                <img src="images/result-not-found.svg" alt="page not found" class="w-64 m-auto" />
                                                <h2 class="mb-8 -mt-4 text-xl text-slate-700">{{ __('No results found.') }}</h2>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        function sweetAlertDelete(event, formId) {
            event.preventDefault();
            let form = document.getElementById(formId);
            Swal.fire({
                title: '@lang('Are you sure ? ')',
                icon : 'question',
                showDenyButton: true,
                confirmButtonText: '@lang('Delete ')',
                denyButtonText: '@lang(' Cancel ')',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>
