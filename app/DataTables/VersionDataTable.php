<?php

namespace App\DataTables;

use App\Helpers\DTHelper;
use App\Models\Version;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class VersionDataTable extends DataTable
{

    private $crudName = 'versions';

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
                return (!empty($model->created_at)) ? $model->created_at->diffForHumans() : '';
            })
            
               ->editColumn('status', function ($model) {
                // return (!empty($model->status)) ? $model->status : '';
                
                
                if($model->status==1){
                   $active= trans('site.active'); 
                    
                }else{
                    
                   $active=trans('site.block');   
                }
                
                return $active;
            })
            
            ->addColumn('action', function ($model) {
                $actions = '';

                $actions .= DTHelper::dtEditButton(route($this->getRoutes()['update'], $model->id), trans('site.edit'), $this->getPermissions()['update']);
                    $actions .= DTHelper::dtStatusActivateButton(route($this->getRoutes()['block'], $model->id), $model->status, $this->getPermissions()['update']);


                $actions .= DTHelper::dtDeleteButton(route($this->getRoutes()['delete'], $model->id), trans('site.delete'), $this->getPermissions()['delete'],$model->id);


                return $actions;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Version $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Version $model)
    {
        return $model->newQuery();
    }

    public function count()
    {
        return Version::all()->count();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('version-table')
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
          //  Column::make('id'),
            Column::make('version_no')->title(trans('site.version_no')),
            Column::make('created_at')->title(trans('site.created_at')),
            Column::make('status')->title(trans('site.status')),
            Column::make('type')->title(trans('site.type')),
            Column::make('os')->title(trans('site.os')),
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
        return 'Version_' . date('YmdHis');
    }
}
