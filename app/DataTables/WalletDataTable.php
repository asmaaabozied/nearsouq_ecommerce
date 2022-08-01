<?php

namespace App\DataTables;

use App\Helpers\DTHelper;
use App\Models\Wallet;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class WalletDataTable extends DataTable
{
    private $crudName = 'wallets';
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
            //'create' => 'create_'.$this->crudName
        ];
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at', function ($model) {
                return (!empty($model->created_at)) ? $model->created_at->diffForHumans() : '';
            })
            ->editColumn('user_id', function ($model) {
                return (!empty($model->user->name)) ? $model->user->name : '';
            })

            ->addColumn('image', function ($user) {
                $url=asset("uploads/shops/wallets/$user->image");
                return "<img alt='' src='{$url}' width='100' class='img-rounded'/>";
            })
            ->addColumn('action', function ($model) {
                $actions = '';

                $actions .= DTHelper::dtShowButton(url('dashboard/wallets/'.$model->id.'?id='.$model->id), trans('site.edit'), $this->getPermissions()['update']);



                // $actions .= DTHelper::dtShowButton(route($this->getRoutes()['show'], $model->id ,? id=$model->id), trans('site.edit'), $this->getPermissions()['update']);
                // $actions .= DTHelper::dtDeleteButton(route($this->getRoutes()['delete'], $model->id), trans('site.delete'), $this->getPermissions()['delete'],$model->id);
                $actions .= DTHelper::dtPopButton($model->id, trans('site.edit'), $this->getPermissions()['update']);

                return $actions;
            })->rawColumns(['image','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Wallet $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Wallet $model)
    {
        return $model->newQuery();
    }
    public function count()
    {
        return Wallet::all()->count();
    }
    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('wallet-table')
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
        

            Column::make('id')->title(trans('site.id')),
            Column::make('user_id')->title(trans('site.user')),
            Column::make('balance')->title(trans('site.balance')),
            //Column::make('image')->title(trans('site.image')),
            Column::make('created_at')->title(trans('site.created_at')),
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
        return 'Wallet_' . date('YmdHis');
    }
}
