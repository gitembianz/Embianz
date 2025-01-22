<?php

namespace App\Http\Livewire;

use App\Models\Invoice;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Status;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class ShowOrder extends Component
{
    public $orderId;
    public $record = [];
    public $edititem = null;
    public $delete = false;
    public $statuses;
    public $invoice_sdatabase;
    public $storno_sdatabase;
    public $circle;
    protected $listeners = [
        'refreshComponent' => '$refresh'
    ];


    public function generate_invoice_number()
    {
        $this->invoice_sdatabase = DB::connection('mysql_invoice')
            ->table('invoices')
            ->where('series', app('global_invoice_series'))
            ->get();

        $existingInvoice = $this->invoice_sdatabase
            ->where('order_number', $this->order->order_number)
            ->where('type', 'invoice')
            ->first();

        if ($existingInvoice) {
            $this->order->external_invoice_number = str_pad($existingInvoice->number, 4, '0', STR_PAD_LEFT);
            $this->order->invoice_series = app('global_invoice_series');
            $this->order->save();

            session()->flash('notification', [
                'message' => 'Invoice number generated successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        } else {
            $latestInvoice = DB::connection('mysql_invoice')
                ->table('invoices')
                ->where('series', app('global_invoice_series'))
                ->orderByDesc('number')
                ->first();

            if ($latestInvoice) {
                $newNumber = $latestInvoice->number + 1;
            } else {
                $newNumber = 1;
            }

            DB::connection('mysql_invoice')->table('invoices')->insert([
                'number' => $newNumber,
                'order_number' => $this->order->order_number,
                'series' => app('global_invoice_series'),
                'type' => 'invoice'
            ]);

            $this->order->invoice_series = app('global_invoice_series');
            $this->order->external_invoice_number = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            $this->order->save();

            session()->flash('notification', [
                'message' => 'Invoice number generated successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        }
    }
    public function generate_storno_number()
    {
        $this->storno_sdatabase = DB::connection('mysql_invoice')
            ->table('invoices')
            ->where('series', app('global_invoice_series'))
            ->get();

        $existingstorno = $this->storno_sdatabase
            ->where('order_number', $this->order->order_number)
            ->where('type', 'storno')
            ->first();

        if ($existingstorno) {
            $this->order->external_storno_number = str_pad($existingstorno->number, 4, '0', STR_PAD_LEFT);
            $this->order->invoice_series = app('global_invoice_series');
            $this->order->save();

            session()->flash('notification', [
                'message' => 'Invoice number generated successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        } else {
            $latestInvoice = DB::connection('mysql_invoice')
                ->table('invoices')
                ->where('series', app('global_invoice_series'))
                ->orderByDesc('number')
                ->first();

            if ($latestInvoice) {
                $newNumber = $latestInvoice->number + 1;
            } else {
                $newNumber = 1;
            }

            DB::connection('mysql_invoice')->table('invoices')->insert([
                'number' => $newNumber,
                'order_number' => $this->order->order_number,
                'series' => app('global_invoice_series'),
                'type' => 'storno'
            ]);

            $this->order->invoice_series = app('global_invoice_series');
            $this->order->external_storno_number = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            $this->order->save();

            session()->flash('notification', [
                'message' => 'Storno number generated successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        }
    }


    public function generate_invoice()
    {
        if (!$this->order->external_invoice_number) {
            session()->flash('notification', [
                'message' => 'Please generate invoice number first!',
                'type' => 'warning',
                'title' => 'Information missing'
            ]);
            return;
        }

        if (!$this->order->invoice_date) {
            session()->flash('notification', [
                'message' => 'Please select invoice date first!',
                'type' => 'warning',
                'title' => 'Information missing'
            ]);
            return;
        }

        // Folder system
        $invoicePath = 'invoices/';
        $yearMonthPath = $invoicePath . Carbon::now()->year . '/' . Carbon::now()->format('F');

        if (!File::exists($yearMonthPath)) {
            File::makeDirectory($yearMonthPath, 0755, true);
        }

        $filePath = $yearMonthPath . "/" . $this->order->invoice_series . $this->order->external_invoice_number . "-" . $this->order->order_number . ".pdf";
        if (file_exists($filePath)) {
            $i = 1;
            $newpath = $yearMonthPath . "/" . $this->order->invoice_series . $this->order->external_invoice_number . "-" . $this->order->order_number . "(" . $i . ")" . ".pdf";
            while (file_exists($newpath)) {
                $i++;
                $newpath = $yearMonthPath . "/" . $this->order->invoice_series . $this->order->external_invoice_number . "-" . $this->order->order_number . "(" . $i . ")" . ".pdf";
            }
            $filePath = $newpath;
        }
        // generate PDF
        $htmlContent = "
         <html>
        <head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>

        <style>
        *{ font-family: DejaVu Sans !important;
        font-size:12px;
            }
        table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                padding: 8px;
                word-wrap: break-word; 
            }
          
        </style>
         </head>
        <body>
        <table class='info'>
            <tr>

                <td class='ff'>" . (app()->has('label_invoice_title') ? app('label_invoice_title') : 'Invoice') . "</td>
            </tr>
            <tr>
                <td class='ff'>" . (app()->has('label_invoice_series') ? app('label_invoice_series') : 'Series: ') .
            (app()->has('global_invoice_series') ? app('global_invoice_series') : 'Number:') . " - " .
            (app()->has('label_invoice_number') ? app('label_invoice_number') : 'Number:') .
            $this->order->external_invoice_number . "</td>
            </tr>
            <tr>
                <td class='ff'>" . (app()->has('label_invoice_date') ? app('label_invoice_date') : 'Date: ') . $this->order->invoice_date . "</td>
            </tr>
            <tr>
                <td class='ff'></td>
                <td class='ff'></td>
            </tr>
            <tr>
                <td class='ff'></td>
                <td class='ff'></td>
            </tr>
            <tr>
                <td class='ff'></td>
                <td class='ff'></td>
            </tr>
            <tr>
                <td class='infotd'>" . (app()->has('label_invoice_furnizor') ? app('label_invoice_furnizor') : 'Furnizor: ') . "</td>
                <td class='infotd'>" . (app()->has('label_invoice_client') ? app('label_invoice_client') : 'Client: ') . "</td>
            </tr>
            <tr>
                <td class='infotd'>" . (app()->has('global_invoice_furnizor') ? app('global_invoice_furnizor') : 'Ceva nu a mers bine, verifica setarile') . "</td>
                <td class='infotd'>" . $this->order->account->name . "<br> " .
            $this->order->account->addresses->where('type', 'billing')->first()->address1 . ",<br> " .
            $this->order->account->addresses->where('type', 'billing')->first()->city . ", " .
            $this->order->account->addresses->where('type', 'billing')->first()->county . "<br>" .
            $this->order->account->addresses->where('type', 'billing')->first()->country . ", " .
            $this->order->account->addresses->where('type', 'billing')->first()->zipcode . "</td>
            </tr>
         </table>
            <br></br><br></br>

            <table border='1' cellpadding='5' cellspacing='0' width='100%' style='margin-top: 20px;'>
                <thead>
                    <tr>
                        <th>" . (app()->has('label_invoice_th_nr') ? app('label_invoice_th_nr') : 'Nr. Crt.') . "</th>
                        <th>" . (app()->has('label_invoice_th_name') ? app('label_invoice_th_name') : 'Denumire Articol/Serviciu') . "</th>
                        <th>" . (app()->has('label_invoice_th_um') ? app('label_invoice_th_um') : 'U.M') . "</th>
                        <th>" . (app()->has('label_invoice_th_vat') ? app('label_invoice_th_vat') : 'TVA') . "</th>
                        <th>" . (app()->has('label_invoice_th_quantity') ? app('label_invoice_th_quantity') : 'Cantitate') . "</th>
                        <th>" . (app()->has('label_invoice_th_pu') ? app('label_invoice_th_pu') : 'Pret Unitar - RON') . "</th>
                        <th>" . (app()->has('label_invoice_th_val') ? app('label_invoice_th_val') : 'Valoare - RON') . "</th>
                        <th>" . (app()->has('label_invoice_th_valvat') ? app('label_invoice_th_valvat') : 'Valoare TVA - RON') . "</th>
                        <th>" . (app()->has('label_invoice_th_total') ? app('label_invoice_th_total') : 'Total') . "</th>
                    </tr>
                </thead>
            <tbody>";

        $voucherValue = $this->order->voucher_value + $this->order->promotion_value;
        $totalval = 0;
        $i = 0;
        if ($voucherValue &&  $voucherValue != 0) {
            $vatGroups = [];
            $amountnovoucher = $this->order->final_amount + $voucherValue - $this->order->delivery_price;
        }
        foreach ($this->order->orders as $item) {
            $vatRate = (int) $item->vat;
            $pu = $item->price / (1 + ($vatRate / 100));
            $totalval += $pu * $item->quantity;

            if ($voucherValue &&  $voucherValue != 0) {
                if (!isset($vatGroups[$vatRate])) {
                    $vatGroups[$vatRate] = [
                        'totalpu' => 0,
                        'total' => 0,
                    ];
                }
                $vatGroups[$vatRate]['totalpu'] += (($item->price / $amountnovoucher) * $item->quantity * $voucherValue) / (1 + ($vatRate / 100));
                $vatGroups[$vatRate]['total'] += ($item->price / $amountnovoucher) * $item->quantity * $voucherValue;
            }



            $htmlContent .= "
                        <tr>
                            <td>" . ($i + 1) . "</td>
                            <td>" . $item->product->name . "<br> (" . $item->product->ean . ")</td>
                            <td>" . (app()->has('label_invoice_um_text') ? app('label_invoice_um_text') : 'buc.') . "</td>
                            <td>" . $vatRate . "</td>
                            <td>" . $item->quantity . "</td>
                            <td>" . number_format($pu, 2) . "</td>
                            <td>" . number_format($pu * $item->quantity, 2) . "</td>
                            <td>" . number_format(($item->price - $pu) * $item->quantity, 2) . "</td>
                            <td>" . number_format($item->price * $item->quantity, 2) . "</td>
                        </tr>";
            $i++;
        }
        if ($voucherValue &&  $voucherValue != 0) {
            foreach ($vatGroups as $vatRate => $group) {
                $totalval -= $group['totalpu'];
                $htmlContent .= "
                            <tr>
                                <td>" . ($i + 1) . "</td>
                                <td>" . (app()->has('label_invoice_th_voucher') ? app('label_invoice_th_voucher') : 'Reducere') . "</td>
                                <td>" . (app()->has('label_invoice_um_text') ? app('label_invoice_um_text') : 'buc.') . "</td>
                                <td>" . $vatRate . "</td>
                                <td>1</td>
                                <td>" . number_format(-$group['totalpu'], 2) . "</td>
                                <td>" . number_format(-$group['totalpu'], 2) . "</td>
                                <td>" . number_format(- ($group['total'] - $group['totalpu']), 2) . "</td>
                                <td>" . number_format(-$group['total'], 2) . "</td>
                            </tr>";
                $i++;
            }
        }
        // delivery sistem
        $htmlContent .= "
                <tr>
                    <td>" . ($i + 1) . "</td>
                    <td>" . (app()->has('label_invoice_th_delivery') ? app('label_invoice_th_delivery') : 'Transport') . "</td>
                    <td>" . (app()->has('label_invoice_um_text') ? app('label_invoice_um_text') : 'buc.') . "</td>
                    <td>19</td>
                    <td>1</td>
                    <td>" . number_format(($this->order->delivery_price / (1 + (19 / 100))), 2) . "</td>
                    <td>" . number_format(($this->order->delivery_price / (1 + (19 / 100))), 2) . "</td>
                    <td>" . number_format(($this->order->delivery_price - ($this->order->delivery_price / (1 + (19 / 100)))), 2) . "</td>
                    <td>" . number_format($this->order->delivery_price, 2) . "</td>
                </tr>";
        $totalval += $this->order->delivery_price / (1 + (19 / 100));
        // total row
        $htmlContent .= "
                <tr>
                    <td colspan='6' style='font-weight: 700;text-align:right'><span>" . (app()->has('label_invoice_total_prev') ? app('label_invoice_total_prev') : 'Total') . "</span></td>
                    <td style='font-weight: 700;'><span>" . number_format($totalval, 2) . "</span></td>
                    <td style='font-weight: 700;'><span>" . number_format($this->order->final_amount - $totalval, 2) . "</span></td>
                    <td style='font-weight: 700;'><span>" . number_format($this->order->final_amount, 2) . "</span></td>
                </tr>";

        $htmlContent .= "
            </tbody>
        </table>
        <p style='text-align:right'><strong>" . (app()->has('label_invoice_th_totalfinal') ? app('label_invoice_th_totalfinal') : 'Total Plata ') . " " . number_format($this->order->final_amount, 2) . " " . (app()->has('global_currency_primary_symbol') ? app('global_currency_primary_symbol') : 'lei') . "</strong></p><br>
        <p>" . (app()->has('label_invoice_cf') ? app('label_invoice_cf') : 'Cf. Comanda') . $this->order->order_number . "<br>" . (app()->has('label_invoice_footer') ? app('label_invoice_footer') : 'Please check invoice footer label') . "</p></body></html>";

        $pdf = PDF::loadHTML($htmlContent);
        $pdf->save($filePath);

        Invoice::create([
            'account_id' => $this->order->account_id,
            'order_id' => $this->order->id,
            'date' => $this->order->invoice_date,
            'type' => 'invoice',
            'path' => $filePath
        ]);
        $vat = $this->order->final_amount - $totalval;
        $type = 'invoice_xml';
        $this->generate_invoice_xml($totalval, $vat, $type);

        session()->flash('notification', [
            'message' => 'Invoice generate successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }

    public function generate_invoice_xml($valoare, $vat, $type)
    {
        $invoiceData = [
            'FurnizorNume' => (app()->has('label_xml_FurnizorNume') ? app('label_xml_FurnizorNume') : 'MOLDASO LINE SRL'),
            'FurnizorCIF' => (app()->has('label_xml_FurnizorCIF') ? app('label_xml_FurnizorCIF') : 'RO41903669'),
            'FurnizorNrRegCom' => (app()->has('label_xml_FurnizorNrRegCom') ? app('label_xml_FurnizorNrRegCom') : 'J40/15607/2019'),
            'FurnizorCapital' => (app()->has('label_xml_FurnizorCapital') ? app('label_xml_FurnizorCapital') : '200.00'),
            'FurnizorAdresa' => (app()->has('label_xml_FurnizorAdresa') ? app('label_xml_FurnizorAdresa') : 'BUCURESTI sect. 1 str. BLV.BUCURESTII NOI nr. 50A bl. TRS.A+C ap. 64'),
            'FurnizorBanca' => '',
            'FurnizorIBAN' => '',
            'FurnizorInformatiiSuplimentare' => (app()->has('label_xml_FurnizorInformatiiSuplimentare') ? app('label_xml_FurnizorInformatiiSuplimentare') : 'Tel. 0757.527.656'),
            'ClientNume' => strtoupper($this->order->account->name),
            'ClientInformatiiSuplimentare' => '',
            'ClientCIF' => '',
            'ClientNrRegCom' => '',
            'ClientJudet' => $this->order->account->addresses->where('type', 'billing')->first()->county_iso,
            'ClientLocalitate' => strtoupper($this->order->account->addresses->where('type', 'billing')->first()->city),
            'ClientTara' => $this->order->account->addresses->where('type', 'billing')->first()->country_iso,
            'ClientAdresa' => strtoupper($this->order->account->addresses->where('type', 'billing')->first()->address1),
            'ClientTelefon' => $this->order->account->phone,
            'ClientEmail' => $this->order->account->email,
            'FacturaNumar' => $this->order->invoice_series . ' - ' . $this->order->external_invoice_number,
            'FacturaData' => $this->order->invoice_date,
            'FacturaScadenta' =>  $this->order->invoice_date,
            'FacturaMoneda' => $this->order->currency->name,
            'FacturaGreutate' => 0,
            'FacturaAccize' => 0,
            'FacturaIndexSPV' => '',
            'Detalii' => [],
            'Sumar' => [
                'TotalValoare' => $valoare,
                'TotalTVA' => $vat,
                'Total' => $this->order->final_amount,
            ],
        ];

        foreach ($this->order->orders as $index => $item) {
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
                    'Descriere' => strtoupper($item->product->name),
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
                    'Descriere' => strtoupper($item->product->name),
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
        $deliveryPrice = $this->order->delivery_price;
        $deliveryPriceWithoutVAT = $deliveryPrice / (1 + (19 / 100));

        if ($deliveryPrice > 0) {
            if ($type === 'invoice_xml') {

                $invoiceData['Detalii'][] = [
                    'LinieNrCrt' => count($invoiceData['Detalii']) + 1,
                    'Descriere' => 'TRANSPORT',
                    'CodArticolFurnizor' => '',
                    'CodArticolClient' => '',
                    'CodBare' => '',
                    'InformatiiSuplimentare' => '',
                    'UM' => 'BUC',
                    'Cantitate' => '1.0000',
                    'Pret' => number_format($deliveryPriceWithoutVAT, 4),
                    'Valoare' => number_format($deliveryPriceWithoutVAT, 4),
                    'ProcTVA' => number_format(19, 2),
                    'TVA' => number_format($this->order->delivery_price - $deliveryPriceWithoutVAT, 4),
                ];
            } else {
                $invoiceData['Detalii'][] = [
                    'LinieNrCrt' => count($invoiceData['Detalii']) + 1,
                    'Descriere' => 'TRANSPORT',
                    'CodArticolFurnizor' => '',
                    'CodArticolClient' => '',
                    'CodBare' => '',
                    'InformatiiSuplimentare' => '',
                    'UM' => 'BUC',
                    'Cantitate' => '-' . '1.0000',
                    'Pret' => number_format($deliveryPriceWithoutVAT, 4),
                    'Valoare' => '-' . number_format($deliveryPriceWithoutVAT, 4),
                    'ProcTVA' => number_format(19, 2),
                    'TVA' => '-' . number_format($this->order->delivery_price - $deliveryPriceWithoutVAT, 4),
                ];
            }
        }
        // Add voucher lines proportionally by VAT rate
        $voucherValue = $this->order->voucher_value + $this->order->promotion_value;
        if ($voucherValue > 0) {
            $amountNoVoucher = array_sum($vatSubtotals); // Total amount without voucher
            $vatGroups = [];

            foreach ($this->order->orders as $item) {
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
                    'CodArticolFurnizor' => '',
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



        // Generate XML structure
        $xml = new \SimpleXMLElement('<Facturi/>');
        $factura = $xml->addChild('Factura');
        $antet = $factura->addChild('Antet');
        foreach ($invoiceData as $key => $value) {
            if (is_array($value)) continue; // Skip arrays for now
            $antet->addChild($key, htmlspecialchars($value));
        }

        $detalii = $factura->addChild('Detalii')->addChild('Continut');
        foreach ($invoiceData['Detalii'] as $detail) {
            $linie = $detalii->addChild('Linie');
            foreach ($detail as $key => $value) {
                $linie->addChild($key, htmlspecialchars($value));
            }
        }

        $sumar = $factura->addChild('Sumar');
        foreach ($invoiceData['Sumar'] as $key => $value) {
            $sumar->addChild($key, htmlspecialchars(number_format($value, 2)));
        }

        $invoicePath = 'invoices/';
        $yearMonthPath = $invoicePath . Carbon::now()->year . '/' . Carbon::now()->format('F');

        if (!File::exists($yearMonthPath)) {
            File::makeDirectory($yearMonthPath, 0755, true);
        }

        $xmlPath = $yearMonthPath . "/" . $this->order->invoice_series . $this->order->external_invoice_number . "-" . $this->order->order_number . ".xml";
        if (file_exists($xmlPath)) {
            $i = 1;
            $newpath = $yearMonthPath . "/" . $this->order->invoice_series . $this->order->external_invoice_number . "-" . $this->order->order_number . "(" . $i . ")" . ".xml";
            while (file_exists($newpath)) {
                $i++;
                $newpath = $yearMonthPath . "/" . $this->order->invoice_series . $this->order->external_invoice_number . "-" . $this->order->order_number . "(" . $i . ")" . ".xml";
            }
            $xmlPath = $newpath;
        }
        Storage::disk('public_upload')->put($xmlPath, $xml->asXML());
        Invoice::create([
            'account_id' => $this->order->account_id,
            'order_id' => $this->order->id,
            'date' => $this->order->invoice_date,
            'type' => $type,
            'path' => $xmlPath
        ]);
    }

    public function generate_storno()
    {
        if (!$this->order->external_storno_number) {
            session()->flash('notification', [
                'message' => 'Please generate storno number first!',
                'type' => 'warning',
                'title' => 'Information missing'
            ]);
            return;
        }

        if (!$this->order->storno_date) {
            session()->flash('notification', [
                'message' => 'Please select storno date first!',
                'type' => 'warning',
                'title' => 'Information missing'
            ]);
            return;
        }

        // Folder system
        $StornoPath = 'invoices/';
        $yearMonthPath = $StornoPath . Carbon::now()->year . '/' . Carbon::now()->format('F');

        if (!File::exists($yearMonthPath)) {
            File::makeDirectory($yearMonthPath, 0755, true);
        }

        $filePath = $yearMonthPath . "/" . $this->order->invoice_series . $this->order->external_storno_number . "-" . $this->order->order_number . ".pdf";
        if (file_exists($filePath)) {
            $i = 1;
            $newpath = $yearMonthPath . "/" . $this->order->invoice_series . $this->order->external_storno_number . "-" . $this->order->order_number . "(" . $i . ")" . ".pdf";
            while (file_exists($newpath)) {
                $i++;
                $newpath = $yearMonthPath . "/" . $this->order->invoice_series . $this->order->external_storno_number . "-" . $this->order->order_number . "(" . $i . ")" . ".pdf";
            }
            $filePath = $newpath;
        }
        // generate PDF
        $htmlContent = "
       <html>
<head>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>

     <style>
    *{ font-family: DejaVu Sans !important;}
  </style>
      </head>
      <body>
        <table class='info'>
            <tr>
                <td class='ff'></td>
                <td class='ff'>" . (app()->has('label_invoice_title') ? app('label_invoice_title') : 'Invoice') . "</td>
            </tr>
            <tr>
                <td class='ff'></td>
                <td class='ff'>" . (app()->has('label_invoice_series') ? app('label_invoice_series') : 'Series: ') .
            (app()->has('global_invoice_series') ? app('global_invoice_series') : 'Number:') . " - " .
            (app()->has('label_invoice_number') ? app('label_invoice_number') : 'Number:') .
            $this->order->external_storno_number . "</td>
            </tr>
            <tr>
                <td class='ff'></td>
                <td class='ff'>" . (app()->has('label_invoice_date') ? app('label_invoice_date') : 'Date: ') . $this->order->storno_date . "</td>
            </tr>
            <tr>
                <td class='ff'></td>
                <td class='ff'></td>
            </tr>
            <tr>
                <td class='ff'></td>
                <td class='ff'></td>
            </tr>
            <tr>
                <td class='ff'></td>
                <td class='ff'></td>
            </tr>
            <tr>
                <td class='infotd'>" . (app()->has('label_invoice_furnizor') ? app('label_invoice_furnizor') : 'Furnizor: ') . "</td>
                <td class='infotd'>" . (app()->has('label_invoice_client') ? app('label_invoice_client') : 'Client: ') . "</td>
            </tr>
            <tr>
                <td class='infotd'>" . (app()->has('global_invoice_furnizor') ? app('global_invoice_furnizor') : 'Ceva nu a mers bine, verifica setarile') . "</td>
                <td class='infotd'>" . $this->order->account->name . "<br> " .
            $this->order->account->addresses->where('type', 'billing')->first()->address1 . ",<br> " .
            $this->order->account->addresses->where('type', 'billing')->first()->city . ", " .
            $this->order->account->addresses->where('type', 'billing')->first()->county . "<br>" .
            $this->order->account->addresses->where('type', 'billing')->first()->country . ", " .
            $this->order->account->addresses->where('type', 'billing')->first()->zipcode . "</td>
            </tr>
         </table>
        <br></br><br></br>

    <table border='1' cellpadding='5' cellspacing='0' width='100%' style='margin-top: 20px;'>
        <thead>
            <tr>
                <th>" . (app()->has('label_invoice_th_nr') ? app('label_invoice_th_nr') : 'Nr. Crt.') . "</th>
                <th>" . (app()->has('label_invoice_th_name') ? app('label_invoice_th_name') : 'Denumire Articol/Serviciu') . "</th>
                <th>" . (app()->has('label_invoice_th_um') ? app('label_invoice_th_um') : 'U.M') . "</th>
                <th>" . (app()->has('label_invoice_th_vat') ? app('label_invoice_th_vat') : 'TVA') . "</th>
                <th>" . (app()->has('label_invoice_th_quantity') ? app('label_invoice_th_quantity') : 'Cantitate') . "</th>
                <th>" . (app()->has('label_invoice_th_pu') ? app('label_invoice_th_pu') : 'Pret Unitar - RON') . "</th>
                <th>" . (app()->has('label_invoice_th_val') ? app('label_invoice_th_val') : 'Valoare - RON') . "</th>
                <th>" . (app()->has('label_invoice_th_valvat') ? app('label_invoice_th_valvat') : 'Valoare TVA - RON') . "</th>
                <th>" . (app()->has('label_invoice_th_total') ? app('label_invoice_th_total') : 'Total') . "</th>
            </tr>
        </thead>
        <tbody>";

        $voucherValue = $this->order->voucher_value + $this->order->promotion_value;
        $totalval = 0;
        $i = 0;
        if ($voucherValue &&  $voucherValue != 0) {
            $vatGroups = [];
            $amountnovoucher = $this->order->final_amount + $voucherValue - $this->order->delivery_price;
        }
        foreach ($this->order->orders as $item) {
            $vatRate = (int) $item->vat;
            $pu = $item->price / (1 + ($vatRate / 100));
            $totalval += $pu * $item->quantity;

            if ($voucherValue &&  $voucherValue != 0) {
                if (!isset($vatGroups[$vatRate])) {
                    $vatGroups[$vatRate] = [
                        'totalpu' => 0,
                        'total' => 0,
                    ];
                }
                $vatGroups[$vatRate]['totalpu'] += (($item->price / $amountnovoucher) * $item->quantity * $voucherValue) / (1 + ($vatRate / 100));
                $vatGroups[$vatRate]['total'] += ($item->price / $amountnovoucher) * $item->quantity * $voucherValue;
            }



            $htmlContent .= "
                <tr>
                    <td>" . ($i + 1) . "</td>
                    <td>" . $item->product->name . "<br> (" . $item->product->ean . ")</td>
                    <td>" . (app()->has('label_invoice_um_text') ? app('label_invoice_um_text') : 'buc.') . "</td>
                    <td>" . $vatRate . "</td>
                    <td>" . -$item->quantity . "</td>
                    <td>" . number_format($pu, 2) . "</td>
                    <td>" . -number_format($pu * $item->quantity, 2) . "</td>
                    <td>" . -number_format(($item->price - $pu) * $item->quantity, 2) . "</td>
                    <td>" . -number_format($item->price * $item->quantity, 2) . "</td>
                </tr>";
            $i++;
        }
        if ($voucherValue &&  $voucherValue != 0) {
            foreach ($vatGroups as $vatRate => $group) {
                $totalval -= $group['totalpu'];
                $htmlContent .= "
                    <tr>
                        <td>" . ($i + 1) . "</td>
                        <td>" . (app()->has('label_invoice_th_voucher') ? app('label_invoice_th_voucher') : 'Reducere') . "</td>
                        <td>" . (app()->has('label_invoice_um_text') ? app('label_invoice_um_text') : 'buc.') . "</td>
                        <td>" . $vatRate . "</td>
                        <td>-1</td>
                        <td>" . +number_format(+$group['totalpu'], 2) . "</td>
                        <td>" . +number_format(+$group['totalpu'], 2) . "</td>
                        <td>" . +number_format(+ ($group['total'] - $group['totalpu']), 2) . "</td>
                        <td>" . +number_format(+$group['total'], 2) . "</td>
                    </tr>";
                $i++;
            }
        }
        // delivery sistem
        $htmlContent .= "
        <tr>
        <td>" . ($i + 1) . "</td>
        <td>" . (app()->has('label_invoice_th_delivery') ? app('label_invoice_th_delivery') : 'Transport') . "</td>
        <td>" . (app()->has('label_invoice_um_text') ? app('label_invoice_um_text') : 'buc.') . "</td>
        <td>19</td>
        <td>-1</td>
        <td>" . number_format(($this->order->delivery_price / (1 + (19 / 100))), 2) . "</td>
        <td>" . -number_format(($this->order->delivery_price / (1 + (19 / 100))), 2) . "</td>
        <td>" . -number_format(($this->order->delivery_price - ($this->order->delivery_price / (1 + (19 / 100)))), 2) . "</td>
        <td>" . -number_format($this->order->delivery_price, 2) . "</td>
    </tr>";
        $totalval += $this->order->delivery_price / (1 + (19 / 100));
        // total row
        $htmlContent .= "
        <tr>
        <td colspan='6' style='font-weight: 700;text-align:right'><span>" . (app()->has('label_invoice_total_prev') ? app('label_invoice_total_prev') : 'Total') . "</span></td>
        <td style='font-weight: 700;'><span>" . -number_format($totalval, 2) . "</span></td>
        <td style='font-weight: 700;'><span>" . -number_format($this->order->final_amount - $totalval, 2) . "</span></td>
        <td style='font-weight: 700;'><span>" . -number_format($this->order->final_amount, 2) . "</span></td>
       </tr>";

        $htmlContent .= "
        </tbody>
    </table>
    <p style='text-align:right'><strong>" . (app()->has('label_invoice_th_totalfinal') ? app('label_invoice_th_totalfinal') : 'Total Plata ') . " " . -number_format($this->order->final_amount, 2) . " " . (app()->has('global_currency_primary_symbol') ? app('global_currency_primary_symbol') : 'lei') .
            "</strong></p><br>
    <p>" . (app()->has('label_invoice_cf') ? app('label_invoice_cf') : 'Cf. Comanda') . $this->order->order_number . "<br>" . (app()->has('label_invoice_footer') ? app('label_invoice_footer') : 'Please check invoice footer label') . "</p></body></html>";

        $pdf = PDF::loadHTML($htmlContent);
        $pdf->save($filePath);

        Invoice::create([
            'account_id' => $this->order->account_id,
            'order_id' => $this->order->id,
            'date' => $this->order->storno_date,
            'type' => 'storno',
            'path' => $filePath
        ]);

        $vat = $this->order->final_amount - $totalval;
        $type = 'storno_xml';
        $this->generate_invoice_xml($totalval, $vat, $type);

        session()->flash('notification', [
            'message' => 'Storno generate successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }

    public function render()
    {
        return view('livewire.show-order', [
            'order' => $this->order
        ]);
    }
    public function getOrderProperty()
    {
        return $this->orderQuery;
    }
    public function getOrderQueryProperty()
    {
        return Order::with([
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
        ])->find($this->orderId);
    }
    public function mount($orderId)
    {
        $this->circle = "#37583b";
        $this->orderId = $orderId;
        foreach ($this->order->orders as $orderItem) {

            $product = $orderItem->product;
            $interimQuantity = $product->quantity + $product->interim_quantity;

            if ($interimQuantity < $orderItem->quantity) {
                $this->circle = "#4a0a0f";
            }
        }
    }
    public function canceledit()
    {
        $this->edititem = null;
        $this->record = [];
    }
    public function edititem()
    {
        $this->statuses = Status::where('type', 'order')->get();
        $this->record = [
            'status_id' => $this->order->status_id,
            'invoice_date' => $this->order->invoice_date,
            'storno_date' => $this->order->storno_date,
            'promotion_value' => $this->order->promotion_value,
            'voucher_value' => $this->order->voucher_value,
            'delivery_price' => $this->order->delivery_price

        ];
        $this->edititem = true;
    }
    public function saveitem()
    {
        $new = $this->record ?? null;
        if (!is_null($new)) {
            $order = Order::find($this->orderId);
            $oldStatus = $order->status_id;
            $statusCloseId = Status::where('type', 'order')->where('name', 'canceled')->first()->id;

            $updatableFields = ['invoice_date', 'storno_date', 'promotion_value', 'voucher_value', 'delivery_price'];
            foreach ($updatableFields as $field) {
                if (array_key_exists($field, $new)) {
                    $order->$field = $new[$field];
                }
            }

            if (
                array_key_exists('promotion_value', $new) ||
                array_key_exists('voucher_value', $new) ||
                array_key_exists('delivery_price', $new)
            ) {
                $order->final_amount = max(
                    0,
                    $order->sum_amount + $order->delivery_price - $order->promotion_value - $order->voucher_value
                );
            }

            if (array_key_exists('status_id', $new)) {
                $order->status_id = $new['status_id'];
                $order->updated_at = now();

                if ($oldStatus != $new['status_id']) {
                    if ($new['status_id'] == $statusCloseId) {
                        foreach ($order->orders as $orderItem) {
                            $orderItem->product->quantity += $orderItem->quantity;
                            $orderItem->product->save();
                        }
                    } elseif ($oldStatus == $statusCloseId) {
                        foreach ($order->orders as $orderItem) {
                            $orderItem->product->quantity -= $orderItem->quantity;
                            $orderItem->product->save();
                        }
                    }
                }
            }

            $order->save();

            $this->emit('itemSaved');
            session()->flash('notification', [
                'message' => 'Record edited successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        }

        $this->record = [];
        $this->edititem = null;
    }

    public function confirmItemRemoval()
    {
        $this->delete = true;
    }
    public function cancelItemRemoval()
    {
        $this->delete = false;
    }
    public function deleteRecord()
    {
        $order = Order::findOrFail($this->orderId);
        foreach ($order->orders as $orderitem) {
            $orderitem->product->quantity += $orderitem->quantity;
            $orderitem->product->save();
            $orderitem->delete();
        }

        $invoices = Invoice::where('order_id', $this->orderId)->get();
        foreach ($invoices as $invoice) {
            $del = Invoice::find($invoice->id);
            if (File::exists($del->path)) {
                File::delete($del->path);
            }
            $del->delete();
        }
        $order->delete();
        $this->delete = false;
        return redirect()->route('orders')->with('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}
