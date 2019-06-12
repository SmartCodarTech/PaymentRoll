<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use App\Employee;
use App\Division;
use App\Premium;

class PremiumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $premiums = DB::table('premium')
        ->leftJoin('employees', 'premium.employee_id', '=', 'employees.id')
        ->leftJoin('division', 'employees.division_id', '=', 'division.id')
        ->select('premium.*', 'employees.lastname as employees_lastname',
                            'employees.firstname as employees_firstname',
                            'employees.picture as employees_picture', 
            'division.name as division_name','division.code as division_code', 'division.salary as division_salary','division.id as division_id')
      
       ->paginate(5);
       return view('system-mgmt/premium/index', ['premiums' => $premiums]);
     //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::all();
        return view('system-mgmt/premium/create', ['employees' => $employees]);    
        
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
         Premium::create([

            'premium_type' => $request['premium_type'],
            'amount' => $request['amount'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'premium_purpose' => $request['premium_purpose'],
            'employee_id' => $request['employee_id']
        ]);

        return redirect()->intended('system-management/premium');
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
        return view('premium.edit', ['premium'=>Premium::findOrFail($id)]);
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
        $premiums = Premium::findOrFail($id);
        
        $this->validate($request,[
            'name' => 'required'
        ]);
        
        $department->name = $request->name;
        $department->slug = str_slug($request->name);
        $department->save();
        
        Session::flash('success', 'Premium updated');
        return redirect()->route('premium.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Premium::where('id', $id)->delete();
         return redirect()->intended('system-management/premium');
    }
        public function search(Request $request) {
         $constraints = [
            'employees.firstname' => $request['employees_firstname'],
            'employees.lastname' => $request['employees_lastname'],
            'employees.picture' => $request['employees_picture']
            ];
        $credit = $this->doSearchingQuery($constraints);
        $constraints['employees_firstname'] = $request['employees_lastname'];
        return view('system-mgmt/credit/index', ['credit' => $credit, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
       $query = DB::table('premium')
          ->leftJoin('employees', 'premium.employee_id', '=', 'employees.id')
           ->leftJoin('division', 'employees.division_id', '=', 'division.id')
         ->select('premium.*','employees.firstname as employees_firstname', 'employees.lastname as employees_lastname','employees.picture as employees_picture', 
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
            'premium_type' => 'required|max:60',
            'amount' => 'required|max:60',
            'start_date' => 'required|max:60',
            'end_date' => 'required',
            'premium_purpose'=>'required',
            'employee_id' => 'required'
           
        
    ]);
    }
}
