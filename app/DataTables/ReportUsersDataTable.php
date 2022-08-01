<?php

namespace App\DataTables;

use App\Helpers\DTHelper;
use App\Models\ReportUser;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReportUsersDataTable extends DataTable
{


    private $crudName = 'users';

    private function getRoutes()
    {
        return [
            'update' => "dashboard.$this->crudName.edit",
            'show' => "dashboard.$this->crudName.show",
            'delete' => "dashboard.$this->crudName.destroy",
            'block' => "dashboard.$this->crudName.block",
        ];
    }

    private function getPermissions()
    {
        return [
            'update' => 'update_' . $this->crudName,
            'delete' => 'delete_' . $this->crudName,
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
            ->addColumn('action', function ($model) {
                $actions = '';

                $actions .= DTHelper::dtShowButton(route($this->getRoutes()['show'], $model->id), trans('site.show'), $this->getPermissions()['delete']);


                return $actions;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ReportUser $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model, Request $request)
    {

        if ($this->request()->has('start_date') && $this->request()->has('end_date')) {

            $start = \Carbon\Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date)->addDay();
            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
                $users = User::where('shop_id', Session::get('shop_id'))->whereBetween('created_at', [$start, $end]);
            }else{
                $users = User::whereBetween('created_at', [$start, $end]);
            }

            return $users->newQuery();

        }

        if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
            return $model->where('shop_id', Session::get('shop_id'))->orWhere('id', auth()->user()->id)->newQuery();
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
                $users = User::where('shop_id', Session::get('shop_id'))->whereBetween('created_at', [$start, $end]);
            }else{
                $users = User::whereBetween('created_at', [$start, $end]);
            }
            return $users->count();

        } else {
            if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {

                return User::where('shop_id', Session::get('shop_id'))->whereNotNull('shop_id')->orWhere('id', auth()->user()->id)->count();
            }else {
                return User::all()->count();
            }
        }

    }
    public function countmonth()
    {
        $dateS = Carbon::now()->startOfMonth()->subMonth(1);
        $dateE = Carbon::now()->startOfMonth();
        if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant')) {
            return User::where('shop_id', Session::get('shop_id'))->whereBetween('created_at', [$dateS, $dateE])->count();
        }else{
            return User::whereBetween('created_at', [$dateS, $dateE])->count();

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
            ->setTableId('reportusers-table')
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
            Column::make('name')->title(trans('site.name')),
            Column::make('type')->title(trans('site.type')),

            Column::make('email')->title(trans('site.email')),
            Column::make('phone')->title(trans('site.phone')),
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
        return 'ReportUsers_' . date('YmdHis');
    }
}
