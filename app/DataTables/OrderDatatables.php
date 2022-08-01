<?php

namespace App\DataTables;

use App\Helpers\DTHelper;
use App\Models\Order;
use App\User;
use Session;
use App\Models\OptionDatatable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrderDatatables extends DataTable
{
    private $crudName = 'order';
    public $type = NULL;

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
            ->editColumn('order_id', function ($model) {
                return $model->id ? $model->id : '';
            })
            ->editColumn('city', function ($model) {
                return $model->city ? $model->city : '';
            })
            ->editColumn('neighborhood', function ($model) {
                return $model->neighborhood ? $model->neighborhood : '';
            })
            ->editColumn('created_at', function ($model) {
                return (!empty($model->created_at)) ? $model->created_at->diffForHumans() : '';
            })
            ->addColumn('action', function ($model) {
                $user_id = auth()->id();
                $user = User::find($user_id);
                $pdf =  'uploads/invoices/ordercommsion.'.$model->id.'.shop'.$user->shop_id.'.pdf';
                $actions = '';
                if(auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')){
                    $actions .= DTHelper::dtDownloadButton((url($pdf)), trans('site.download'), $this->getPermissions()['update']);
                }
                if(!empty($model->pdf1)){
                    $actions .= DTHelper::dtDownloadButton((url($model->pdf1)), trans('site.download'), $this->getPermissions()['update']);
                }
                $actions .= DTHelper::dtShowButton(route($this->getRoutes()['details'], $model->id), trans('site.details'), $this->getPermissions()['update']);
                //$actions .= DTHelper::dtEditButton(route($this->getRoutes()['update'], $model->id), trans('site.edit'), $this->getPermissions()['update']);
                //$actions .= DTHelper::dtDeleteButton(route($this->getRoutes()['delete'], $model->id), trans('site.delete'), $this->getPermissions()['delete']);

                return $actions;
            });
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\OptionDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model)
    {
        $orders_not_delivered = Order::Join('order_details','orders.id','=','order_details.order_id')
        //->groupBy('order_details.order_id')
        ->where(function ($q) {
                $q->whereIN('order_details.status',['READY','RECEIVED','APPROVED_BY_CAPTAIN','NOT_DELIVERED']);
        })
        ->distinct()
        ->select('orders.id')->where('CONFIRMED','!=','FALSE')->pluck('orders.id');

        if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
            return Order::join('users','users.id','=','user_id')
            ->join('addresses','addresses.id','=','address_id')
            ->join('order_details','order_details.order_id','=','orders.id')
            ->where('order_details.shop_id',Session::get('shop_id'))
            ->where(function ($q) use($orders_not_delivered){
                if($this->type === 'NOT_DELIVERED'){
                    $q->whereIN('order_details.status',['READY','RECEIVED','APPROVED_BY_CAPTAIN','NOT_DELIVERED'])
                    ->whereNotIn('order_details.status',['DELIVERED','CANCELED','CANCELLED_ACCEPTED','CANCELLED_DENIED','RETURNED','RETURNED_ACCEPTED','RETURNED_DENIED']);      
                }elseif($this->type === 'RETURNED'){
                    $q->whereIN('order_details.status',['RETURNED','RETURNED_ACCEPTED','RETURNED_DENIED'])
                    ->whereNotIn('order_details.status',['DELIVERED','NOT_DELIVERED','RETURNED','READY','RECEIVED','APPROVED_BY_CAPTAIN','CANCELED','CANCELLED_ACCEPTED','CANCELLED_DENIED'])
                    ->whereNotIn('orders.id',$orders_not_delivered);      
                }elseif($this->type === 'DELIVERED' ){
                    $q->whereIN('order_details.status',['DELIVERED'])
                    ->whereNotIn('order_details.status',['CANCELED','CANCELLED_ACCEPTED','CANCELLED_DENIED','RETURNED','RETURNED_ACCEPTED','RETURNED_DENIED','READY','RECEIVED','APPROVED_BY_CAPTAIN','NOT_DELIVERED'])
                    ->whereNotIn('orders.id',$orders_not_delivered);     
                }elseif($this->type === 'CANCELED'){
                    $q->whereNotIn('order_details.status',['DELIVERED','NOT_DELIVERED','RETURNED','READY','RECEIVED','APPROVED_BY_CAPTAIN','RETURNED_ACCEPTED','RETURNED_DENIED'])
                    ->whereIN('order_details.status',['CANCELED','CANCELLED_ACCEPTED','CANCELLED_DENIED'])
                    ->whereNotIn('orders.id',$orders_not_delivered);
                }
            })
            // ->select('users.*','addresses.*','orders.*','orders.id as order_id')->where('CONFIRMED','!=','FALSE')->orderBy('orders.id','DESC')->newQuery();
              ->select('users.name','addresses.city','addresses.neighborhood','orders.*','orders.id as order_id')->where('CONFIRMED','!=','FALSE')->orderBy('orders.id','DESC')->newQuery();

        }else{
            return Order::join('users','users.id','=','user_id')
            ->join('addresses','addresses.id','=','address_id')
            ->Join('order_details','orders.id','=','order_details.order_id')
            ->groupBy('order_details.order_id')
            ->where(function ($q) use($orders_not_delivered){
                if($this->type === 'NOT_DELIVERED'){
                    $q->whereIN('order_details.status',['READY','RECEIVED','APPROVED_BY_CAPTAIN','NOT_DELIVERED'])
                    ->whereNotIn('order_details.status',['DELIVERED','CANCELED','CANCELLED_ACCEPTED','CANCELLED_DENIED','RETURNED','RETURNED_ACCEPTED','RETURNED_DENIED']);      
                }elseif($this->type === 'RETURNED'){
                    $q->whereIN('order_details.status',['RETURNED','RETURNED_ACCEPTED','RETURNED_DENIED'])
                    ->whereNotIn('order_details.status',['DELIVERED','NOT_DELIVERED','RETURNED','READY','RECEIVED','APPROVED_BY_CAPTAIN','CANCELED','CANCELLED_ACCEPTED','CANCELLED_DENIED'])
                    ->whereNotIn('orders.id',$orders_not_delivered);      
                }elseif($this->type === 'DELIVERED' ){
                    $q->whereIN('order_details.status',['DELIVERED'])
                    ->whereNotIn('order_details.status',['CANCELED','CANCELLED_ACCEPTED','CANCELLED_DENIED','RETURNED','RETURNED_ACCEPTED','RETURNED_DENIED','READY','RECEIVED','APPROVED_BY_CAPTAIN','NOT_DELIVERED'])
                    ->whereNotIn('orders.id',$orders_not_delivered);     
                }elseif($this->type === 'CANCELED'){
                    $q->whereNotIn('order_details.status',['DELIVERED','NOT_DELIVERED','RETURNED','READY','RECEIVED','APPROVED_BY_CAPTAIN','RETURNED_ACCEPTED','RETURNED_DENIED'])
                    ->whereIN('order_details.status',['CANCELED','CANCELLED_ACCEPTED','CANCELLED_DENIED'])
                    ->whereNotIn('orders.id',$orders_not_delivered);
                }
            })
            ->select('users.name','addresses.city','addresses.neighborhood','orders.*','orders.id as order_id')->where('CONFIRMED','!=','FALSE')->orderBy('orders.id','DESC')->newQuery();
        }
        
    }

    public function count()
    {
        return Order::where('CONFIRMED','!=','FALSE')->count();
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
        if(auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')){
            return [
                Column::make('id')->title(trans('site.id')),
                Column::make('confirmed')->title(trans('site.confirmed')),
                //Column::make('delivery_cost')->title(trans('site.en.delivery_cost')),
                //Column::make('users.name')->title(trans('site.user')),
                [ 'data' => 'name', 'name' => 'users.name', 'title' => trans('site.username') ],
                //[ 'data' => 'address', 'name' => 'addresses.city', 'title' => 'address' ],
                //Column::make('address_id')->title(trans('site.address')),
                Column::make('subtotal')->title(trans('site.subtotal')),
                Column::make('total')->title(trans('site.total')),
                Column::make('bill_number')->title(trans('site.bill_number')),
                Column::computed('city')->title(trans('site.city')),
                Column::computed('neighborhood')->title(trans('site.neighborhood')),
                //Column::computed('invoice')->title(trans('site.invouce')),
                Column::computed('invoice')
                    ->exportable(false)
                    ->printable(false)
                    ->width(120)
                    ->addClass('text-center')
                    ->title(trans('site.invoice')),
                Column::make('created_at')->title(trans('site.created_at')),
                Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->width(120)
                    ->addClass('text-center')
                    ->title(trans('site.action')),
            ];
        }else{
        return [
            Column::make('id')->title(trans('site.id')),
            Column::make('confirmed')->title(trans('site.confirmed')),
            //Column::make('delivery_cost')->title(trans('site.en.delivery_cost')),
            //Column::make('users.name')->title(trans('site.user')),
            [ 'data' => 'name', 'name' => 'users.name', 'title' => trans('site.username') ],
            //[ 'data' => 'address', 'name' => 'addresses.city', 'title' => 'address' ],
            //Column::make('address_id')->title(trans('site.address')),
            Column::make('subtotal')->title(trans('site.subtotal')),
            Column::make('total')->title(trans('site.total')),
            Column::make('bill_number')->title(trans('site.bill_number')),
            Column::computed('city')->title(trans('site.city')),
            Column::computed('neighborhood')->title(trans('site.neighborhood')),
            Column::make('created_at')->title(trans('site.created_at')),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center')
                ->title(trans('site.action')),
        ];
        }
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'OrderDatatables_' . date('YmdHis');
    }
}
