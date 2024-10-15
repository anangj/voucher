<x-app-layout>
    <div>
        <div class="mb-6 ">
            {{-- Breadcrumb --}}
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
                    @can('role create')
                    <a class="btn inline-flex justify-center btn-dark rounded-[25px] items-center !p-2 !px-3" href="{{ route('roles.create') }}">
                        <iconify-icon icon="ic:round-plus" class="mr-1 text-lg">
                        </iconify-icon>
                        {{ __('New') }}
                    </a>
                    @endcan
                    {{--Refresh Button start--}}
                    <a class="btn inline-flex justify-center btn-dark rounded-[25px] items-center !p-2.5" href="{{ route('roles.index') }}">
                        <iconify-icon icon="mdi:refresh" class="text-xl "></iconify-icon>
                    </a>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-3 sm:flex lg:justify-end">
                    <div class="relative flex items-center w-full sm:w-auto">
                        <form id="searchForm" method="get" action="{{ route('roles.index') }}">
                            <input name="q" type="text" class="p-2 pl-8 border rounded-md inputField border-slate-200 dark:border-slate-700 dark:bg-slate-900" placeholder="Search" value="{{ request()->q }}">
                        </form>
                        <iconify-icon class="absolute text-textColor left-2 dark:text-white" icon="quill:search-alt"></iconify-icon>
                    </div>
                </div>
            </header>
            <div class="px-6 pb-6 card-body">
                <div class="-mx-6 overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden ">
                            <table class="min-w-full divide-y table-fixed divide-slate-100 dark:divide-slate-700">
                                <thead class="bg-slate-200 dark:bg-slate-700">
                                    <tr>
                                        <th scope="col" class="table-th ">
                                            {{ __('Sl No') }}
                                        </th>
                                        <th scope="col" class="table-th ">
                                            {{ __('Name') }}
                                        </th>
                                        <th scope="col" class="table-th ">
                                            {{ __('Created At') }}
                                        </th>
                                        <th scope="col" class="w-20 table-th">
                                            {{ __('Action') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                    @forelse ($roles as $role)
                                    <tr class="relative border border-slate-100 dark:border-slate-900">
                                        <td class="sticky left-0 table-td"># {{ $role->id }}</td>
                                        <td class="table-td">
                                            <span>{{ $role->name }}</span>
                                        </td>
                                        <td class="table-td">{{ $role->created_at->toFormattedDateString() }}</td>
                                        <td class="table-td">
                                            <div class="flex space-x-2 action-btns">
                                                {{-- show --}}
                                                @can('role show')
                                                <a class="action-btn" href="{{ route('roles.show', $role) }}" x-data="{ tooltip: 'View' }" x-tooltip="tooltip">
                                                    <iconify-icon icon="ph:eye-light"></iconify-icon>
                                                </a>
                                                @endcan
                                                {{-- Edit --}}
                                                @can('role update')
                                                <a class="action-btn" href="{{ route('roles.edit', ['role' => $role]) }}">
                                                    <iconify-icon icon="uil:edit"></iconify-icon>
                                                </a>
                                                @endcan
                                                {{-- delete --}}
                                                @can('role delete')
                                                <form id="deleteForm{{ $role->id }}" method="POST" action="{{ route('roles.destroy', $role) }}" class="cursor-pointer">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="action-btn" onclick="sweetAlertDelete(event, 'deleteForm{{ $role->id }}')" type="submit">
                                                        <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                                    </a>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
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
                            <x-table-footer :per-page-route-name="'roles.index'" :data="$roles" />
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
