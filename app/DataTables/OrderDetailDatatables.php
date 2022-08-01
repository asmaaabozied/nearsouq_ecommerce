<?php

namespace App\DataTables;

use App\Helpers\DTHelper;
use App\Models\OrderDetail;
use App\Models\OptionDatatable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Session;

class OrderDetailDatatables extends DataTable
{
    private $crudName = 'orderDetail';
    private function getRoutes() {
        return [
            'update' => "dashboard.$this->crudName.edit",
            'delete' => "dashboard.$this->crudName.destroy",
            'block' =>  "dashboard.$this->crudName.block",
            'changeStatus' => "dashboard.$this->crudName.update",
            'addCaptainOrder' => "dashboard.$this->crudName.update",
            'history' => "dashboard.$this->crudName.history",

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
            ->editColumn('price', function ($model) {
                return (!empty($model->discount_price)) ? $model->discount_price : $model->price;
            })
            ->editColumn('created_at', function ($model) {
                return (!empty($model->created_at)) ? $model->created_at->diffForHumans() : '';
            })
            ->editColumn('status', function ($model) {
                $status = '';
                $status .= DTHelper::dtStatus(route($this->getRoutes()['changeStatus'], $model->id), trans('site.changeStatus'), $this->getPermissions()['update'], $model->id);

                return $status;
            })
            ->editColumn('captain_id', function ($model) {
                $captain = '';
                $captain .= DTHelper::dtCaptain(route($this->getRoutes()['addCaptainOrder'], $model->id), trans('site.addCaptainOrder'), $this->getPermissions()['update'], $model->id);

                return $captain;
            })
            ->addColumn('shop', function ($model) {
                if(app()->getLocale() == 'ar')
                return $model->shop_name_ar;
                else
                return $model->shop->name_en;
            })
            ->editColumn('total', function ($model) {
                if($model->discount_price != NULL)
                return round($model->quantity * $model->discount_price);
                else 
                return round($model->quantity  * $model->price);

            })
            ->addColumn('image', function ($model) {
                $url=asset("uploads/shops/products/$model->image");
                                 return "<img alt='' src='{$url}' width='70' height='60' class=\"z-depth-4 circle img-rounded\"/>";
            })
            ->addColumn('action', function ($model) {
                $actions = '';

                $actions .= DTHelper::dtEditButton(route($this->getRoutes()['update'], $model->id), trans('site.edit'), $this->getPermissions()['update']);
                //$actions .= DTHelper::dtDeleteButton(route($this->getRoutes()['delete'], $model->id), trans('site.delete'), $this->getPermissions()['delete']);
                $actions .= DTHelper::dtHistoryButton(route($this->getRoutes()['history'], $model->id), trans('site.history'), $this->getPermissions()['update']);

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
    public function query(OrderDetail $model)
    {
        if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
        return OrderDetail::where('order_id',$this->order_id)
        ->where('shop_id',Session::get('shop_id'))
        ->join('shops','shops.id','=','shop_id')->select('shops.name_ar as shop_name_ar', 'order_details.*')
        ->newQuery();
        }else{
            return OrderDetail::where('order_id',$this->order_id)
            ->join('shops','shops.id','=','shop_id')->select('shops.name_ar as shop_name_ar', 'order_details.*')
            ->newQuery();
        }
    }
    public function count()
    {
        return OrderDetail::where('order_id',$this->order_id)->count();
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
            Column::make('name_ar')->title(trans('site.ar.name')),
//            Column::make('name_en')->title(trans('site.en.name')),
            //Column::make('shop')->title(trans('site.shop')),
            Column::make('status')->title(trans('site.status')),
            Column::make('price')->title(trans('site.price')),
            Column::make('captain_id')->title(trans('site.captain')),
            Column::make('quantity')->title(trans('site.quantity')),
            Column::computed('total')->title(trans('site.total')),
            Column::computed('image')->title(trans('site.image')),
            Column::make('created_at')->title(trans('site.created_at')),
            Column::computed('shop')
            ->exportable(true)
            ->printable(true)
            ->width(120)
            ->addClass('text-center')
            ->title(trans('site.shop')),
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
        return 'OrderDetailDatatables_' . date('YmdHis');
    }
}
