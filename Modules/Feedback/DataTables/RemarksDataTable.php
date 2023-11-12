<?php

namespace Modules\Feedback\DataTables;

use Carbon\Carbon;
use Illuminate\Support\HtmlString;
use Modules\Feedback\Services\RemarkService;
use Modules\Feedback\Entities\Remark;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RemarksDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function __construct(RemarkService $remarkService)
    {
        $this->module_name = 'remarks';

        $this->remarkService = $remarkService;
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($data) {
                $module_name = $this->module_name;

                return view('backend.includes.action_column_remark', compact('module_name', 'data'));
            })
            ->editColumn('available', function ($data) {
                $module_name = $this->module_name;

                if($data->available)
                {
                    $availability = '<p class="text-white text-center bg-success rounded">YES</p>';
                }else{
                    $availability = '<p class="text-white text-center bg-danger rounded">NO</p>';
                }

                return $availability;
            })
            ->editColumn('category', function ($data) {
                $module_name = $this->module_name;
                if(isset($data->type)){

                    $cateogry_name = $data->type->name;
                }else{
                    $cateogry_name = "uncategorized";
                }
                return $cateogry_name;
            })
            ->editColumn('status', function ($data) {

                $text_modification = "";
                $the_status = $data->status;

                switch($the_status){
                    case 'Belum Dibaca':
                        $text_modification = "text-danger";       
                        break;
                    case 'Diproses':    
                        $text_modification = "text-warning";
                        break;
                    case 'Selesai':    
                        $text_modification = "text-success";
                        break;
                }

                return "<span class=".$text_modification.">".$the_status."</span>";
            })
            ->editColumn('photo', function ($data) {
                $module_name = $this->module_name;

                $pictureView = '<img src="'.asset($data->photo).'" class="user-profile-image img-fluid img-thumbnail" style="max-height:150px; max-width:100px;" />';

                return $pictureView;
            })
            ->editColumn('updated_at', function ($data) {
                $module_name = $this->module_name;

                $diff = Carbon::now()->diffInHours($data->updated_at);

                if ($diff < 25) {
                    return $data->updated_at->diffForHumans();
                } else {
                    return $data->updated_at->isoFormat('LLLL');
                }
            })
            ->editColumn('created_at', function ($data) {
                $module_name = $this->module_name;

                $formated_date = Carbon::parse($data->created_at)->format('d-m-Y, H:i:s');

                return $formated_date;
            })
            ->rawColumns(['name', 'status','action','photo','available']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Remark $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $user = auth()->user();
        $data = Remark::query();

        return $this->applyScopes($data);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $created_at = 1;
        return $this->builder()
                ->setTableId('remarks-table')
                ->columns($this->getColumns())
                ->minifiedAjax()
                ->dom(config('mk-datatables.mk-dom'))
                ->orderBy($created_at,'desc')
                ->buttons(
                    Button::make('export'),
                    Button::make('print'),
                    Button::make('reset')->className('rounded-right'),
                    Button::make('colvis')->text('Kolom')->className('m-2 rounded btn-info'),
                )->parameters([
                    'paging' => true,
                    'searching' => true,
                    'info' => true,
                    'responsive' => true,
                    'autoWidth' => false,
                    'searchDelay' => 350,
                ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->addClass('text-center'),
            Column::make('id')->hidden(),
            Column::make('remarker')->title(__("feedback::remarks.remarker")),
            Column::make('kelas')->title(__("feedback::remarks.kelas")),
            Column::make('remarker_type')->title(__("feedback::remarks.remarker_type")),
            Column::make('category')->title(__("feedback::remarks.category")),
            Column::make('remarker_email')->title(__("feedback::remarks.remarker_email"))->hidden(),
            Column::make('nomor_hp')->title(__("feedback::remarks.nomor_hp"))->hidden(),
            Column::make('alamat')->title(__("feedback::remarks.alamat"))->hidden(),
            Column::make('title')->title(__("feedback::remarks.title")),
            Column::make('status')->title(__("feedback::remarks.status")),
            Column::make('created_at'),
            Column::make('updated_at')->hidden(),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Remarks_' . date('YmdHis');
    }
}