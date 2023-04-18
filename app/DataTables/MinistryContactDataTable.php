<?php
/*
 * File name: TownshipDataTable.php
 */

namespace App\DataTables;

use App\Models\MinistryContact;
use App\Models\CustomField;
use App\Models\Post;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;

class MinistryContactDataTable extends DataTable
{
    /**
     * custom fields columns
     * @var array
     */
    public static $customFields = [];

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable = $dataTable
            ->editColumn('ministry_name', function ($m_contact) {
                return $m_contact->ministry_name;
            })
            ->editColumn('townships.tsh_name', function ($m_contact) {
                return getLinksColumnByRouteName([$m_contact->townships], 'townships.edit', 'id', 'tsh_name');
            })
            ->editColumn('phone', function ($m_contact) {
               return $m_contact->phone;
            })
            ->editColumn('address', function ($m_contact) {
               return $m_contact->address;
            })
            ->editColumn('updated_at', function ($m_contact) {
                return getDateColumn($m_contact, 'updated_at');
            })
            ->addColumn('action', 'admin.ministry_contact.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));
            // dd($dataTable);
        return $dataTable;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            [
                'data' => 'ministry_name',
                'title' => trans('lang.ministry_name'),

            ],
            [
                'data' => 'townships.tsh_name',
                'title' => trans('lang.township_plural'),

            ],
            [
                'data' => 'phone',
                'title' => trans('lang.user_phone_number'),

            ],
            [
                'data' => 'address',
                'title' => trans('lang.e_provider_addresses'),

            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.category_updated_at'),
                'searchable' => false,
            ]
        ];

        $hasCustomField = in_array(Category::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', Category::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.category_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }
        return $columns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param Township $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(MinistryContact $model)
    {
        return $model->newQuery()->with(["townships"])->orderBy("ministry_contacts.updated_at","desc")->select("ministry_contacts.*");;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true)
                ]
            ));
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = PDF::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename() . '.pdf');
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ministryContact_' . time();
    }
}
