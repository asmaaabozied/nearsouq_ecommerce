<?php

namespace App\DataTables;

use App\Models\Wallet;
use App\Models\Walletlog;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class WalletlogDataTable extends DataTable
{
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
            
               ->editColumn('created_at', function ($model) {
                return (!empty($model->created_at)) ? $model->created_at->diffForHumans() : '';
            })
                 ->editColumn('operation', function ($model) {
                return (!empty($model->operation)) ? trans('site.'.$model->operation) : '';
            })
            ->addColumn('action', 'walletlog.action')
        ->escapeColumns(false)->with('id',$this->id);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Walletlog $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Walletlog $model)
    {


        $user = Wallet::where('id',request()->id)->first()->user_id;

        $logs = Walletlog::where('user_id', $user)->newQuery();

        return $logs;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('walletlog-table')
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
            Column::make('amount')->title(trans('site.amount')),

            Column::make('total')->title(trans('site.total')),
            Column::make('comment')->title(trans('site.comment')),
            Column::make('payment_method')->title(trans('site.payment_method')),
            Column::make('operation')->title(trans('site.operation')),
            Column::make('created_at')->title(trans('site.created_at')),

   
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Walletlog_' . date('YmdHis');
    }
}
