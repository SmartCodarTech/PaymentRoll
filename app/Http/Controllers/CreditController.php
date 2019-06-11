<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Credit;
use App\Employee;
use App\Division;
class CreditController extends Controller
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
        
         $credit = DB::table('credit')
        ->leftJoin('employees', 'credit.employee_id', '=', 'employees.id')
        ->leftJoin('division', 'employees.division_id', '=', 'division.id')
        ->select('credit.*', 'employees.lastname as employees_lastname',
                            'employees.firstname as employees_firstname',
                            'employees.picture as employees_picture', 
            'division.name as division_name','division.code as division_code', 'division.salary as division_salary','division.id as division_id')
      
       ->paginate(5);
    
       return view('system-mgmt/credit/index', ['credit' => $credit]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
          $employees = Employee::all();
        return view('system-mgmt/credit/create', [
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
         Credit::create([
            'comment' => $request['comment'],
            'amount' => $request['amount'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'credit_purpose' => $request['credit_purpose'],
            'employee_id' => $request['employee_id']
        ]);

        return redirect()->intended('system-management/credit');
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
        $credit = Credit::find($id);
        // Redirect to division list if updating division wasn't existed
        if ($credit == null || count($credit) == 0) {
            return redirect()->intended('/system-management/credit');
        }

        return view('system-mgmt/credit/edit', ['credit' => $credit]);
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
        $credit= Credit::findOrFail($id);
        $this->validateInput($request);
        $input = [
             'comment' => $request['comment'],
            'amount' => $request['amount'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'credit_purpose' => $request['credit_purpose'],
            'employee_id' => $request['employee_id']
        ];
        Credit::where('id', $id)
            ->update($input);
        
        return redirect()->intended('system-management/credit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Credit::where('id', $id)->delete();
         return redirect()->intended('system-management/credit');
    }

    /**
     * Search division from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $constraints = [
             'comment' => $request['comment'],
            'amount' => $request['amount'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'credit_purpose' => $request['credit_purpose'],
            'employee_id' => $request['employee_id']
            ];

       $divisions = $this->doSearchingQuery($constraints);
       return view('system-mgmt/credit/index', ['credits' => $credits, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = Credit::query();
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
            'comment' => 'required|max:60',
            'amount' => 'required|max:60',
            'start_date' => 'required|max:60',
            'end_date' => 'required',
            'credit_purpose'=>'required',
            'employee_id' => 'required'
           
        
    ]);
    }
}

