<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use App\premuim;
use App\Employee;

class PremuimController extends Controller
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

         $premuims = DB::table('premuim')
        ->leftJoin('employees', 'premuim.employee_id', '=', 'employees.id')
        ->leftJoin('division', 'employees.division_id', '=', 'division.id')
        ->select('premuim.*', 'employees.lastname as employees_lastname',
                            'employees.firstname as employees_firstname',
                            'employees.picture as employees_picture', 
            'division.name as division_name','division.code as division_code', 'division.salary as division_salary','division.id as division_id')
        
       ->paginate(5);

        return view('system-mgmt/premium/index', ['premium' => $premuims]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::all();
        return view('system-mgmt/premium/create', [
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
         Premuim::create([
            'premium_type' => $request['premium_type'],
            'amount' => $request['amount'],
            'premium_purpose' => $request['premium_purpose'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
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
        $premuim = Premuim::find($id);
        // Redirect to country list if updating country wasn't existed
        if ($premuim == null || count($premuim) == 0) {
            return redirect()->intended('/system-management/premium');
        }

        return view('system-mgmt/premium/edit', ['premuim' => $premuim]);
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
        $premuim = Premuim::findOrFail($id);
        $input = [
            'premium_type' => $request['premium_type'],
            'amount' => $request['amount'],
            'premium_purpose' => $request['premium_purpose'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'employee_id' => $request['employee_id']
        ];
        $this->validate($request, [
        'premium_type' => 'required|max:60',
        'amount' => 'required|max:60',
        'premium_purpose' => 'required|max:60',
        'start_date' => 'required|max:60',
        'end_date' => 'required|max:60'
        ]);
        Premuim::where('id', $id)
            ->update($input);
        
        return redirect()->intended('system-management/premium');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Premuim::where('id', $id)->delete();
         return redirect()->intended('system-management/premium');
    }

    /**
     * Search country from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $constraints = [
            'premium_type' => $request['premium_type'],
            'amount' => $request['amount']
            ];

       $countries = $this->doSearchingQuery($constraints);
       return view('system-mgmt/premium/index', ['premuim' => $premuim, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = premuim::query();
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {
            if ($constraint != null) {
                $query = $query->where( $fields[$index], 'like', '%'.$constraint.'%');
            }

            $index++;
        }
        return $query->paginate(5);
    }
    private function validateInput($request) {
        $this->validate($request, [
        'premium_type' => 'required|max:60|unique:premium_type',
        'amount' => 'required|max:60|unique:amount',
          'premium_purpose' => 'required|max:60|unique:premium_purpose',
        'start_date' => 'required|max:60|unique:start_date',
          'end_date' => 'required|max:60|unique:end_date'
       
    ]);
    }
}
