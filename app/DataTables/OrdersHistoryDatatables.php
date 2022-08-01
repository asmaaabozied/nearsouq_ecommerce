<?php

namespace App\DataTables;

use App\Helpers\DTHelper;
use App\Models\OrdersHistory;
use App\Models\OptionDatatable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Session;

class OrdersHistoryDatatables extends DataTable
{
    private $crudName = 'ordersHistory';
    public $order_id = NULL;
    public $order_details_id = NULL;
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
            ->editColumn('shop_id', function ($model) {
                return (!empty($model->shop_id)) ? $model->shop_name : '';
            })
            ->editColumn('product_id', function ($model) {
                return (!empty($model->product_id)) ? $model->product_name : '';
            })
            ->editColumn('user_id', function ($model) {
                return (!empty($model->user_id)) ? $model->user_name : '';
            })
            ->editColumn('status', function ($model) {
                return (!empty($model->status)) ? trans('site.'.strtolower($model->status)) : '';
            })
            ->editColumn('processed_id', function ($model) {
                return (!empty($model->processed_id)) ? $model->role_name : '';
            })

            ->addColumn('action', function ($model) {
                $actions = '';

                //$actions .= DTHelper::dtEditButton(route($this->getRoutes()['update'], $model->id), trans('site.edit'), $this->getPermissions()['update']);
                //$actions .= DTHelper::dtDeleteButton(route($this->getRoutes()['delete'], $model->id), trans('site.delete'), $this->getPermissions()['delete']);

                return $actions;
            })
            ->escapeColumns(false)->with('order',$this->order);
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\OptionDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(OrdersHistory $model)
    {
        if(isset($this->order_id)){
            return OrdersHistory::where('order_id',$this->order_id)
            ->join('shops','shops.id','=','shop_id')
            ->join('products','products.id','=','product_id')
            ->join('users','users.id','=','user_id')
            ->join('roles','roles.id','=','processed_id')
            ->select('shops.name_'.app()->getLocale() .' as shop_name', 'products.name_'.app()->getLocale() .' as product_name','users.name as user_name','roles.name as role_name', 'orders_histories.*')
            ->newQuery();
        }elseif(isset($this->order_details_id)){
            return OrdersHistory::where('order_details_id',$this->order_details_id)
            ->join('shops','shops.id','=','shop_id')
            ->join('products','products.id','=','product_id')
            ->join('users','users.id','=','user_id')
            ->join('roles','roles.id','=','processed_id')
            ->select('shops.name_'.app()->getLocale() .' as shop_name', 'products.name_'.app()->getLocale() .' as product_name','users.name as user_name','roles.name as role_name', 'orders_histories.*')
            ->newQuery();
        }
            
        
    }
    public function count()
    {
        return OrdersHistory::where('order_id',$this->order_id)->count();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('orderDetaildatatables-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        //Button::make('create'),
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
            Column::make('shop_id')->title(trans('site.shop')),
//            Column::make('name_en')->title(trans('site.en.name')),
            //Column::make('shop')->title(trans('site.shop')),
            Column::make('product_id')->title(trans('site.product')),
            Column::make('order_details_id')->title(trans('site.orderDetails')),
            Column::make('user_id')->title(trans('site.user')),
            Column::make('processed_id')->title(trans('site.processed')),
            Column::make('status')->title(trans('site.status')),
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
        return 'OrderDetailDatatables_' . date('YmdHis');
    }
}
