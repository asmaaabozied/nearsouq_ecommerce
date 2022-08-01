<?php

namespace App\DataTables;

use App\Helpers\DTHelper;
use App\Models\Order;
use App\Models\Product;
use App\Models\ReportOrder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReportOrdersDataTable extends DataTable
{
    private $crudName = 'order';

    private function getRoutes() {
        return [
            'update' => "dashboard.$this->crudName.edit",
            'delete' => "dashboard.$this->crudName.destroy",
            'block' =>  "dashboard.$this->crudName.block",
            'details' => "dashboard.$this->crudName.details",
        ];
    }

    private function getPermissions() {
        return [
            'update' => 'update_'.$this->crudName,
            'delete' => 'delete_'.$this->crudName,
            'details' => 'details_'.$this->crudName,
            //'create' => 'create_'.$this->crudName
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
            ->editColumn('created_at', function ($model) {
                return (!empty($model->created_at)) ? $model->created_at->diffForHumans() : '';
            })

            ->editColumn('user_id', function ($model) {
                return (!empty($model->user)) ? $model->user->name : '';
            })
            ->addColumn('action', function ($model) {
                $actions = '';
                if(!empty($model->pdf1)){
                    $actions .= DTHelper::dtDownloadButton((url($model->pdf1)), trans('site.download'), $this->getPermissions()['update']);
                }
                $actions .= DTHelper::dtShowButton(route($this->getRoutes()['details'], $model->id), trans('site.details'), $this->getPermissions()['update']);

                return $actions;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ReportOrder $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model)
    {

        if ($this->request()->has('start_date') && $this->request()->has('end_date')) {

            $start = \Carbon\Carbon::parse($this->request()->get('start_date'));
            $end = Carbon::parse($this->request()->get('end_date'))->addDay();
            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant'))



            {
                $orders= Order::where('user_id',Auth::id())->whereBetween('created_at', [$start, $end]);
                return $orders ->newQuery();


            }else{
                $orders= Order::whereBetween('created_at', [$start, $end]);
                return $orders ->newQuery();

            }
        }


        if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
            return Order::where('user_id',Auth::id())->newQuery();
        }else{
            return $model->newQuery();
        }
    }

    public function count()
    {
        if ($this->request()->has('start_date') && $this->request()->has('end_date')) {

            $start = \Carbon\Carbon::parse($this->request()->get('start_date'));
            $end = Carbon::parse($this->request()->get('end_date'))->addDay();
            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
                $Order = Order::where('user_id',Auth::id())->whereBetween('created_at', [$start, $end]);
            }else{
                $Order = Order::whereBetween('created_at', [$start, $end]);
            }
            return $Order->count();

        } else {
            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {

                return Order::where('user_id',Auth::id())->count();
            }else {
                return Order::all()->count();
            }
        }

    }
    public function countmonth()
    {
        $dateS = Carbon::now()->startOfMonth()->subMonth(1);
        $dateE = Carbon::now()->startOfMonth()->addDay();
        if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
            return Order::where('user_id',Auth::id())->whereBetween('created_at', [$dateS, $dateE])->count();
        }else{
            return Order::whereBetween('created_at', [$dateS, $dateE])->count();

        }
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('reportorders-table')
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
            Column::make('confirmed')->title(trans('site.confirmed')),
            Column::make('delivery_cost')->title(trans('site.delivery_cost')),
            Column::make('payment_status')->title(trans('site.status')),
            Column::make('code')->title(trans('site.code')),
            Column::make('user_id')->title(trans('site.user')),
            Column::make('subtotal')->title(trans('site.subtotal')),
            Column::make('total')->title(trans('site.total')),
            Column::make('bill_number')->title(trans('site.bill_number')),
            Column::make('created_at')->title(trans('site.created_at')),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center')
                ->title(trans('site.action')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ReportOrders_' . date('YmdHis');
    }
}
