<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Data;
use App\Models\DataLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)

    {
        if ($request->ajax()) {


            $data = Data::orderBy('priority', 'DESC')->orderBy('id', 'DESC')
                ->select([
                    'id',
                    'name',
                    'unique_id',
                    'requested_device',
                    'traking_number',
                    'attachment',
                    'priority',
                ]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    if ($row->priority == "2") {
                        $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-urgent'>$row->name</span></a>  <span class='badge bg-danger'>Urgent</span>";
                    } elseif ($row->priority == "1") {
                        $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-high'>$row->name</a> <span class='badge bg-warning'>High</span>";
                    } else {
                        $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-low'>$row->name</span></a> <span class='badge bg-success'>Low</span>";
                    }
                    return $name;
                })
                ->editColumn('traking_number', function ($row) {
                    if ($row->traking_number) {
                        $traking_number = '<a href="https://www.fedex.com/fedextrack/?trknbr=' . $row->traking_number . '" target="_blank">
                        ' . $row->traking_number . '
                        </a>';
                    } else {
                        $traking_number = '';
                    }
                    return $traking_number;
                })
                ->editColumn('attachment', function ($row) {
                    if ($row->attachment) {
                        $attachment = '<a href="' . asset('assets/img/entries/' . $row->attachment) . '" target="_blank">
                        <img src="' . asset('assets/img/entries/' . $row->attachment) . '" height="40" width="40">
                    </a>';
                    } else {
                        $attachment = 'N/A';
                    }
                    return $attachment;
                })
                ->addColumn('action', function ($row) {
                    // $viewLogButton = '<a data-bs-toggle="modal" data-bs-target="#view-log-modal" onclick="view_data_log(' . $row->id . ')" href="javascript:;"><i class="fas fa-history text-secondary"></i></a>';
                    $viewLogButton = '<a href="'.route('admin.get.data.log',$row->id).'" target="_blank"><i class="fas fa-history text-secondary"></i></a>';
                    $viewButton = '<a data-bs-toggle="modal" data-bs-target="#view-modal" onclick="view_data(' . $row->id . ')" href="javascript:;" class="mx-3"><i class="fas fa-eye text-secondary"></i></a>';
                    $editButton = '<a href="' . route('admin.edit.data', $row->id) . '" data-bs-toggle="tooltip" data-bs-original-title="Edit product"><i class="fas fa-user-edit text-secondary"></i></a>';
                    $deleteButton = '<a onclick="delete_data(' . $row->id . ')" href="javascript:;" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Delete product"><i class="fas fa-trash text-secondary"></i></a>';
                    return $viewLogButton . $viewButton . $editButton . $deleteButton;
                })
                ->rawColumns(['name', 'traking_number', 'attachment', 'action'])
                ->make(true);
        }
        return view('admin.all-data');
    }

    //duplicate entry
    public function duplicate_entry(Request $request){

        if ($request->ajax()) {
            $data = Data::select(
                'data.id',
                'data.name',
                'data.unique_id',
                'data.requested_device',
                'data.traking_number',
                'data.attachment',
                'data.priority'
            )
            ->join(DB::raw('(SELECT state, unique_id FROM data GROUP BY state, unique_id HAVING COUNT(*) > 1) d'), function ($join) {
                $join->on('data.state', '=', 'd.state')
                    ->on('data.unique_id', '=', 'd.unique_id');
            })
            ->orderBy('data.unique_id', 'ASC');

            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                if ($row->priority == "2") {
                    $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-urgent'>$row->name</span></a>  <span class='badge bg-danger'>Urgent</span>";
                } elseif ($row->priority == "1") {
                    $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-high'>$row->name</a> <span class='badge bg-warning'>High</span>";
                } else {
                    $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-low'>$row->name</span></a> <span class='badge bg-success'>Low</span>";
                }
                return $name;
            })
            ->editColumn('traking_number', function ($row) {
                if ($row->traking_number) {
                    $traking_number = '<a href="https://www.fedex.com/fedextrack/?trknbr=' . $row->traking_number . '" target="_blank">
                    ' . $row->traking_number . '
                    </a>';
                } else {
                    $traking_number = '';
                }
                return $traking_number;
            })
            ->editColumn('attachment', function ($row) {
                if ($row->attachment) {
                    $attachment = '<a href="' . asset('assets/img/entries/' . $row->attachment) . '" target="_blank">
                    <img src="' . asset('assets/img/entries/' . $row->attachment) . '" height="40" width="40">
                </a>';
                } else {
                    $attachment = 'N/A';
                }
                return $attachment;
            })
            ->addColumn('action', function ($row) {
                // $viewLogButton = '<a data-bs-toggle="modal" data-bs-target="#view-log-modal" onclick="view_data_log(' . $row->id . ')" href="javascript:;"><i class="fas fa-history text-secondary"></i></a>';
                $viewLogButton = '<a href="'.route('admin.get.data.log',$row->id).'" target="_blank"><i class="fas fa-history text-secondary"></i></a>';
                $viewButton = '<a data-bs-toggle="modal" data-bs-target="#view-modal" onclick="view_data(' . $row->id . ')" href="javascript:;" class="mx-3"><i class="fas fa-eye text-secondary"></i></a>';
                $editButton = '<a href="' . route('admin.edit.data', $row->id) . '" data-bs-toggle="tooltip" data-bs-original-title="Edit product"><i class="fas fa-user-edit text-secondary"></i></a>';
                $deleteButton = '<a onclick="delete_data(' . $row->id . ')" href="javascript:;" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Delete product"><i class="fas fa-trash text-secondary"></i></a>';
                return $viewLogButton . $viewButton . $editButton . $deleteButton;
            })
            ->rawColumns(['name', 'traking_number', 'attachment', 'action'])
            ->make(true);
        }
        return view('admin.duplicate-data');

    }

    public function inprogess_entry(Request $request)
    {
        if ($request->ajax()) {


            $data = Data::where('traking_number','=',null)->orderBy('priority', 'DESC')->orderBy('id', 'DESC')
                ->select([
                    'id',
                    'name',
                    'unique_id',
                    'requested_device',
                    'traking_number',
                    'attachment',
                    'priority',
                ]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    if ($row->priority == "2") {
                        $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-urgent'>$row->name</span></a>  <span class='badge bg-danger'>Urgent</span>";
                    } elseif ($row->priority == "1") {
                        $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-high'>$row->name</span></a> <span class='badge bg-warning'>High</span>";
                    } else {
                        $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-low'>$row->name</span></a> <span class='badge bg-success'>Low</span>";
                    }
                    return $name;
                })
                ->editColumn('traking_number', function ($row) {
                    if ($row->traking_number) {
                        $traking_number = '<a href="https://www.fedex.com/fedextrack/?trknbr=' . $row->traking_number . '" target="_blank">
                        ' . $row->traking_number . '
                        </a>';
                    } else {
                        $traking_number = '';
                    }
                    return $traking_number;
                })
                ->editColumn('attachment', function ($row) {
                    if ($row->attachment) {
                        $attachment = '<a href="' . asset('assets/img/entries/' . $row->attachment) . '" target="_blank">
                        <img src="' . asset('assets/img/entries/' . $row->attachment) . '" height="40" width="40">
                    </a>';
                    } else {
                        $attachment = 'N/A';
                    }
                    return $attachment;
                })
                ->addColumn('action', function ($row) {
                    // $viewLogButton = '<a data-bs-toggle="modal" data-bs-target="#view-log-modal" onclick="view_data_log(' . $row->id . ')" href="javascript:;"><i class="fas fa-history text-secondary"></i></a>';
                    $viewLogButton = '<a href="'.route('admin.get.data.log',$row->id).'" target="_blank"><i class="fas fa-history text-secondary"></i></a>';
                    $viewButton = '<a data-bs-toggle="modal" data-bs-target="#view-modal" onclick="view_data(' . $row->id . ')" href="javascript:;" class="mx-3"><i class="fas fa-eye text-secondary"></i></a>';
                    $editButton = '<a href="' . route('admin.edit.data', $row->id) . '" data-bs-toggle="tooltip" data-bs-original-title="Edit product"><i class="fas fa-user-edit text-secondary"></i></a>';
                    $deleteButton = '<a onclick="delete_data(' . $row->id . ')" href="javascript:;" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Delete product"><i class="fas fa-trash text-secondary"></i></a>';
                    return $viewLogButton . $viewButton . $editButton . $deleteButton;
                })
                ->rawColumns(['name', 'traking_number', 'attachment', 'action'])
                ->make(true);
        }
        return view('admin.inprogress-data');
    }
    public function complete_entry(Request $request)
    {
        if ($request->ajax()) {


            $data = Data::where(function ($query) {
                $query->where('traking_number', '!=', null)
                    ->orWhere('current_status', 'Resolved');
            })
        ->orderBy('priority', 'DESC')->orderBy('id', 'DESC')
                ->select([
                    'id',
                    'name',
                    'unique_id',
                    'requested_device',
                    'traking_number',
                    'attachment',
                    'priority',
                ]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    if ($row->priority == "2") {
                        $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-urgent'>$row->name</span></a>  <span class='badge bg-danger'>Urgent</span>";
                    } elseif ($row->priority == "1") {
                        $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-high'>$row->name</span></a> <span class='badge bg-warning'>High</span>";
                    } else {
                        $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-low'>$row->name</span></a> <span class='badge bg-success'>Low</span>";
                    }
                    return $name;
                })
                ->editColumn('traking_number', function ($row) {
                    if ($row->traking_number) {
                        $traking_number = '<a href="https://www.fedex.com/fedextrack/?trknbr=' . $row->traking_number . '" target="_blank">
                        ' . $row->traking_number . '
                        </a>';
                    } else {
                        $traking_number = '';
                    }
                    return $traking_number;
                })
                ->editColumn('attachment', function ($row) {
                    if ($row->attachment) {
                        $attachment = '<a href="' . asset('assets/img/entries/' . $row->attachment) . '" target="_blank">
                        <img src="' . asset('assets/img/entries/' . $row->attachment) . '" height="40" width="40">
                    </a>';
                    } else {
                        $attachment = 'N/A';
                    }
                    return $attachment;
                })
                ->addColumn('action', function ($row) {
                    // $viewLogButton = '<a data-bs-toggle="modal" data-bs-target="#view-log-modal" onclick="view_data_log(' . $row->id . ')" href="javascript:;"><i class="fas fa-history text-secondary"></i></a>';
                    $viewLogButton = '<a href="'.route('admin.get.data.log',$row->id).'" target="_blank"><i class="fas fa-history text-secondary"></i></a>';
                    $viewButton = '<a data-bs-toggle="modal" data-bs-target="#view-modal" onclick="view_data(' . $row->id . ')" href="javascript:;" class="mx-3"><i class="fas fa-eye text-secondary"></i></a>';
                    $editButton = '<a href="' . route('admin.edit.data', $row->id) . '" data-bs-toggle="tooltip" data-bs-original-title="Edit product"><i class="fas fa-user-edit text-secondary"></i></a>';
                    $deleteButton = '<a onclick="delete_data(' . $row->id . ')" href="javascript:;" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Delete product"><i class="fas fa-trash text-secondary"></i></a>';
                    return $viewLogButton . $viewButton . $editButton . $deleteButton;
                })
                ->rawColumns(['name', 'traking_number', 'attachment', 'action'])
                ->make(true);
        }
        return view('admin.complete-data');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create-data');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'name' => 'required',
            'attachment' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($request->file('attachment')) {
            $imagePath = $request->file('attachment');
            $imageName = $imagePath->getClientOriginalExtension();
            $imageName = time() . Str::random(8) . "." . $imageName;
            $request->file('attachment')->move(public_path('/assets/img/entries'), $imageName);
        } else {
            $imageName = "";
        }

        $check_unique_id = Data::where("unique_id", strip_tags($request->unique_id))->where('state', strip_tags($request->state))->first();
        if (!$check_unique_id) {
            Data::create([
                "name" => strip_tags($request->name),
                "address" => strip_tags($request->address),
                "state" => strip_tags($request->state),
                "unique_id" => strip_tags($request->unique_id),
                "device" => strip_tags($request->device),
                "unlock_status" => strip_tags($request->unlock_status),
                "notes" => strip_tags($request->notes),
                "traking_number" => strip_tags($request->traking_number) ? strip_tags($request->traking_number): null,
                "attachment" => $imageName,
                "priority" => strip_tags($request->priority),
                "requested_device" => strip_tags($request->requested_device),
                "current_status" => strip_tags($request->current_status),
                "music_count" => $request->music_count,
                "received_via" => strip_tags($request->received_via),
            ]);

            echo json_encode(array("message" => "data successfully created"));
            exit;
        }
        echo json_encode(array("message" => "Inmate Id must be unique to state."));
    }

    // get single data
    public function view_data(Request $request)
    {
        $entry = Data::where("id", $request->id)->first();

        $data =  '<p><strong>Created Date :</strong> ' . date('m-d-Y', strtotime($entry->created_at)) . '
        </p>
        <p><strong>Name :</strong> ' . $entry->name . '</p>
        <p><strong>Address :</strong> ' . $entry->address . '</p>
        <p><strong>State Incarcerated :</strong> ' . $entry->state . '</p>
        <p><strong>Inmate ID :</strong> ' . $entry->unique_id . '</p>
        <p><strong>Device :</strong> ' . $entry->device . '</p>
        <p><strong>Unlock Status :</strong> ' . $entry->unlock_status . '
        </p>';
        $prority = $entry->priority == 0 ? 'Low' : ($entry->priority == 1 ? 'High' : ($entry->priority == 2 ? 'Urgent' : ''));
        $data .= '<p><strong>Priority :</strong> ' . $prority . '
        </p>
        <p><strong>Request For :</strong> ' . $entry->requested_device . '</p>
        <p><strong>Current Status :</strong> ' . $entry->current_status . '</p>
        <p><strong>Music Count :</strong> ' . $entry->music_count . '</p>
        <p><strong>Received Via :</strong> ' . $entry->received_via . '</p>
        <p><strong>Tracking Number :</strong>
             ' . $entry->traking_number . '</p>
        <p><strong>Note :</strong> ' . $entry->notes . '</p>';

        echo json_encode(array("data" => $data, "name" => $entry->name));
    }

    /**
     * Display the specified resource.
     */
    public function filter_data(Request $request)
    {
        if ($request->ajax()) {

            $data = Data::orderBy('priority', 'DESC')->orderBy('id', 'DESC');

            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $month = $request->input('month');
            $type = $request->input('type');

            if ($type == 'date') {
                $data = $data->whereBetween('created_at', [$start_date, $end_date]);
            } elseif ($type == 'month') {
                $data = $data->whereMonth('created_at', $month);
            }

            $data =  $data->select([
                'id',
                'name',
                'unique_id',
                'requested_device',
                'traking_number',
                'attachment',
                'priority',
            ]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    if ($row->priority == "2") {
                        $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-urgent'>$row->name</span></a>  <span class='badge bg-danger'>Urgent</span>";
                    } elseif ($row->priority == "1") {
                        $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-high'>$row->name</span></a> <span class='badge bg-warning'>High</span>";
                    } else {
                        $name = "<a  data-bs-toggle='modal' data-bs-target='#view-modal' onclick='view_data(`$row->id`)' href='javascript:;'><span class='priority-low'>$row->name</span></a> <span class='badge bg-success'>Low</span>";
                    }
                    return $name;
                })
                ->editColumn('traking_number', function ($row) {
                    if ($row->traking_number) {
                        $traking_number = '<a href="https://www.fedex.com/fedextrack/?trknbr=' . $row->traking_number . '" target="_blank">
                        ' . $row->traking_number . '
                        </a>';
                    } else {
                        $traking_number = '';
                    }
                    return $traking_number;
                })
                ->editColumn('attachment', function ($row) {
                    if ($row->attachment) {
                        $attachment = '<a href="' . asset('assets/img/entries/' . $row->attachment) . '" target="_blank">
                        <img src="' . asset('assets/img/entries/' . $row->attachment) . '" height="40" width="40">
                    </a>';
                    } else {
                        $attachment = 'N/A';
                    }
                    return $attachment;
                })
                ->addColumn('action', function ($row) {
                    $viewButton = '<a data-bs-toggle="modal" data-bs-target="#view-modal" onclick="view_data(' . $row->id . ')" href="javascript:;" class="mx-3"><i class="fas fa-eye text-secondary"></i></a>';
                    $editButton = '<a href="' . route('admin.edit.data', $row->id) . '" data-bs-toggle="tooltip" data-bs-original-title="Edit product"><i class="fas fa-user-edit text-secondary"></i></a>';
                    $deleteButton = '<a onclick="delete_data(' . $row->id . ')" href="javascript:;" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Delete product"><i class="fas fa-trash text-secondary"></i></a>';
                    return $viewButton . $editButton . $deleteButton;
                })
                ->rawColumns(['name', 'traking_number', 'attachment', 'action'])
                ->make(true);
        }
        return view('admin.filter-data');
    }
    public function search_data(Request $request)
    {
        $entries = Data::where([
            ['unique_id', $request->unique_id],
            ['unique_id', '!=', '']
        ])
        ->orWhere('name','=', $request->name)
        ->orderBy('priority', 'DESC')->orderBy('id', 'DESC')->get();

        $data = '<table class="table table-flush" id="data-list">
        <thead class="thead-light">
            <tr>
                <th>Name</th>
                <th>Inmate ID</th>
                <th>Request For</th>
                <th>Tracking Number search</th>
                <th>Attachment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($entries as $entry) {
            $data  .= '<tr>';
            if ($entry->priority == "2") {
                $data .= '<td class="text-sm"><a href="javascript:;" data-bs-toggle="modal" class="mx-3" data-bs-target="#modal-' . $entry->id . '"><span class="priority-urgent">' . $entry->name . '</span></a> <span class="badge bg-danger">Urgent</span></td>';
            } elseif ($entry->priority == "1") {
                $data .= '<td class="text-sm"><a href="javascript:;" data-bs-toggle="modal" class="mx-3" data-bs-target="#modal-' . $entry->id . '"><span class="priority-high">' . $entry->name . '</span></a> <span class="badge bg-warning">High</span></td>';
            } else {
                $data .= '<td class="text-sm"><a href="javascript:;" data-bs-toggle="modal" class="mx-3" data-bs-target="#modal-' . $entry->id . '"><span class="priority-low">' . $entry->name . '</span></a> <span class="badge bg-success">Low</span></td>';
            }



            $data  .= '<td class="text-sm inmate">' . $entry->unique_id . '</td>
                    <td class="text-sm">' . $entry->requested_device . '</td>
                    <td class="text-sm"><a href="https://www.fedex.com/fedextrack/?trknbr=' . $entry->traking_number . '" target="_blank">' . $entry->traking_number . '</a></td>';

            $data .= '<td class="text-sm">';
            if ($entry->attachment) {
                $data  .= '<a  href="' . asset('assets/img/entries/' . $entry->attachment) . '" target="_blank">
                        <img src="' . asset('assets/img/entries/' . $entry->attachment) . '"
                            height="40" width="40">
                        </a>
                        <div class = "link" hidden>' . asset('assets/img/entries/' . $entry->attachment) . '</div>
                        ';
            } else {
                $data  .= 'N/A';
            }

            $data .= '</td><td class="text-sm">
                        <a href="javascript:;" data-bs-toggle="modal" class="mx-3"
                            data-bs-target="#modal-' . $entry->id . '">
                            <i class="fas fa-eye text-secondary"></i>
                        </a>';
            if ($entry->current_status == 'Awaiting On Physical Address') {
                $data .= '<button href="javascript:; "
                        class="mt-2 btn btn-icon btn-3 btn-outline-primary  btn-sm" type="button"
                        onclick="create_address_form(`' . $entry->id . '`,`' . $entry->address . '`)">
                        <span class="btn-inner--icon"><i class="fas fa-plus text-primary"></i></span>
                      <span class="btn-inner--text">Add Address</span>
                    </button>';
            }
            $data .= '
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="modal-' . $entry->id . '" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    ' . $entry->name . '</h5>
                                <button type="button" class="btn btn-outline-dark"
                                    data-bs-dismiss="modal" aria-label="Close"><i
                                        class="fas fa-times"></i></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Created Date :</strong> ' . date('m-d-Y', strtotime($entry->created_at)) . '
                                </p>
                                <p><strong>Name :</strong> ' . $entry->name . '</p>
                                <p><strong>Address :</strong> ' . $entry->address . '</p>
                                <p><strong>State Incarcerated :</strong> ' . $entry->state . '</p>
                                <p><strong>Inmate ID :</strong> ' . $entry->unique_id . '</p>
                                <p><strong>Device :</strong> ' . $entry->device . '</p>
                                <p><strong>Unlock Status :</strong> ' . $entry->unlock_status . '
                                </p>';
                                $prority = $entry->priority == 0 ? 'Low' : ($entry->priority == 1 ? 'High' : ($entry->priority == 2 ? 'Urgent' : ''));
                                $data .= '<p><strong>Priority :</strong> ' . $prority . '
                                </p>
                                <p><strong>Request For :</strong> ' . $entry->requested_device . '</p>
                                <p><strong>Current Status :</strong> ' . $entry->current_status . '</p>
                                <p><strong>Music Count :</strong> ' . $entry->music_count . '</p>
                                <p><strong>Received Via :</strong> ' . $entry->received_via . '</p>
                                <p><strong>Tracking Number :</strong>' . $entry->traking_number . '</p>
                                <p><strong>Note :</strong> ' . $entry->notes . '</p>
                            </div>
                        </div>
                    </div>
                </div>';
        }
        $data .= '
            </tbody>

        </table>
        <!-- Modal -->
                <div class="modal fade" id="address_modal" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="address_modal_head"> </h5>
                                <button type="button" class="btn btn-outline-dark"
                                    data-bs-dismiss="modal" aria-label="Close"><i
                                        class="fas fa-times"></i></button>
                            </div>
                            <div class="modal-body" id="address_modal_body" >
                            <form id="address-form" class="form-control">
                            <label class="form-label">Address *</label>
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" class="form-control" id="id" name="id" placeholder="Enter Physical Address" required>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Enter Physical Address" required>
                            <button type="button" id="add-button" onclick="add_address()" class="mt-2 btn btn-primary">Submit</button>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
        ';
        $data .= '
        <script src="' . asset('assets') . '/js/magnific.js"></script>';

        echo json_encode(array("data" => $data));
    }
    /**
     * Display the specified resource.
     */
    public function add_address(Request $request)
    {
        $data = Data::where("id", $request->id)->first();

        if ($data->address != $request->address) {
            DataLog::create([
                "data_id" => $data->id,
                "date" => date('Y-m-d'),
                "property" => "Address",
                "previous" => $data->address,
                "new" => $request->address,
                "created_at" => Carbon::now()
            ]);
        }

        $data->address = $request->address;
        $data->update();
        echo json_encode(array("message" => "address added"));
    }
    /**
     * Display the specified resource.
     */
    public function show(Data $data)
    {
        $noteLog = DataLog::where('data_id',$data->id)->where('property','Notes')->orderBy('id','DESC')->get([
            'id',
            'new',
            'created_at',
        ]);
        return view('admin.edit-data', compact(['data','noteLog']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Data $data)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Data $data)
    {
        $field = $request->validate([
            'name' => 'required',
            'attachment' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->file('attachment')) {
            $imagePath = $request->file('attachment');
            $imageName = $imagePath->getClientOriginalExtension();
            $imageName = time() . Str::random(8) . "." . $imageName;
            $request->file('attachment')->move(public_path('/assets/img/entries'), $imageName);
            File::delete(public_path("assets/img/entries/$data->attachment"));
        } else {
            $imageName = $data->attachment;
        }
        $check_unique_id = Data::where('id', '!=', $data->id)->where("unique_id", strip_tags($request->unique_id))->where('state', strip_tags($request->state))->first();
        if (!$check_unique_id) {

            $this->createLog($data, $request);
            $data->update([
                "name" => strip_tags($request->name),
                "address" => strip_tags($request->address),
                "state" => strip_tags($request->state),
                "unique_id" => strip_tags($request->unique_id),
                "device" => strip_tags($request->device),
                "unlock_status" => strip_tags($request->unlock_status),
                "notes" => strip_tags($request->notes),
                "traking_number" => strip_tags($request->traking_number) ? strip_tags($request->traking_number) : null,
                "attachment" => $imageName,
                "priority" => strip_tags($request->priority),
                "requested_device" => strip_tags($request->requested_device),
                "current_status" => strip_tags($request->current_status),
                "music_count" => $request->music_count,
                "received_via" => strip_tags($request->received_via),
            ]);
            echo json_encode(array("message" => "data successfully created"));
            exit;
        }

        echo json_encode(array("message" => "Inmate Id must be unique to state."));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function createLog($data, $request)
    {
        $log = array();
        if ($data->notes != $request->notes) {
            $log[] = ["data_id" => $data->id, "date" => date('Y-m-d'), "property" => "Notes", "previous" => $data->notes, "new" => $request->notes, "created_at" => Carbon::now()];
        }
        if ($data->current_status != $request->current_status) {
            $log[] = ["data_id" => $data->id, "date" => date('Y-m-d'), "property" => "Current Status", "previous" => $data->current_status, "new" => $request->current_status, "created_at" => Carbon::now()];
        }
        if ($data->unlock_status != $request->unlock_status) {
            $log[] = ["data_id" => $data->id, "date" => date('Y-m-d'), "property" => "Unlock Status", "previous" => $data->unlock_status, "new" => $request->unlock_status, "created_at" => Carbon::now()];
        }
        if ($data->unique_id != $request->unique_id) {
            $log[] = ["data_id" => $data->id, "date" => date('Y-m-d'), "property" => "Inmate Id", "previous" => $data->unique_id, "new" => $request->unique_id, "created_at" => Carbon::now()];
        }
        if ($data->traking_number != $request->traking_number) {
            $log[] = ["data_id" => $data->id, "date" => date('Y-m-d'), "property" => "Traking Number", "previous" => $data->traking_number, "new" => $request->traking_number, "created_at" => Carbon::now()];
        }
        if ($data->address != $request->address) {
            $log[] = ["data_id" => $data->id, "date" => date('Y-m-d'), "property" => "Address", "previous" => $data->address, "new" => $request->address, "created_at" => Carbon::now()];
        }
        if ($data->requested_device != $request->requested_device) {
            $log[] = ["data_id" => $data->id, "date" => date('Y-m-d'), "property" => "Request For", "previous" => $data->requested_device, "new" => $request->requested_device, "created_at" => Carbon::now()];
        }
        if ($data->received_via != $request->received_via) {
            $log[] = ["data_id" => $data->id, "date" => date('Y-m-d'), "property" => "Received Via", "previous" => $data->received_via, "new" => $request->received_via, "created_at" => Carbon::now()];
        }

        DataLog::insert($log);
    }

    public function validate_unique_id(Request $request)
    {
        $data = Data::where("state",$request->state)->where('unique_id',$request->unique_id);
        if($request->id){
            $data = $data->where("id","!=",$request->id);
        }
        $data= $data->count();

        $response = $data == 0 ? response(array("message" => "Valid Inmate Id"), 200) : response(array("message" => "Invalid Inmate Id"), 401);
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $data = Data::find($request->id);

        if ($data) {
            if ($data->attachment) {
                File::delete(public_path("assets/img/entries/$data->attachment"));
            }
            $data->delete();
        }
        echo json_encode(array("message" => "user successfully deleted"));
    }
}
