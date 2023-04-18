<?php
/*
 * File name: TownshipDataTable.php
 */

namespace App\DataTables;

use App\Models\EmergancyContact;
use App\Models\CustomField;
use App\Models\Post;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;

class EmergancyContactDataTable extends DataTable
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
            ->editColumn('contact_name', function ($emg_contact) {
                return $emg_contact->contact_name;
            })
            ->editColumn('townships.tsh_name', function ($emg_contact) {
                return getLinksColumnByRouteName([$emg_contact->townships], 'townships.edit', 'id', 'tsh_name');
            })
            ->editColumn('phone', function ($emg_contact) {
               return $emg_contact->phone;
            })
            ->editColumn('address', function ($emg_contact) {
               return $emg_contact->address;
            })
            ->editColumn('updated_at', function ($emg_contact) {
                return getDateColumn($emg_contact, 'updated_at');
            })
            ->addColumn('action', 'admin.emergancy_contact.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));
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
                'data' => 'contact_name',
                'title' => trans('lang.contact_name'),

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

        $hasCustomField = in_array(EmergancyContact::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', EmergancyContact::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.emg_contact' . $field->name),
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
    public function query(EmergancyContact $model)
    {
        return $model->newQuery()->with(["townships"])->orderBy("emergancy_contacts.updated_at","desc")->select("emergancy_contacts.*");
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
        return 'emergancyContact_' . time();
    }
}
