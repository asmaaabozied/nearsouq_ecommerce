<?php

namespace App\DataTables;

use App\Helpers\DTHelper;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Shop;
use App\Models\OrderDatatable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class transactionDatatables2 extends DataTable
{

    private $crudName = 'orders';

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
            ->editColumn('address_id', function ($model) {
                return (!empty($model->address->first_name)) ? $model->address->first_name : '';
            })
            ->editColumn('user_id', function ($model) {
                return (!empty($model->user->name)) ? $model->user->name : '';
            })
            ->editColumn('select_orders', static function ($row) {
                return '<input type="checkbox" name="order_id[]"
                value="'.$row->id.'"/>';
            })->rawColumns(['select_orders'])
            ->editColumn('payment_status', function ($model) {

                if ($model->payment_status =='PAID') {
                    return trans('site.PAID');

                }else{
                    return trans('site.NOT_PAID');

                }
            })

        ->addColumn('action', function ($model) {
                $actions = '';

                // $actions .= DTHelper::dtDeleteButton(route($this->getRoutes()['delete'], $model->id), trans('site.delete'), $this->getPermissions()['delete']);

                return $actions;
            });
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\OrderDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model,Request $request)
    {
        if ($this->request()->has('shop_id') && $this->request()->has('start_date') && $this->request()->has('end_date')) {
            $shop=Shop::find(request()->shop_id);
            $start = \Carbon\Carbon::parse( $request->start_date);
            $end =Carbon::parse( $request->end_date)->addDay();
            $shop->whereBetween('created_at', [$start, $end]);

            return $shop->orders()->newQuery();

            }

        return $model->newQuery();
    }
    public function count()
    {
        return Order::all()->count();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('orderdatatables-table')
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
            Column::make('select_orders')->title(trans('site.selected')),
            Column::make('id'),
            Column::make('payment_type')->title(trans('site.payment_type')),
            Column::make('delivery_cost')->title(trans('site.cost')),
            Column::make('payment_status')->title(trans('site.status')),
            Column::make('bill_number')->title(trans('site.number')),
            Column::make('total')->title(trans('site.total')),
            Column::make('user_id')->title(trans('site.users')),
            Column::make('address_id')->title(trans('site.address')),
            Column::make('delivery_distance_in_km')->title(trans('site.distance')),
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
        return 'transactionDatatables_' . date('YmdHis');
    }
}
