<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Employee;
use App\Penalty;
use App\Division;
class PenaltyController extends Controller
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
        $penaltys = DB::table('penalty')
        ->leftJoin('employees', 'penalty.employee_id', '=', 'employees.id')
        ->leftJoin('division', 'employees.division_id', '=', 'division.id')
        ->select('penalty.*', 'employees.lastname as employees_lastname',
                            'employees.firstname as employees_firstname',
                            'employees.picture','division.salary as division_salary','division.id as division_id')
        
       ->paginate(5);
    
        return view('system-mgmt/penalty/index', ['penaltys' => $penaltys]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $employees = Employee::all();
        return view('system-mgmt/penalty/create', [
         'employees' => $employees]);
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
         Penalty::create([

            'penalty_type' => $request['penalty_type'],
            'amount_division' => $request['amount_division'],
            'comment' => $request['comment'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'employee_id' => $request['employee_id']
        ]);

        return redirect()->intended('system-management/penalty');
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
        $penalty = Penalty::find($id);
        // Redirect to division list if updating division wasn't existed
        if ($penalty == null || count($penalty) == 0) {
            return redirect()->intended('/system-management/penalty');
        }

        return view('system-mgmt/penalty/edit', ['penalty' => $penalty]);
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
        $penalty= Penalty::findOrFail($id);
        $this->validateInput($request);
        $input = [
            'penalty_type' => $request['penalty_type'],
            'amount_division' => $request['amount_division'],
            'comment' => $request['comment'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'employee_id' => $request['employee_id']
            
        ];
        Penalty::where('id', $id)
            ->update($input);
        
        return redirect()->intended('system-management/penalty');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Penalty::where('id', $id)->delete();
         return redirect()->intended('system-management/penalty');
    }

    /**
     * Search division from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $constraints = [
            'penalty_type' => $request['penalty_type'],
            'amount_division' => $request['amount_division'],
            'comment' => $request['comment'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'employee_id' => $request['employee_id']
            ];

       $divisions = $this->doSearchingQuery($constraints);
       return view('system-mgmt/penalty/index', ['penalty' => $penalty, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = Penalty::query();
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
            'penalty_type' => 'required|max:60',
            'amount_division' => 'required|max:60',
            'comment' => 'required|max:60',
            'start_date' => 'required|max:60',
            'end_date' => 'required',
            'employee_id' => 'required'
    ]);
    }
}

