<x-app-layout>
    <div>
        <div class="mb-6 ">
            <x-breadcrumb :pageTitle="$pageTitle" :breadcrumbItems="$breadcrumbItems" />
        </div>


    </div>
</x-app-layout>

<form action="{{route('packages.store')}}" method="POST" class="max-w-4xl m-auto">
    @csrf
    <div class="p-5 pb-6 bg-white rounded-md dark:bg-slate-800">
        <div class="grid sm:grid-cols-1 gap-x-8 gap-y-4">

        </div>
    </div>
</form>
