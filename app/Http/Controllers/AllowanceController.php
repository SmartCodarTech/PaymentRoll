<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator; 
use App\Allowance;
use App\Civilian;


class AllowanceController extends Controller
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
        
         $allowances= DB::table('allowance')
        ->leftJoin('civilian', 'allowance.civilian_id', '=', 'civilian.id')
        ->select('allowance.*', 'civilian.lastname as civilian_lastname',
                            'civilian.firstname as civilian_firstname',
                            'civilian.picture as civilian_picture')
      
       ->paginate(5);
        //return view('system-mgmt/credit/index',  ['credit'=>Credit::paginate(5)];
       return view('system-mgmt/allowance/index', ['allowances' => $allowances]);
        //return view('employee.index', ['employees'=>Employee::paginate(5)]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
          $civilians = Civilian::all();
        return view('system-mgmt/allowance/create', ['civilians' => $civilians]);
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
         Allowance::create([

            'allowance_type' => $request['allowance_type'],
            'amount' => $request['amount'],
            'allowance_date' => $request['allowance_date'],
            'civilian_id'=>$request['civilian_id'],
            
        ]);

         
         

        return redirect()->intended('system-management/allowance');
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
        $allowances = Alowance::find($id);
        // Redirect to division list if updating division wasn't existed
        if ($credit == null || count($credit) == 0) {
            return redirect()->intended('/system-management/allowance');
        }

        return view('system-mgmt/allowance/edit', ['allowances' => $allowances]);
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
        $allowances= Alowance::findOrFail($id);
        $this->validateInput($request);
        $input = [
             'allowance_type' => $request['allowance_type'],
            'amount' => $request['amount'],
            'allowance_date' => $request['allowance_date'],
           
            'civilian_id' => $request['civilian_id']
        ];
        Allowance::where('id', $id)
            ->update($input);
        
        return redirect()->intended('system-management/allowance');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Alowance::where('id', $id)->delete();
         return redirect()->intended('system-management/allowance');
    }

    /**
     * Search division from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
         $constraints = [
            'employees.firstname' => $request['employees_firstname'],
            'employees.lastname' => $request['employees_lastname'],
            'employees.picture' => $request['employees_picture']
            ];
        $credits = $this->doSearchingQuery($constraints);
        $constraints['employees_firstname'] = $request['employees_lastname'];
        return view('system-mgmt/credit/index', ['credit' => $credit, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
       $query = DB::table('credit')
          ->leftJoin('employees', 'credit.employee_id', '=', 'employees.id')
           ->leftJoin('division', 'employees.division_id', '=', 'division.id')
         ->select('credit.*','employees.firstname as employees_firstname', 'employees.lastname as employees_lastname','employees.picture as employees_picture', 
                    'division.salary as division_salary','division.id as division_id');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {
            if ($constraint != null) {
                $query = $query->where($fields[$index], 'like', '%'.$constraint.'%');
            }

            $index++;
        }
        return $query->paginate(5);
    }
    private function validateInput($request) {
        $this->validate($request, [
            'allowance_type' => 'required|max:60',
            'amount' => 'required|max:60',
            'allowance_date' => 'required|max:60',
         
            'civilian_id' => 'required'
           
        
    ]);
    }
}
