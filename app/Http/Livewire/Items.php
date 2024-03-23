<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Item;
use Livewire\WithPagination;

class Items extends Component
{
    use WithPagination;

    public $active;
    public $q;
    public $sortBy = 'id';
    public $sortAsc = true;
    public $item = []; // Use an empty array for item creation

    public $confirmingItemDeletion = false;
    public $confirmingItemAdd = false;

    protected $queryString = [
        'active' => ['except' => false],
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => true],
    ];

    protected $rules = [
        'item.kode_pajak' => 'required|string|unique:items,kode_pajak', // Enforce unique kode_pajak
        'item.nama_pajak' => 'required|string',
        'item.deskripsi' => 'nullable|string',
        'item.tarif_pajak' => 'required|numeric|', // Adjust decimal range as needed
        'item.tanggal_berlaku' => 'required|date',
    ];

    public function render()
    {
        $items = Item::where('user_id', auth()->user()->id)
            ->when($this->q, function ($query) {
                return $query->where(function ($query) {
                    $query->where('kode_pajak', 'like', '%' . $this->q . '%')
                        ->orWhere('nama_pajak', 'like', '%' . $this->q . '%')
                        ->orWhere('deskripsi', 'like', '%' . $this->q . '%'); // Search all relevant fields
                });
            })
            ->when($this->active, function ($query) {
                return $query->active(); // Assuming an 'active' scope exists in Item model
            })
            ->orderBy($this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');

        $items = $items->paginate(10);

        return view('livewire.items', [
            'items' => $items,
        ]);
    }

    public function updatingActive()
    {
        $this->resetPage();
    }

    public function updatingQ()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($field === $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }

    public function confirmItemDeletion($id)
    {
        $this->confirmingItemDeletion = $id;
    }

    public function deleteItem(Item $item)
    {
        $item->delete();
        $this->confirmingItemDeletion = false;
        session()->flash('message', 'Item Deleted Successfully');
    }

    public function confirmItemAdd()
    {
        $this->reset(['item']); // Clear item data for new creation
        $this->confirmingItemAdd = true;
    }

    public function confirmItemEdit(Item $item)
    {
        $this->resetErrorBag();
        $this->item = $item->toArray(); // Convert model to array for form binding
        $this->confirmingItemAdd = true;    
    }

    public $showingItem = false;

    public function show(Item $item)
    {
        $this->resetErrorBag();
        $this->item = $item->toArray(); // Convert model to array for form binding
        $this->showingItem = true;    
    }

    public function saveItem()
{
    // Menyiapkan aturan validasi
    $rules = [
        'item.kode_pajak' => 'required|string', // Kode pajak harus diisi
        'item.nama_pajak' => 'required|string',
        'item.deskripsi' => 'nullable|string',
        'item.tarif_pajak' => 'required|numeric',
        'item.tanggal_berlaku' => 'required|date',
    ];

    // Jika sedang menambahkan item baru, tambahkan aturan unik untuk kode_pajak
    if (!isset($this->item['id'])) {
        $rules['item.kode_pajak'] .= '|unique:items,kode_pajak';
    }

    // Validasi data sesuai aturan yang telah disiapkan
    $this->validate($rules);

    // Melakukan pengecekan apakah sedang mengedit item atau menambahkan item baru
    if (isset($this->item['id'])) {
        $item = Item::find($this->item['id']);
        $item->update($this->item); // Update item yang ada
        session()->flash('message', 'Item Updated Successfully');
    } else {
        auth()->user()->items()->create($this->item); // Buat item baru untuk pengguna
        session()->flash('message', 'Item Added Successfully');
    }

    $this->confirmingItemAdd = false;
}


}
