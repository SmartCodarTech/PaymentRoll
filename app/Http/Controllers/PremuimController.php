<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'name' => $request['name'],
            'country_code' => $request['country_code']
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
        $country = Country::findOrFail($id);
        $input = [
            'name' => $request['name'],
            'country_code' => $request['country_code']
        ];
        $this->validate($request, [
        'name' => 'required|max:60'
        ]);
        Country::where('id', $id)
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
        Country::where('id', $id)->delete();
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
            'name' => $request['name'],
            'country_code' => $request['country_code']
            ];

       $countries = $this->doSearchingQuery($constraints);
       return view('system-mgmt/premium/index', ['countries' => $countries, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = country::query();
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
        'name' => 'required|max:60|unique:country',
        'country_code' => 'required|max:3|unique:country'
    ]);
    }
}
