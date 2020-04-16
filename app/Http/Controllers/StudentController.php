<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $students=Student::all();
       return view('welcome',\compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [

            'name' => 'required',
            'roll' => 'required',
            'class' => 'required',

            // Image Dimensions Validation
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|dimensions:min_width=100,min_height=50,max_width=200,max_height=100',
            'image' => 'required',

        ]);

        //if Validator not fail...
        if (!$validator->fails()) {
            $student = new Student();
            $student->name = $request->name;
            $student->roll = $request->roll;
            $student->class = $request->class;

            //if request file image then store the image...
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images/student', 'public');
                $student->image = $path;
            }

            //if data save then give us "Ok" response
            if ($student->save()) {
                return response()->json([
                    'success' => 'OK',
                    'data' => $student
                ]);
            }
        }
        //other wise give faild response
        return response()->json([
            'success' => 'FALD',
            'errors' => $validator->errors()->all()
        ]);


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $student=Student::find($id);
      if ($student){
          return response()->json([
              'success' => 'OK',
              'data' => $student
          ]);
      }
      else{
          return response()->json([
              'success' => 'fail',

          ]);
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
