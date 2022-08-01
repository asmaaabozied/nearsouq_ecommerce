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
use App\User;
class transactionPageDatatables extends DataTable
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
            ->editColumn('name_id', function ($model) {
                return (!empty($model->name->name_ar)) ? $model->name->name_ar : '';
            })
            ->editColumn('user_id', function ($model) {
                return (!empty($model->user->name)) ? $model->user->name : '';
            })

        ->addColumn('action', function ($model) {
                $actions = '';
                $actions .= DTHelper::dtShowButton(route('dashboard.order.details', $model->order_id), trans('site.show'), $this->getPermissions()['update']);

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
    public function query(Transaction $model,Request $request)
    {
        if ($this->request()->has('shop_id')) {
            $shop = Transaction::where('shop_id',request()->shop_id)->newQuery();
            

            return $shop->newQuery();
            }

        return $model->newQuery();
    }
    public function count()
    {
        return Transaction::where('shop_id',1)->count();
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
            Column::computed('name_id')->title(trans('site.name')),
            Column::make('debit')->title(trans('site.debit')),
            Column::make('credit')->title(trans('site.credit')),
            Column::make('final_balance')->title(trans('site.final_balance')),
            Column::make('transaction_date')->title(trans('site.transaction_date')),
            Column::make('user_id')->title(trans('site.username')),
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
