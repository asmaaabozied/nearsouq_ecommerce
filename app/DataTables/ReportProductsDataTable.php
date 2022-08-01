<?php

namespace App\DataTables;

use App\Helpers\DTHelper;
use App\Models\Product;
use App\Models\OrderDetail;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReportProductsDataTable extends DataTable
{
    private $crudName = 'products';

    private function getRoutes() {
        return [
            'update' => "dashboard.$this->crudName.edit",
            'show' => "dashboard.$this->crudName.show",
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
            ->editColumn('created_at', function ($model) {
                return (!empty($model->created_at)) ? $model->created_at->diffForHumans() : '';
            })

            ->editColumn('shop_id', function ($model) {
                return (!empty($model->Shop)) ? $model->Shop->name : '';
            })
            ->addColumn('action', function ($model) {
                $actions = '';


                $actions .= DTHelper::dtShowButton(route($this->getRoutes()['show'], $model->id), trans('site.show'), $this->getPermissions()['delete']);

                return $actions;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ReportProduct $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model)
    {
        
        if ($this->request()->has('status')){
        
              if ($this->request()->get('status')==1){
            
 
 
   $productdetailIds = OrderDetail::distinct()->pluck('product_id');
   
  $products= Product::whereIn('id',$productdetailIds);


               return $products->newQuery();
            
        
            
        }else if($this->request()->get('status')==0){
           $productdetails= OrderDetail::whereIn('quantity', [1,2,3,4,5])->pluck('product_id');
           
            $products= Product::whereIn('id',$productdetails);


               return $products ->newQuery();
            
        }
        }
        if ($this->request()->has('start_date') && $this->request()->has('end_date')) {

            $start = \Carbon\Carbon::parse($this->request()->get('start_date'));
            $end = Carbon::parse($this->request()->get('end_date'))->addDay();
            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
              $products= Product::where('shop_id',Session::get('shop_id'))->whereBetween('created_at', [$start, $end]);
               return $products ->newQuery();

            }else{
                $products= Product::whereBetween('created_at', [$start, $end]);
                return $products ->newQuery();


            }
        }

        if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
            return Product::where('shop_id',Session::get('shop_id'))->newQuery();
        } else {
            return $model->newQuery();

        }
    }


    public function count()
    {
          if($this->request()->has('status')){
        if($this->request()->get('status')==0){
           $productdetails= OrderDetail::whereIn('quantity', [1,2,3,4,5])->pluck('product_id');
           
            $products= Product::whereIn('id',$productdetails);


               return $products ->count();
            
        }else{
            
                 
            
 
 
   $productdetailIds = OrderDetail::distinct()->pluck('product_id');
   
  $products= Product::whereIn('id',$productdetailIds);


               return $products ->count();
            
        
     
            
        }
          }
  
        if ($this->request()->has('start_date') && $this->request()->has('end_date')) {

            $start = \Carbon\Carbon::parse($this->request()->get('start_date'));
            $end = Carbon::parse($this->request()->get('end_date'))->addDay();
            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
                $products = Product::where('shop_id',Session::get('shop_id'))->whereBetween('created_at', [$start, $end]);
            }else{
                $products = Product::whereBetween('created_at', [$start, $end]);
            }
            return $products->count();

        } else {
            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {

                return Product::where('shop_id',Session::get('shop_id'))->count();
            }else {
                return Product::all()->count();
            }
        }

    }
    public function countmonth()
    {
        $dateS = Carbon::now()->startOfMonth()->subMonth(1);
        $dateE = Carbon::now()->startOfMonth()->addDay();
        if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
            return Product::where('shop_id',Session::get('shop_id'))->whereBetween('created_at', [$dateS, $dateE])->count();
        }else{
            return Product::whereBetween('created_at', [$dateS, $dateE])->count();

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
                    ->setTableId('reportproducts-table')
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
            Column::make('price')->title(trans('site.price')),
            Column::make('discount_price')->title(trans('site.discount_price')),
            Column::make('shop_id')->title(trans('site.shops')),

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
        return 'ReportProducts_' . date('YmdHis');
    }
}
