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

class ShowOrder extends Component
{
    public $orderId;
    public $record = [];
    public $edititem = null;
    public $delete = false;
    public $statuses;
    public $invoice_sdatabase;
    public $storno_sdatabase;


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

        $voucherValue = $this->order->voucher_value;
        $totalval = 0;
        $i = 0;
        if ($voucherValue &&  $voucherValue != 0) {
            $vatGroups = [];
            $amountnovoucher = $this->order->final_amount + $this->order->voucher_value - $this->order->delivery_price;
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
                        <td>" . (app()->has('label_invoice_th_voucher') ? app('label_invoice_th_voucher') : 'Reducere Voucher') . " - " . $vatRate . "%</td>
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
    <p>" . (app()->has('label_invoice_footer') ? app('label_invoice_footer') : 'Please check invoice footer label') . "</p></body></html>";

        $pdf = PDF::loadHTML($htmlContent);
        $pdf->save($filePath);

        session()->flash('notification', [
            'message' => 'Invoice generated successfully!',
            'type' => 'success',
            'title' => 'Invoice Created'
        ]);
        Invoice::create([
            'account_id' => $this->order->account_id,
            'order_id' => $this->order->id,
            'date' => $this->order->invoice_date,
            'type' => 'invoice',
            'path' => $filePath
        ]);

        session()->flash('notification', [
            'message' => 'Invoice generate successfully!',
            'type' => 'success',
            'title' => 'Success'
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

        $voucherValue = $this->order->voucher_value;
        $totalval = 0;
        $i = 0;
        if ($voucherValue &&  $voucherValue != 0) {
            $vatGroups = [];
            $amountnovoucher = $this->order->final_amount + $this->order->voucher_value - $this->order->delivery_price;
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
                        <td>" . (app()->has('label_invoice_th_voucher') ? app('label_invoice_th_voucher') : 'Reducere Voucher') . " - " . $vatRate . "%</td>
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
    <p style='text-align:right'><strong>" . (app()->has('label_invoice_th_totalfinal') ? app('label_invoice_th_totalfinal') : 'Total Plata ') . " " . -number_format($this->order->final_amount, 2) . " " . (app()->has('global_currency_primary_symbol') ? app('global_currency_primary_symbol') : 'lei') . "</strong></p><br>
    <p>" . (app()->has('label_invoice_footer') ? app('label_invoice_footer') : 'Please check invoice footer label') . "</p></body></html>";

        $pdf = PDF::loadHTML($htmlContent);
        $pdf->save($filePath);

        session()->flash('notification', [
            'message' => 'Invoice generated successfully!',
            'type' => 'success',
            'title' => 'Invoice Created'
        ]);
        Invoice::create([
            'account_id' => $this->order->account_id,
            'order_id' => $this->order->id,
            'date' => $this->order->storno_date,
            'type' => 'storno',
            'path' => $filePath
        ]);

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
        return Order::with('account', 'currency', 'status', 'cart', 'orders')->find($this->orderId);
    }
    public function mount($orderId)
    {
        $this->orderId = $orderId;
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
            'storno_date' => $this->order->storno_date

        ];
        $this->edititem = true;
    }
    public function saveitem()
    {
        $new = $this->record ?? NULL;
        if (!is_null($new)) {
            $order = Order::find($this->orderId);
            $oldstatus = $order->status_id;
            $statusclose = Status::where('type', 'order')->where('name', 'canceled')->first()->id;
            if (array_key_exists('invoice_date', $new)) {
                $order->invoice_date = $new['invoice_date'];
            }
            if (array_key_exists('storno_date', $new)) {
                $order->storno_date = $new['storno_date'];
            }
            if (array_key_exists('status_id', $new)) {
                $order->status_id = $new['status_id'];
                $order->updated_at = now();
                $order->save();
                if ($oldstatus != $new['status_id'] && $new['status_id'] == $statusclose) {
                    foreach ($order->orders as $orderitem) {
                        $orderitem->product->quantity += $orderitem->quantity;
                        $orderitem->product->save();
                    }
                } elseif ($oldstatus == $statusclose && $new['status_id'] != $statusclose) {
                    foreach ($order->orders as $orderitem) {
                        $orderitem->product->quantity -= $orderitem->quantity;
                        $orderitem->product->save();
                    }
                }
                $this->emit('itemSaved');
                session()->flash('notification', [
                    'message' => 'Record edited successfully!',
                    'type' => 'success',
                    'title' => 'Success'
                ]);
            }
            $order->save();
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

        $order->delete();
        $this->delete = false;
        return redirect()->route('orders')->with('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}
