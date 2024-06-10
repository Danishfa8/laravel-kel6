<?php

namespace App\Http\Livewire;

<<<<<<< HEAD
use App\Models\Item;
use Livewire\Component;
use App\Models\JenisPajak;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
=======
use Livewire\Component;
use App\Models\Item;
use Livewire\WithPagination;
>>>>>>> 9939541fdbddec7dea304a149db9b835e026c4d9

class Items extends Component
{
    use WithPagination;

    public $active;
    public $q;
    public $sortBy = 'id';
    public $sortAsc = true;
    public $item = []; // Use an empty array for item creation

    public $confirmingItemDeletion = false;
<<<<<<< HEAD
    public $confirmingItemAddEdit = false;
    public $showingItem = false;
    public $jenisPajakOptions;
    public $selectedJenisPajak;
=======
    public $confirmingItemAdd = false;
>>>>>>> 9939541fdbddec7dea304a149db9b835e026c4d9

    protected $queryString = [
        'active' => ['except' => false],
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => true],
    ];

<<<<<<< HEAD
    public function mount()
    {
        $this->jenisPajakOptions = JenisPajak::all();
    }

    public function rules()
    {
        $rules = [
            'item.kode_pajak' => 'required|string',
            'item.nama_pajak' => 'required|string',
            'item.jenis_pajak' => 'string',
            'item.deskripsi' => 'nullable|string',
            'item.tarif_pajak' => 'required|numeric',
            'item.tanggal_berlaku' => 'required|date',
        ];

        if (!isset($this->item['id'])) {
            $rules['item.kode_pajak'] .= '|unique:items,kode_pajak';
        }

        return $rules;
    }
=======
    protected $rules = [
        'item.kode_pajak' => 'required|string|unique:items,kode_pajak', // Enforce unique kode_pajak
        'item.nama_pajak' => 'required|string',
        'item.deskripsi' => 'nullable|string',
        'item.tarif_pajak' => 'required|numeric|', // Adjust decimal range as needed
        'item.tanggal_berlaku' => 'required|date',
    ];
>>>>>>> 9939541fdbddec7dea304a149db9b835e026c4d9

    public function render()
    {
        $items = Item::where('user_id', auth()->user()->id)
            ->when($this->q, function ($query) {
                return $query->where(function ($query) {
                    $query->where('kode_pajak', 'like', '%' . $this->q . '%')
                        ->orWhere('nama_pajak', 'like', '%' . $this->q . '%')
<<<<<<< HEAD
                        ->orWhere('jenis_pajak', 'like', '%' . $this->q . '%')
                        ->orWhere('deskripsi', 'like', '%' . $this->q . '%');
                });
            })
            ->when($this->active, function ($query) {
                return $query->active();
=======
                        ->orWhere('deskripsi', 'like', '%' . $this->q . '%'); // Search all relevant fields
                });
            })
            ->when($this->active, function ($query) {
                return $query->active(); // Assuming an 'active' scope exists in Item model
>>>>>>> 9939541fdbddec7dea304a149db9b835e026c4d9
            })
            ->orderBy($this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');

        $items = $items->paginate(10);

        return view('livewire.items', [
<<<<<<< HEAD
            'items' => $items,            
=======
            'items' => $items,
>>>>>>> 9939541fdbddec7dea304a149db9b835e026c4d9
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

<<<<<<< HEAD
    public function confirmItemAction($itemId = null)
    {
        $this->reset(['item']);
        $this->resetErrorBag();
        
        if ($itemId) {
            $this->item = Item::find($itemId)->toArray();
        }

        $this->confirmingItemAddEdit = true;
    }

=======
>>>>>>> 9939541fdbddec7dea304a149db9b835e026c4d9
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

<<<<<<< HEAD
    public function saveItem()
    {
        $this->item['tarif_pajak'] = preg_replace('/[^0-9]/', '', $this->item['tarif_pajak']);
        
        $validatedData = $this->validate();

        if (!isset($validatedData['item']['jenis_pajak'])) {
            $validatedData['item']['jenis_pajak'] = '';
        }

        if (isset($this->item['id'])) {
            $item = Item::find($this->item['id']);
            $item->update($validatedData['item']);
            session()->flash('message', 'Item Updated Successfully');
        } else {
            auth()->user()->items()->create($validatedData['item']);
            session()->flash('message', 'Item Added Successfully');
        }

        $this->confirmingItemAddEdit = false;
    }

    public function show(Item $item)
    {
        $this->resetErrorBag();
        $this->item = $item->toArray();
        $this->showingItem = true;
    }

    public function jenis_pajak(string $jenis)
    {
        $jenisStrReplace = str_replace('_', ' ', $jenis);
    
        $jenis_pajak = DB::table('items AS a')
            ->join('jenis_pjk AS b', 'a.jenis_pajak', '=', 'b.jenis')
            ->select('a.kode_pajak', 'a.nama_pajak', 'a.jenis_pajak AS jenis')
            ->where('a.jenis_pajak', '=', $jenisStrReplace)
            ->get();
    
        return view('jenis_pajak', compact('jenis_pajak'));
    }

    public function pajak_pdf()
    {
        $userId = auth()->user()->id;
        $items = Item::where('user_id', $userId)->get();
    
        $pdf = PDF::loadview('pajak_pdf', ['items' => $items]);
        return $pdf->stream('laporan-pajak-pdf');
    }

    public function pajakOptions()
    {
        $pajak = JenisPajak::all();
    }
=======
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


>>>>>>> 9939541fdbddec7dea304a149db9b835e026c4d9
}
