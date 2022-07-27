<?php

namespace Rutatiina\GoodsDelivered\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use Rutatiina\Contact\Traits\ContactTrait;
use Rutatiina\GoodsDelivered\Models\GoodsDelivered;
use Rutatiina\GoodsDelivered\Traits\Item as TxnItem;
use Rutatiina\FinancialAccounting\Classes\Transaction;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Rutatiina\GoodsDelivered\Services\GoodsDeliveredService;
use Rutatiina\FinancialAccounting\Traits\FinancialAccountingTrait;

class GoodsDeliveredController extends Controller
{
    //use TenantTrait;
    use ContactTrait;
    use FinancialAccountingTrait;
    use TxnItem; // >> get the item attributes template << !!important

    private  $txnEntreeSlug = 'delivery-note';

    public function __construct()
    {
        $this->middleware('permission:goods-delivered.view');
		$this->middleware('permission:goods-delivered.create', ['only' => ['create','store']]);
		$this->middleware('permission:goods-delivered.update', ['only' => ['edit','update']]);
		$this->middleware('permission:goods-delivered.delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        //load the vue version of the app
        if (!FacadesRequest::wantsJson()) {
            return view('ui.limitless::layout_2-ltr-default.appVue');
        }

        $per_page = ($request->per_page) ? $request->per_page : 20;

        $txns = GoodsDelivered::latest()->paginate($per_page);

        return [
            'tableData' => $txns
        ];
    }

    private function nextNumber()
    {
        $txn = GoodsDelivered::latest()->first();
        $settings = GoodsDeliveredService::settings();

        return $settings->number_prefix.(str_pad((optional($txn)->number+1), $settings->minimum_number_length, "0", STR_PAD_LEFT)).$settings->number_postfix;
    }

    public function create()
    {
        //load the vue version of the app
        if (!FacadesRequest::wantsJson())
        {
            return view('ui.limitless::layout_2-ltr-default.appVue');
        }

        $tenant = Auth::user()->tenant;

        $txnAttributes = (new GoodsDelivered())->rgGetAttributes();

        $txnAttributes['number'] = $this->nextNumber();

        $txnAttributes['status'] = 'Approved';
        $txnAttributes['contact_id'] = '';
        $txnAttributes['contact'] = json_decode('{"currencies":[]}'); #required
        $txnAttributes['date'] = date('Y-m-d');
        $txnAttributes['base_currency'] = $tenant->base_currency;
        $txnAttributes['quote_currency'] = $tenant->base_currency;
        $txnAttributes['taxes'] = json_decode('{}');
        $txnAttributes['isRecurring'] = false;
        $txnAttributes['recurring'] = [
            'date_range' => [],
            'day_of_month' => '*',
            'month' => '*',
            'day_of_week' => '*',
        ];
        $txnAttributes['contact_notes'] = null;
        $txnAttributes['terms_and_conditions'] = null;
        $txnAttributes['items'] = [$this->itemCreate()];

        $data = [
            'pageTitle' => 'Create Delivery Note', #required
            'pageAction' => 'Create', #required
            'txnUrlStore' => '/goods-delivered', #required
            'txnAttributes' => $txnAttributes, #required
        ];

        return $data;

    }

    public function store(Request $request)
    {
        $storeService = GoodsDeliveredService::store($request);

        if ($storeService == false)
        {
            return [
                'status' => false,
                'messages' => GoodsDeliveredService::$errors
            ];
        }

        return [
            'status' => true,
            'messages' => ['Goods delivered note saved'],
            'number' => 0,
            'callback' => URL::route('goods-delivered.show', [$storeService->id], false)
        ];

    }

    public function show($id)
    {
        //load the vue version of the app
        if (!FacadesRequest::wantsJson()) {
            return view('ui.limitless::layout_2-ltr-default.appVue');
        }

        $txn = GoodsDelivered::findOrFail($id);
        $txn->load('contact', 'items', 'ledgers');
        $txn->setAppends([
            'number_string',
            'total_in_words',
        ]);

        return $txn->toArray();
    }

    public function edit($id)
	{
        //load the vue version of the app
        if (!FacadesRequest::wantsJson())
        {
            return view('ui.limitless::layout_2-ltr-default.appVue');
        }

        $txnAttributes = GoodsDeliveredService::edit($id);

        return [
            'pageTitle' => 'Edit Goods delivered note', #required
            'pageAction' => 'Edit', #required
            'txnUrlStore' => '/goods-delivered/' . $id, #required
            'txnAttributes' => $txnAttributes, #required
        ];
    }

    public function update(Request $request)
    {
        //print_r($request->all()); exit;

        $storeService = GoodsDeliveredService::update($request);

        if ($storeService == false)
        {
            return [
                'status' => false,
                'messages' => GoodsDeliveredService::$errors
            ];
        }

        return [
            'status' => true,
            'messages' => ['Goods delivered note updated'],
            'number' => 0,
            'callback' => URL::route('goods-delivered.show', [$storeService->id], false)
        ];
    }

    public function destroy($id)
    {
        $destroy = GoodsDeliveredService::destroy($id);

        if ($destroy)
        {
            return [
                'status' => true,
                'messages' => ['Goods delivered note deleted'],
                'callback' => URL::route('goods-delivered.index', [], false)
            ];
        }
        else
        {
            return [
                'status' => false,
                'messages' => GoodsDeliveredService::$errors
            ];
        }
    }

	#-----------------------------------------------------------------------------------

    public function approve($id)
    {
        $approve = GoodsDeliveredService::approve($id);

        if ($approve == false)
        {
            return [
                'status' => false,
                'messages' => GoodsDeliveredService::$errors
            ];
        }

        return [
            'status' => true,
            'messages' => ['Goods delivered note Approved'],
        ];

    }

    public function copy($id)
    {
        //load the vue version of the app
        if (!FacadesRequest::wantsJson())
        {
            return view('ui.limitless::layout_2-ltr-default.appVue');
        }

        $txnAttributes = GoodsDeliveredService::copy($id);

        return [
            'pageTitle' => 'Copy Goods delivered note', #required
            'pageAction' => 'Copy', #required
            'txnUrlStore' => '/goods-delivered', #required
            'txnAttributes' => $txnAttributes, #required
        ];
    }

    public function datatables()
	{
        $txns = Transaction::setRoute('show', route('accounting.inventory.goods-delivered.show', '_id_'))
			->setRoute('edit', route('accounting.inventory.goods-delivered.edit', '_id_'))
			->paginate(false)
			->findByEntree($this->txnEntreeSlug);

        return Datatables::of($txns)->make(true);
    }

    public function exportToExcel(Request $request)
	{
        $txns = collect([]);

        $txns->push([
            'DATE',
            'DOCUMENT#',
            'REFERENCE',
            'CUSTOMER',
            'STATUS',
            'EXPIRY DATE',
            'TOTAL',
            ' ', //Currency
        ]);

        foreach (array_reverse($request->ids) as $id) {
            $txn = Transaction::transaction($id);

            $txns->push([
                $txn->date,
                $txn->number,
                $txn->reference,
                $txn->contact_name,
                $txn->status,
                $txn->expiry_date,
                $txn->total,
                $txn->base_currency,
            ]);
        }

        $export = $txns->downloadExcel(
            'maccounts-goods-delivered-export-'.date('Y-m-d-H-m-s').'.xlsx',
            null,
            false
        );

        //$books->load('author', 'publisher'); //of no use

        return $export;
    }
}
