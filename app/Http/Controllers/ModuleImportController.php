<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CSVService;
use App\Models\User;
use App\Models\Module;
use App\Notifications\ModuleEnrollmentNotification;
use Illuminate\Support\Facades\Gate;


class ModuleImportController extends Controller
{
    private array $requiredHeaders = ['module_code','module_name','coordinator_email','year_group','year_taught'];

    public function __construct(private CSVService $csvService){}

    public function import(Request $request) {
        Gate::authorize('access-admin');
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt|max:10240'
        ]);

        $rows = $this->csvService->parseCSV($request->file('csv'));

        if(empty($rows)) {
            return back()-with('error', 'Empty CSV file provided');
        }

        if(!$this->csvService->validateHeaders($rows, $this->requiredHeaders)) {
            return back()->with('error','Incorrect headers. Expected: module_code, module_name, coordinator_email, year_group, year_taught');
        }

        foreach($rows as $index => $row) {
            $rowNumber = $index + 2;

            // check that all the properties are present
            if(empty($row['module_code']) || empty($row['module_name']) 
                || empty($row['coordinator_email']) || empty($row['year_group']) || empty($row['year_taught'])) {
                return back()->with('error', 'Missing required fields on row '.$rowNumber);
            }

            // validate that the lecturers email is in the correct form
            if(!filter_var($row['coordinator_email'], FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', 'Invalid email provided on row '.$rowNumber);
            }

            // validate that the coordinator exists and is a lecturer
            $coordinator = User::where('email', $row['coordinator_email'])->first();

            if(!$coordinator) {
                return back()->with('error', 'No lecturer found with the email provided on row'.$rowNumber);
            }

            if(!$coordinator->hasRole('Lecturer')) {
                return back()->with('error', 'Provided user is not a Lecturer, on row '.$rowNumber);
            }

            // validate the year group is between 0 and 4
            if(!is_numeric($row['year_group']) || $row['year_group'] < 0 || $row['year_group'] > 4) {
                return back()->with('error', 'Year group must be a number between 0 and 4 on row '.$rowNumber);
            }

            // validate the year taught is a number
            if(!is_numeric($row['year_taught'])) {
                return back()->with('error', 'Year taught must be a valid number on row '.$rowNumber);
            }

            // validate the module doesn't exist
            if(Module::where('module_code', $row['module_code'])->where('year_taught', $row['year_taught'])->exists()) {
                return back()->with('error', 'The provided module already exists, on '.$rowNumber);
            }
        }
        foreach($rows as $row) {
            $coordinator = User::where('email', $row['coordinator_email'])->first();

            $module = Module::create([
                'module_code'=>strtoupper($row['module_code']),
                'module_name'=>$row['module_name'],
                'module_leader_id'=>$coordinator->id,
                'year_group'=>$row['year_group'],
                'year_taught'=>$row['year_taught'],
                // must be added later on
                'bg_image_url'=>null,
            ]);
            // enrol the coordinator and email them
            $module->users()->attach($coordinator->id);
            $coordinator->notify(new ModuleEnrollmentNotification($module));
        }
        return back()->with('success', 'Modules successfully imported!');
    }
}
