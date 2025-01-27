<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;
use App\Models\Order_Item;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;



class Orderstable extends Component
{
    use WithPagination;
    public $loadAmount = 20;
    public $search = '';
    public $orderBy = 'created_at';
    public $orderAsc = false;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $tableName;
    public $columns;
    public $selectedColumns = [];
    public $idbeingremoved = null;
    public $single = false;
    public $multiple = false;
    public $row = null;
    public $status31Only = false;
    public $xmlinvoicesmodal = false;
    public $xmlstornomodal = false;
    public $filteractive = false;
    public $start_date_filter;
    public $end_date_filter;
    public $start_date;
    public $end_date;

    protected $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    protected $messages = [
        'start_date.required' => 'Start date is required.',
        'end_date.required' => 'The end date is required.',
        'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
    ];

    // Validate and generate XML for invoices
    public function generate_xml_invoice()
    {
        $this->validate();

        // Ensure $this->orders is a query, such as a model or builder instance
        $ordersQuery = \App\Models\Order::query();

        if ($this->start_date && $this->end_date) {
            $ordersQuery->whereBetween('invoice_date', [$this->start_date, $this->end_date]);
        }

        // Fetch the orders based on the filtered query
        $orders = $ordersQuery->get();
        $type = 'invoice_xml';
        $path = $this->generate_invoice_xml($orders, $type);
        // Debug or further process the orders

        // Handle logic to generate XML for invoices
        session()->flash('message', 'XML Invoice generated successfully.');
        $this->resetModal();
        return response()->download($path);
    }
    public function generate_invoice_xml($orders, $type)
    {
        // Validate that there are orders to process
        if ($orders->isEmpty()) {
            session()->flash('notification', [
                'message' => 'No orders found for the specified criteria!',
                'type' => 'warning',
                'title' => 'No Orders'
            ]);
            return;
        }

        // Initialize XML structure
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
<Facturi />');

        foreach ($orders as $order) {
            // Determine date and series based on type
            if ($type === 'invoice_xml') {
                $date = Carbon::createFromFormat('Y-m-d', $order->invoice_date)->format('d-m-Y');
                $serie = $order->external_invoice_number;
            } else {
                $date = Carbon::createFromFormat('Y-m-d', $order->storno_date)->format('d-m-Y');
                $serie = $order->external_storno_number;
            }
            $pu = 0;
            $va = 0;
            foreach ($order->orders as $index => $item) {
                $vatRate = (int) $item->vat;
                $priceWithoutVAT = $item->price / (1 + ($vatRate / 100));
                $pu += $priceWithoutVAT;
                $va += ($item->price - $priceWithoutVAT);
            }
            // Build invoice data for each order
            $invoiceData = [
                'FurnizorNume' => (app()->has('label_xml_FurnizorNume') ? app('label_xml_FurnizorNume') : 'MOLDASO LINE SRL'),
                'FurnizorCIF' => (app()->has('label_xml_FurnizorCIF') ? app('label_xml_FurnizorCIF') : 'RO41903669'),
                'FurnizorNrRegCom' => (app()->has('label_xml_FurnizorNrRegCom') ? app('label_xml_FurnizorNrRegCom') : 'J40/15607/2019'),
                'FurnizorCapital' => (app()->has('label_xml_FurnizorCapital') ? app('label_xml_FurnizorCapital') : '200.00'),
                'FurnizorAdresa' => (app()->has('label_xml_FurnizorAdresa') ? app('label_xml_FurnizorAdresa') : 'BUCURESTI sect. 1 str.
BLV.BUCURESTII NOI nr. 50A bl. TRS.A+C ap. 64'),
                'FurnizorBanca' => '',
                'FurnizorIBAN' => '',
                'FurnizorInformatiiSuplimentare' => (app()->has('label_xml_FurnizorInformatiiSuplimentare') ?
                    app('label_xml_FurnizorInformatiiSuplimentare') : 'Tel. 0757.527.656'),
                'ClientNume' => $order->account->name,
                'ClientInformatiiSuplimentare' => '',
                'ClientCIF' => '',
                'ClientNrRegCom' => '',
                'ClientJudet' => $order->account->addresses->where('type', 'billing')->first()->county_iso,
                'ClientLocalitate' => $order->account->addresses->where('type', 'billing')->first()->city,
                'ClientTara' => $order->account->addresses->where('type', 'billing')->first()->country_iso,
                'ClientAdresa' => $order->account->addresses->where('type', 'billing')->first()->address1,
                'ClientTelefon' => $order->account->phone,
                'ClientEmail' => $order->account->email,
                'FacturaNumar' => $order->invoice_series . ' - ' . $serie,
                'FacturaData' => $date,
                'FacturaScadenta' => $date,
                'FacturaMoneda' => $order->currency->name,
                'FacturaGreutate' => 0,
                'FacturaAccize' => 0,
                'FacturaIndexSPV' => '',
                'Detalii' => [],
                'Sumar' => [
                    'TotalValoare' => $pu, // Ensure $pu is a float
                    'TotalTVA' => $va, // Ensure $va is a float
                    'Total' => $order->final_amount,
                ],


            ];


            foreach ($order->orders as $index => $item) {
                $vatRate = (int) $item->vat;
                $priceWithoutVAT = $item->price / (1 + ($vatRate / 100));

                // Accumulate subtotals by VAT rate
                if (!isset($vatSubtotals[$vatRate])) {
                    $vatSubtotals[$vatRate] = 0;
                }
                $vatSubtotals[$vatRate] += $item->price * $item->quantity;
                if ($type === 'invoice_xml') {

                    $invoiceData['Detalii'][] = [
                        'LinieNrCrt' => $index + 1,
                        'Descriere' => $item->product->name,
                        'CodArticolFurnizor' => strtoupper($item->product->sku),
                        'CodArticolClient' => '',
                        'CodBare' => '',
                        'InformatiiSuplimentare' => '',
                        'UM' => 'BUC',
                        'Cantitate' => number_format($item->quantity, 4),
                        'Pret' => number_format($priceWithoutVAT, 4),
                        'Valoare' => number_format($priceWithoutVAT * $item->quantity, 4),
                        'ProcTVA' => number_format($vatRate, 4),
                        'TVA' => number_format(($item->price - $priceWithoutVAT) * $item->quantity, 4),
                    ];
                } else {
                    $invoiceData['Detalii'][] = [
                        'LinieNrCrt' => $index + 1,
                        'Descriere' => $item->product->name,
                        'CodArticolFurnizor' => strtoupper($item->product->sku),
                        'CodArticolClient' => '',
                        'CodBare' => '',
                        'InformatiiSuplimentare' => '',
                        'UM' => 'BUC',
                        'Cantitate' => '-' . number_format($item->quantity, 4),
                        'Pret' => number_format($priceWithoutVAT, 4),
                        'Valoare' => '-' . number_format($priceWithoutVAT * $item->quantity, 4),
                        'ProcTVA' => number_format($vatRate, 4),
                        'TVA' => '-' . number_format(($item->price - $priceWithoutVAT) * $item->quantity, 4),
                    ];
                }
            }
            // Add delivery line
            $deliveryPrice = $order->delivery_price;
            $deliveryPriceWithoutVAT = $deliveryPrice / (1 + (19 / 100));

            if ($deliveryPrice > 0) {
                if ($type === 'invoice_xml') {

                    $invoiceData['Detalii'][] = [
                        'LinieNrCrt' => count($invoiceData['Detalii']) + 1,
                        'Descriere' => 'TRANSPORT',
                        'CodArticolFurnizor' => '000001',
                        'CodArticolClient' => '',
                        'CodBare' => '',
                        'InformatiiSuplimentare' => '',
                        'UM' => 'BUC',
                        'Cantitate' => '1.0000',
                        'Pret' => number_format(
                            $deliveryPriceWithoutVAT,
                            4
                        ),
                        'Valoare' => number_format($deliveryPriceWithoutVAT, 4),
                        'ProcTVA' => number_format(19, 2),
                        'TVA' => number_format($order->delivery_price - $deliveryPriceWithoutVAT, 4),
                    ];
                } else {
                    $invoiceData['Detalii'][] = [
                        'LinieNrCrt' => count($invoiceData['Detalii']) + 1,
                        'Descriere' => 'TRANSPORT',
                        'CodArticolFurnizor' => '000001',
                        'CodArticolClient' => '',
                        'CodBare' => '',
                        'InformatiiSuplimentare' => '',
                        'UM' => 'BUC',
                        'Cantitate' => '-' . '1.0000',
                        'Pret' => number_format(
                            $deliveryPriceWithoutVAT,
                            4
                        ),
                        'Valoare' => '-' . number_format($deliveryPriceWithoutVAT, 4),
                        'ProcTVA' => number_format(19, 2),
                        'TVA' => '-' . number_format($order->delivery_price - $deliveryPriceWithoutVAT, 4),
                    ];
                }
            }
            $voucherValue = $order->voucher_value + $order->promotion_value;
            if ($voucherValue > 0) {
                $amountNoVoucher = array_sum($vatSubtotals); // Total amount without voucher
                $vatGroups = [];

                foreach ($order->orders as $item) {
                    $vatRate = (int) $item->vat;
                    $priceWithoutVAT = $item->price / (1 + ($vatRate / 100));

                    if (!isset($vatGroups[$vatRate])) {
                        $vatGroups[$vatRate] = [
                            'totalNet' => 0,
                            'totalVoucherNet' => 0,
                            'totalVoucher' => 0,
                        ];
                    }

                    // Calculate the proportional voucher value for the VAT rate
                    $voucherImpactNet = (($item->price / $amountNoVoucher) * $item->quantity * $voucherValue) / (1 + ($vatRate / 100));
                    $voucherImpactTotal = ($item->price / $amountNoVoucher) * $item->quantity * $voucherValue;

                    $vatGroups[$vatRate]['totalNet'] += $priceWithoutVAT * $item->quantity;
                    $vatGroups[$vatRate]['totalVoucherNet'] += $voucherImpactNet;
                    $vatGroups[$vatRate]['totalVoucher'] += $voucherImpactTotal;
                }

                // Add voucher lines to the XML
                foreach ($vatGroups as $vatRate => $group) {
                    $invoiceData['Detalii'][] = [
                        'LinieNrCrt' => count($invoiceData['Detalii']) + 1,
                        'Descriere' => 'DISCOUNT ACORDAT',
                        'CodArticolFurnizor' => '000002',
                        'CodArticolClient' => '',
                        'CodBare' => '',
                        'InformatiiSuplimentare' => '',
                        'UM' => 'BUC',
                        'Cantitate' => '1.0000',
                        'Pret' => '-' . number_format($group['totalVoucherNet'], 4),
                        'Valoare' => '-' . number_format($group['totalVoucherNet'], 4),
                        'ProcTVA' => number_format($vatRate, 4),
                        'TVA' => '-' . number_format($group['totalVoucher'] - $group['totalVoucherNet'], 4),
                    ];
                }
            }


            // Add each order to the XML as a `Factura` node
            $factura = $xml->addChild('Factura');
            $antet = $factura->addChild('Antet');
            foreach ($invoiceData as $key => $value) {
                if (is_array($value)) continue; // Skip arrays for now
                $antet->addChild($key, htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8'));
            }

            $detalii = $factura->addChild('Detalii')->addChild('Continut');
            foreach ($invoiceData['Detalii'] as $detail) {
                $linie = $detalii->addChild('Linie');
                foreach ($detail as $key => $value) {
                    $linie->addChild($key, htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8'));
                }
            }

            $sumar = $factura->addChild('Sumar');
            foreach ($invoiceData['Sumar'] as $key => $value) {
                $sumar->addChild($key, htmlspecialchars(number_format($value, 2), ENT_XML1 | ENT_COMPAT, 'UTF-8'));
            }
        }

        // Save XML file
        $invoicePath = 'invoices/';
        if ($type === 'invoice_xml') {
            $pref = 'invoice';
        } else {
            $pref = 'storno';
        }
        $fileName = (app()->has('label_xml_filename') ? app('label_xml_filename') : 'F_41903669') . '_' . $pref .
            "_{$this->start_date}_to_{$this->end_date}.xml";

        if (!File::exists($invoicePath)) {
            File::makeDirectory($invoicePath, 0755, true);
        }

        Storage::disk('public_upload')->put($invoicePath . $fileName, $xml->asXML());

        session()->flash('notification', [
            'message' => "Invoices XML successfully generated at: $fileName",
            'type' => 'success',
            'title' => 'XML Generated'
        ]);
        $path = public_path($invoicePath . $fileName);
        return $path;
    }



    // Validate and generate XML for storno
    public function generate_xml_storno()
    {
        $this->validate();

        // Ensure $this->orders is a query, such as a model or builder instance
        $ordersQuery = \App\Models\Order::query();

        if ($this->start_date && $this->end_date) {
            $ordersQuery->whereBetween('storno_date', [$this->start_date, $this->end_date]);
        }

        // Fetch the orders based on the filtered query
        $orders = $ordersQuery->get();
        if ($orders) {


            $type = 'storno_xml';
            $path = $this->generate_invoice_xml($orders, $type);
            // Debug or further process the orders

            // Handle logic to generate XML for invoices
            session()->flash('message', 'XML Invoice generated successfully.');
            $this->resetModal();
            return response()->download($path);
        } else {
            $this->resetModal();

            session()->flash('message', 'No order founds beteen those dates.');
        }
    }

    // Cancel and reset modal
    public function cancel_xml()
    {
        $this->resetModal();
    }

    private function resetModal()
    {
        $this->xmlinvoicesmodal = false;
        $this->xmlstornomodal = false;
        $this->reset(['start_date', 'end_date']);
    }

    public function xmlinvoices()
    {
        $this->xmlinvoicesmodal = true;
    }

    public function filter()
    {
        $this->filteractive = true;
    }

    public function xmlstorno()
    {
        $this->xmlstornomodal = true;
    }



    public function expandRow($index)
    {
        if ($this->row === null) {
            $this->row = $index;
        } elseif ($this->row != $index) {
            $this->row = $index;
        } else {
            $this->row = null;
        }
    }

    public function cancel_filter()
    {
        $this->filteractive = false;
    }

    public function render()
    {
        return view('livewire.orderstable', ['orders' => $this->orders]);
    }
    public function mount($tableName)
    {
        $this->tableName = $tableName;
        $this->columns = Schema::getColumnListing($this->tableName);
        $this->selectedColumns = $this->columns;
    }
    public function filter_order()
    {
        $this->validate([
            'start_date_filter' => 'nullable|date',
            'end_date_filter' => 'nullable|date|after_or_equal:start_date_filter',
        ]);

        // Set the query to filter by date range
        $this->orders->when($this->start_date_filter && $this->end_date_filter, function ($query) {
            $query->whereBetween('invoice_date', [$this->start_date_filter, $this->end_date_filter]);
        });
        $this->filteractive = false;
    }
    public function getOrdersProperty()
    {
        return $this->ordersQuery->paginate($this->loadAmount);
    }
    public function getOrdersQueryProperty()
    {
        $query = Order::search($this->search)
            ->with([
                'orders.product' => function ($query) {
                    $query->withCount(['orders_item as interim_quantity' => function ($query) {
                        $query->whereHas('order', function ($q) {
                            $q->where('status_id', 31);
                        })->select(DB::raw('sum(quantity)'));
                    }]);
                },
                'status',
                'account',
                'cart',
                'currency',
                'voucher',
                'payment'
            ])
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');

        if ($this->status31Only) {
            $query = $query->where('status_id', 31);
        }

        if ($this->start_date_filter && $this->end_date_filter) {
            $query = $query->whereBetween('invoice_date', [$this->start_date_filter, $this->end_date_filter]);
        }

        return $query;
    }

    public function showColumn($column)
    {
        if ($column === 'id') {
            return true;
        }
        return in_array($column, $this->selectedColumns);
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->orders->pluck('id')->map(fn($item) => (string) $item)->toArray();
        } else {
            $this->checked = [];
        }
    }
    public function updatedChecked()
    {
        $this->selectPage = false;
    }
    public function sortBy($columnName)
    {

        if ($this->orderBy === $columnName) {
            $this->orderAsc = $this->swapSortDirection();
        } else {
            $this->orderAsc = '1';
        }

        $this->orderBy = $columnName;
    }
    public function swapSortDirection()
    {
        return $this->orderAsc === '1' ? '0' : '1';
    }
    public function isChecked($id)
    {
        return in_array($id, $this->checked);
    }
    public function selectAll()
    {
        $this->selectAll = true;
        $this->checked = $this->ordersQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
    }
    public function deleteSingleRecord()
    {
        $id = $this->idbeingremoved;
        $order = Order::findOrFail($id);
        foreach ($order->orders as $orderitem) {
            $orderitem->product->quantity += $orderitem->quantity;
            $orderitem->product->save();
            $orderitem->delete();
        }

        $order->delete();
        $this->checked = array_diff($this->checked, [$id]);
        $this->single = false;
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function confirmItemRemoval($id)
    {
        $this->idbeingremoved = $id;
        $this->single = true;
    }
    public function confirmItemsRemoval()
    {
        $this->multiple = true;
    }
    public function cancel_delete()
    {
        $this->multiple = false;
        $this->single = false;
    }
    public function deleteRecords()
    {
        $orders = Order::whereKey($this->checked)->get();
        foreach ($orders as $order) {
            foreach ($order->orders as $orderitem) {
                $orderitem->product->quantity += $orderitem->quantity;
                $orderitem->product->save();
                $orderitem->delete();
            }

            $order->delete();
        }
        $this->checked = [];
        $this->selectPage = false;
        $this->multiple = false;
        session()->flash('notification', [
            'message' => 'Records deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}
