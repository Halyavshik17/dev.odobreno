<x-app-layout>
    <x-card>
        {{-- <x-slot name='actions'>
            <a href="" class="btn btn-success btn-sm">
                <i class="fa fa-plus-circle"></i>&nbsp;
                {{ localize('Add User') }}
            </a>
        </x-slot> --}}

        <div>
            <x-data-table :dataTable="$dataTable" />
        </div>
    </x-card>

</x-app-layout>
