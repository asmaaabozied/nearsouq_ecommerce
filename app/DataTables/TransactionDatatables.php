<?php

namespace App\DataTables;

use App\Helpers\DTHelper;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Shop;
use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\OrderDatatable;
use App\Models\ShopSetting;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TransactionDatatables extends DataTable
{

    private $crudName = 'transactions';

    private function getRoutes() {
        return [
            'update' => "dashboard.$this->crudName.edit",
            'delete' => "dashboard.$this->crudName.destroy",
            'block' =>  "dashboard.$this->crudName.block",
        ];
    }

    private function getPermissions() {
        return [
            'update' => 'update_'.$this->crudName,
            'delete' => 'delete_'.$this->crudName,
            'create' => 'create_'.$this->crudName
        ];
    }
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            // ->editColumn('created_at', function ($model) {
            //     return (!empty($model->created_at)) ? $model->created_at->diffForHumans() : '';
            // })
            ->editColumn('shop_id', function ($model) {
                return (!empty($model->shop->brand_name)) ? $model->shop->brand_name : '';
            })
            ->editColumn('user_id', function ($model) {
                return (!empty($model->user->name)) ? $model->user->name : '';
            })
            ->editColumn('transaction_date', function ($model) {
                return (!empty($model->last_transaction)) ? $model->last_transaction->transaction_date : '-';
            })
            ->editColumn('balance', function ($model) {
                $payment = ShopSetting::where('shop_id',$model->id)->pluck('payment');

                $transactions_commsion1 = Transaction::where('shop_id',$model->id)->where('name_id',1)->where('final_balance','>',0)->where('debit','>',0)->sum('final_balance');
                $transactions_credit = Transaction::where('shop_id',$model->id)->where('name_id',3)->where('final_balance','<',0)->sum('credit');
                $transaction_debit = Transaction::where('shop_id',$model->id)->where('name_id',4)->sum('debit');
                $result = $transactions_credit - $transaction_debit;
                $sql['old_commsion'] =  $result ;
                
                $transactions_merchant1 = Transaction::where('shop_id',$model->id)->where('name_id',1)->where('final_balance','>',0)->where('debit','>',0)->sum('final_balance');
                $transactions_merchant = Transaction::where('shop_id',$model->id)->where('name_id',3)->where('final_balance','>=',0)->where('credit','>',0)->sum('credit');
                $transactions_merchant_credit = Transaction::where('shop_id',$model->id)->where('name_id',2)->where('final_balance','>=',0)->sum('credit');
        
                $sql['old_merchant'] =  $transactions_merchant1 - $transactions_merchant - ($transactions_merchant_credit );
                
                $balance =$sql['old_merchant'] -  $sql['old_commsion'];
                return (!count($payment) == 0) ? $balance  : '0';
            })
        ->addColumn('action', function ($model) {
                $actions = '';
                $actions .= DTHelper::dtShowButton(route('dashboard.showTransactions', $model->id), trans('site.show'), $this->getPermissions()['update']);

                // $actions .= DTHelper::dtDeleteButton(route($this->getRoutes()['delete'], $model->id), trans('site.delete'), $this->getPermissions()['delete']);

                return $actions;
            });
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\TransactionDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Shop $model,Request $request)
    {
        if ($this->request()->has('shop_id') && $this->request()->has('start_date') && $this->request()->has('end_date')) {
            $shop = Shop::find(request()->shop_id);
            

            return $shop->newQuery();
            }

        return $model->newQuery();
    }
    public function count()
    {
        return Merchant::all()->count();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('transactiondatatables-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id'),
            Column::make('name_ar')->title(trans('site.ar.name')),
            Column::make('name_en')->title(trans('site.en.name')),
            Column::computed('balance')->title(trans('site.balance')),
            Column::computed('transaction_date')->title(trans('site.last_transaction')),
            /*Column::make('payment_type')->title(trans('site.payment_type')),
            Column::make('delivery_cost')->title(trans('site.cost')),
            Column::make('payment_status')->title(trans('site.status')),
            Column::make('bill_number')->title(trans('site.number')),
            Column::make('total')->title(trans('site.total')),
            Column::make('user_id')->title(trans('site.users')),
            Column::make('address_id')->title(trans('site.address')),
            Column::make('delivery_distance_in_km')->title(trans('site.distance')),
            Column::make('created_at')->title(trans('site.created_at')),*/
            Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(60)->trans(trans('site.actions'))
            ->addClass('text-center'),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'transactionDatatables_' . date('YmdHis');
    }
}
