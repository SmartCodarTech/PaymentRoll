<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use App\Civilian;
use App\Bank;
use App\Department;


class CivilianEmployeesController extends Controller
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
        $civilians = DB::table('civilian')
        ->leftJoin('department', 'civilian.department_id', '=', 'department.id')
        ->leftJoin('bank', 'civilian.bank_id', '=', 'bank.id')
        ->select('civilian.*', 'department.name as department_name', 'department.id as department_id','bank.name as bank_name')
        ->paginate(5);

        return view('civilian-mgmt/index', ['civilians' => $civilians]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
        $departments = Department::all();
        $banks = Bank::all();
        return view('civilian-mgmt/create', [
        'departments' => $departments,'banks' => $banks]);
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
        // Upload image
        $path = $request->file('picture')->store('avatars');
        $keys = ['lastname', 'firstname', 'gender','service_id','email', 'type',
       'salary','date_hired', 'department_id', 'bank_id'];
        $input = $this->createQueryInput($keys, $request);
        $input['picture'] = $path;
        Civilian::create($input);

        return redirect()->intended('/civilian-management');
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
        $civilian = Civilian::find($id);
        // Redirect to state list if updating state wasn't existed
        if ($civilian == null || count($civilian) == 0) {
            return redirect()->intended('/civilain-management');
        }
      
        $departments = Department::all();
        $bank = Bank::all();
        return view('civilian-mgmt/edit', ['civilian' => $civilian, 
        'departments' => $departments]);
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
        $civilian = Civilian::findOrFail($id);
        $this->validateInput($request);
        // Upload image
      $keys = ['lastname', 'firstname', 'gender','service_id','email', 'type',
       'salary','date_hired', 'department_id', 'bank_id'];
        $input = $this->createQueryInput($keys, $request);
        if ($request->file('picture')) {
            $path = $request->file('picture')->store('avatars');
            $input['picture'] = $path;
        }

        Civilian::where('id', $id)
            ->update($input);

        return redirect()->intended('/civilian-management');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         Civilian::where('id', $id)->delete();
         return redirect()->intended('/civilian-management');
    }

    /**
     * Search state from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $constraints = [
            'firstname' => $request['firstname'],
            'department.name' => $request['department_name']
            ];
        $civilians = $this->doSearchingQuery($constraints);
        $constraints['department_name'] = $request['department_name'];
        return view('civilian-mgmt/index', ['civilian' => $civilians, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = DB::table('civilian')
        ->leftJoin('department', 'civilian.department_id', '=', 'department.id')
        ->leftJoin('bank', 'civilian.bank_id', '=', 'bank.id')
    
        ->select('employees.firstname as employee_name', 'employees.*','department.name as department_name', 'department.id as department_id', 'division.name as division_name', 'division.id as division_id');
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

     /**
     * Load image resource.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function load($name) {
         $path = storage_path().'/app/avatars/'.$name;
        if (file_exists($path)) {
            return Response::download($path);
        }
    }

    private function validateInput($request) {
        $this->validate($request, [
            'lastname' => 'required|max:60',
            'firstname' => 'required|max:60',
            'service_id' => 'required|max:60',
            'email' => 'required|max:60',
            'type' => 'required',
            'gender'=>'required',
            'salary'=>'required',
            'date_hired' => 'required',
            'department_id' => 'required',
            'bank_id' => 'required'
        ]);
    }

    private function createQueryInput($keys, $request) {
        $queryInput = [];
        for($i = 0; $i < sizeof($keys); $i++) {
            $key = $keys[$i];
            $queryInput[$key] = $request[$key];
        }

        return $queryInput;
    }
}

