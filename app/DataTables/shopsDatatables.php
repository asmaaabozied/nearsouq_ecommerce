<?php

namespace App\DataTables;

use App\Helpers\DTHelper;
use App\Models\Shop;
use App\Models\shopsDatatable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class shopsDatatables extends DataTable
{
    private $crudName = 'shops';

    private function getRoutes()
    {
        return [
            'update' => "dashboard.$this->crudName.edit",
            'delete' => "dashboard.$this->crudName.destroy",
            'block' => "dashboard.$this->crudName.block",
        ];
    }

    private function getPermissions()
    {
        return [
            'update' => 'update_' . $this->crudName,
            'delete' => 'delete_' . $this->crudName,
            'create' => 'create_' . $this->crudName
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
                return (!empty($model->created_at)) ? $model->created_at : '';
            })
            //   ->editColumn('image', function ($model) {
            //     return (!empty($model->image)) ? 'yes' : 'no';
            // })
                ->editColumn('image', function ($model) {
                return (!empty($model->Products()->count()>0)) ? $model->Products()->count() : trans('site.no');
            })
               ->editColumn('category_id', function ($model) {
                return (!empty($model->category)) ? $model->category->name : '';
            })
            ->editColumn('mall_id', function ($model) {
                return (!empty($model->mall)) ? $model->mall->name : '';
            })
            
             ->editColumn('active', function ($model) {
                return (!empty($model->active)) ? trans('site.active') : trans('site.block');
            })
            
            ->addColumn('action', function ($model) {
                $actions = '';
                $actionsdelete='';
                if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
         

  

                    $actions .= DTHelper::dtShowButton(route('dashboard.showbrances', $model->shop_id), trans('site.show'), $this->getPermissions()['update']);

                    $actions .= DTHelper::dtEditButton(route($this->getRoutes()['update'], $model->shop_id), trans('site.edit'), $this->getPermissions()['update']);
                    $actionsdelete .= DTHelper::dtDeleteButton(route($this->getRoutes()['delete'], $model->shop_id), trans('site.delete'), $this->getPermissions()['delete'],$model->id);
                    $actions .= DTHelper::dtBlockActivateButton(route($this->getRoutes()['block'], $model->shop_id), $model->published, $this->getPermissions()['update']);

                } else {
                    $actions .= DTHelper::dtShowButton(route('dashboard.showbrances', $model->id), trans('site.show'), $this->getPermissions()['update']);

                    $actions .= DTHelper::dtEditButton(route($this->getRoutes()['update'], $model->id), trans('site.edit'), $this->getPermissions()['update']);
                    $actionsdelete .= DTHelper::dtDeleteButton(route($this->getRoutes()['delete'], $model->id), trans('site.delete'), $this->getPermissions()['delete'],$model->id);
                    $actions .= DTHelper::dtBlockActivateButton(route($this->getRoutes()['block'], $model->id), $model->published, $this->getPermissions()['update']);


                }
            
                                                 return '<div class="dropdown">
                                                               <i class="far fa-file me-1 fa fa-1x"></i>
                                                                <div class="dropdown-content">'.$actions.'</div></div>'.$actionsdelete;

            });
    }

    /**
     * Get query source of dataTable.
     *                                                // <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> <div class="dropdown-content">

     * @param \App\Models\shopsDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Shop $model)
    {
        if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
            return auth()->user()->shops()->where('parent_id', '!=', null)->newQuery();

        } else {
            return $model->where('parent_id', '!=', null)->newQuery();

        }


    }

    public function count()
    {
        return Shop::all()->where('parent_id', '!=', null)->count();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('shopdatatables-table')
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
        if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {

            return [

                Column::make('shop_id')->title(trans('site.id')),

                   Column::make('mall_id')->title(trans('site.malls')),
                Column::make('phone')->title(trans('site.phone')),
         Column::make('commission')->title(trans('site.commission')),
         Column::make('longitude')->title(trans('site.longitude')),
                Column::make('latitude')->title(trans('site.latitude')),
                  Column::make('category_id')->title(trans('site.categories')),
            Column::make('image')->title(trans('site.products')),
                                         Column::make('active')->title(trans('site.status')),

                Column::make('created_at')->title(trans('site.created_at')),
                Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->width(60)->trans(trans('site.actions'))
                    ->addClass('text-center'),
            ];

        } else {
            return [

                Column::make('id')->title(trans('site.id')),
                   Column::make('mall_id')->title(trans('site.malls')),
                Column::make('phone')->title(trans('site.phone')),
                    Column::make('commission')->title(trans('site.commission')),
                Column::make('longitude')->title(trans('site.longitude')),
                Column::make('latitude')->title(trans('site.latitude')),
                 Column::make('category_id')->title(trans('site.categories')),

            Column::make('image')->title(trans('site.products')),
                                         Column::make('active')->title(trans('site.status')),

                Column::make('created_at')->title(trans('site.created_at')),
                Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->width(60)->trans(trans('site.actions'))
                    ->addClass('text-center'),
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
        return 'shopsDatatables_' . date('YmdHis');
    }
}
