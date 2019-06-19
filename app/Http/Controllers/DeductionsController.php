<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Deductions;
use App\Civilian;

class DeductionsController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $deductions = DB::table('deduct')
        ->leftJoin('civilian', 'deduct.civilian_id', '=', 'civilian.id')
        ->select('deduct.*', 'civilian.lastname as civilian_lastname',
                            'civilian.firstname as civilian_firstname',
                            'civilian.picture as civilian_picture')
        
       ->paginate(5);

       return view('system-mgmt/deduction/index', ['deductions' => $deductions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $civilian = Civilian::all();
        return view('system-mgmt/deduction/create', [
         'civilian' => $civilian]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateInput($request);
         Deductions::create([

            'deduction_type' => $request['deduction_type'],
            'amount' => $request['amount'],
            'organization' => $request['organization'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'purpose' => $request['purpose'],
            'civilian_id' => $request['civilian_id']
        ]);

        return redirect()->intended('system-management/deduction');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deduction = Deductions::find($id);
        // Redirect to division list if updating division wasn't existed
        if ($deduction == null || count($deduction) == 0) {
            return redirect()->intended('/system-management/deduction');
        }

        return view('system-mgmt/deduction/edit', ['deduction' => $deduction]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $deduction= Deductions::findOrFail($id);
        $this->validateInput($request);
        $input = [
            'deduction_type' => $request['deduction_type'],
            'amount' => $request['amount'],
            'organization' => $request['organization'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'purpose' => $request['purpose'],
            'civilian_id' => $request['civilian_id']
        ];
        Deduction::where('id', $id)
            ->update($input);
        
        return redirect()->intended('system-management/deduction');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Deduction::where('id', $id)->delete();
         return redirect()->intended('system-management/deduction');
    }

    /**
     * Search division from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    
    public function search(Request $request) {
        $constraints = [
             'deduction_type' => $request['deduction_type'],
            'amount' => $request['amount'],
            'organization' => $request['organization'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'purpose' => $request['purpose'],
            'civilian_id' => $request['civilian_id']
            ];

       $deduction= $this->doSearchingQuery($constraints);
       return view('system-mgmt/deduction/index', ['deduction' => $deduction, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = Deduction::query();
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {
            if ($constraint != null) {
                $query = $query->where( $fields[$index], 'like', '%'.$constraint.'%');
            }

            $index++;
        }
        return $query->paginate(10);
    }

    private function validateInput($request) {
        $this->validate($request, [
            'deduction' => 'required|max:60',
            'amount' => 'required|max:60',
            'organization' => 'required|max:60',
            'start_date' => 'required|max:60',
            'end_date' => 'required|max:60',
            'purpose'=>'required|max:60',
            'civilian_id' => 'required|max:60',

    ]);
    }
}

