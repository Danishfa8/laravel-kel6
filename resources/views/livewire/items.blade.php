<div class="p-6 sm:px-20 bg-white border-b border-gray-200">
    @if(session()->has('message'))
    <div class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3 relative" role="alert" x-data="{ show: true }" x-show="show">
        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/>
        </svg>
        <p>{{ session('message') }}</p>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="show = false">
            <svg class="fill-current h-6 w-6 text-white" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
        </span>
    </div>
    @endif

    <div class="mt-8 text-2xl flex justify-between">
        <div>Data Pajak</div>
        <div class="mr-2">
            <x-jet-button wire:click="confirmItemAction" class="bg-blue-500 hover:bg-blue-700">
                Add New Item
            </x-jet-button>
            <x-jet-button class="bg-blue-500 hover:bg-blue-700">
                <a href="{{ url('pajak_pdf') }}" target="_blank">CETAK PDF</a>
            </x-jet-button>
        </div>
    </div>

    <div class="mt-6">
        <div class="flex justify-between">
            <div>
                <input wire:model.debounce.500ms="q" type="search" placeholder="Search" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
        </div>

        <table class="table-auto w-full mt-6">
            <thead>
                <tr>
                    @foreach(['nomor', 'kode_pajak', 'nama_pajak', 'jenis_pajak', 'deskripsi', 'tarif_pajak', 'tanggal_berlaku'] as $field)
                    <th class="border px-4 py-2">
                        <div class="flex items-center">
                            <button wire:click="sortBy('{{ $field }}')">{{ ucfirst(str_replace('_', ' ', $field)) }}</button>
                            <x-sort-icon sortField="{{ $field }}" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                        </div>
                    </th>
                    @endforeach
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $item)
                <tr>
                    <th class="border px-4 py-2">{{ $index + 1 }}</th>                    
                    <td class="border px-4 py-2">{{ $item->kode_pajak }}</td>
                    <td class="border px-4 py-2">{{ $item->nama_pajak }}</td>
                    <td class="border px-4 py-2">{{ $item->jenis_pajak }}</td>
                    <td class="border px-4 py-2">{{ $item->deskripsi }}</td>
                    <td class="border px-4 py-2">{{ 'Rp ' . number_format($item->tarif_pajak, 0, ',', '.') }}</td>
                    <td class="border px-4 py-2">{{ $item->tanggal_berlaku }}</td>
                    <td class="border px-4 py-2">
                        <div class="flex space-x-2">
                            <x-jet-button wire:click="confirmItemAction({{ $item->id }})" class="bg-green-500 hover:bg-warning-700">Edit</x-jet-button>
                            <x-jet-button wire:click="confirmItemDeletion({{ $item->id }})" class="bg-red-500 hover:bg-warning-700" wire:loading.attr="disabled">Delete</x-jet-button>
                            <x-jet-button wire:click="show({{ $item->id }})" class="bg-blue-500 hover:bg-blue-700">Show</x-jet-button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>

    <!-- Confirmation Modals -->
    <x-jet-confirmation-modal wire:model="confirmingItemDeletion">
        <x-slot name="title">{{ __('Delete Item') }}</x-slot>
        <x-slot name="content">{{ __('Are you sure you want to delete this Item?') }}</x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('confirmingItemDeletion', false)" wire:loading.attr="disabled">{{ __('Cancel') }}</x-jet-secondary-button>
            <x-jet-danger-button class="ml-2" wire:click="deleteItem({{ $confirmingItemDeletion }})" wire:loading.attr="disabled">{{ __('Delete') }}</x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>

    <x-jet-dialog-modal wire:model="confirmingItemAddEdit">
        <x-slot name="title">{{ isset($this->item['id']) ? 'Edit Item' : 'Add Item' }}</x-slot>
        <x-slot name="content">
            @foreach(['kode_pajak', 'nama_pajak', 'jenis_pajak', 'deskripsi', 'tarif_pajak', 'tanggal_berlaku'] as $field)
            <div class="col-span-6 sm:col-span-4 mt-4">
                <x-jet-label for="{{ $field }}" value="{{ __(ucfirst(str_replace('_', ' ', $field))) }}" />
                @if($field === 'jenis_pajak')
                <select id="{{ $field }}" class="mt-1 block w-full" wire:model.defer="item.{{ $field }}">
                    <option value="">Pilih Jenis Pajak</option>
                    @foreach($jenisPajakOptions as $jp)
                    <option value="{{ $jp->jenis }}">{{ $jp->jenis }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.293 7.293a1 1 0 011.414 0L10 9.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                @elseif($field === 'deskripsi')
                <textarea id="{{ $field }}" class="mt-1 block w-full form-textarea" wire:model.defer="item.{{ $field }}" rows="4"></textarea>
                @else
                <x-jet-input id="{{ $field }}" type="{{ $field === 'tanggal_berlaku' ? 'date' : 'text' }}" class="mt-1 block w-full {{ $field === 'tarif_pajak' ? 'format-rupiah' : '' }}" wire:model.defer="item.{{ $field }}" />
                @endif
                <x-jet-input-error for="item.{{ $field }}" class="mt-2" />
            </div>
            @endforeach
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('confirmingItemAddEdit', false)" wire:loading.attr="disabled">{{ __('Cancel') }}</x-jet-secondary-button>
            <x-jet-danger-button class="ml-2" wire:click="saveItem()" wire:loading.attr="disabled">{{ isset($this->item['id']) ? 'Save' : 'Create' }}</x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="showingItem">
        <x-slot name="title">{{ __('Item Details') }}</x-slot>
        <x-slot name="content">
            @if ($item)
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="kode_pajak" value="{{ __('Kode Pajak') }}" />
                <x-jet-input id="kode_pajak" type="text" class="mt-1 block w-full" value="{{ $item->kode_pajak }}" readonly />
            </div>

            <div class="col-span-6 sm:col-span-4 mt-4">
                <x-jet-label for="nama_pajak" value="{{ __('Nama Pajak') }}" />
                <x-jet-input id="nama_pajak" type="text" class="mt-1 block w-full" value="{{ $item->nama_pajak }}" readonly />
            </div>

            <div class="col-span-6 sm:col-span-4 mt-4">
                <x-jet-label for="jenis_pajak" value="{{ __('Jenis Pajak') }}" />
                <x-jet-input id="jenis_pajak" type="text" class="mt-1 block w-full" value="{{ $item->jenis_pajak }}" readonly />
            </div>

            <div class="col-span-6 sm:col-span-4 mt-4">
                <x-jet-label for="deskripsi" value="{{ __('Deskripsi') }}" />
                <textarea id="deskripsi" class="mt-1 block w-full form-textarea" readonly>{{ $item->deskripsi }}</textarea>
            </div>

            <div class="col-span-6 sm:col-span-4 mt-4">
                <x-jet-label for="tarif_pajak" value="{{ __('Tarif Pajak') }}" />
                <x-jet-input id="tarif_pajak" type="text" class="mt-1 block w-full" value="{{ 'Rp ' . number_format($item->tarif_pajak, 0, ',', '.') }}" readonly />
            </div>

            <div class="col-span-6 sm:col-span-4 mt-4">
                <x-jet-label for="tanggal_berlaku" value="{{ __('Tanggal Berlaku') }}" />
                <x-jet-input id="tanggal_berlaku" type="text" class="mt-1 block w-full" value="{{ $item->tanggal_berlaku }}" readonly />
            </div>
            @endif
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('showingItem', false)" wire:loading.attr="disabled">{{ __('Close') }}</x-jet-secondary-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix === undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }

        document.querySelectorAll('.format-rupiah').forEach(function (element) {
            element.addEventListener('keyup', function (e) {
                element.value = formatRupiah(this.value, 'Rp ');
            });
        });
    });
</script>
