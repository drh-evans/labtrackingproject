<?php

namespace App\Http\Controllers;

use App\Notifications\ModuleEnrollmentNotification;
use App\Services\CSVService;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\Gate;


class EnrolmentImportController extends Controller
{
    private array $requiredHeaders = ['email','module_code','year_taught'];

    public function __construct(private CSVService $csvService) {
    }

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
            return back()->with('error','Incorrect headers. Expected: email, module_code, year_taught');
        }

        foreach($rows as $index => $row) {
            $rowNumber = $index + 2;

            // check that all the properties are present
            if(empty($row['module_code']) || empty($row['email']) || empty($row['year_taught'])) {
                return back()->with('error', 'Missing required fields on row '.$rowNumber);
            }

            // validate that the email is in the correct form
            if(!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', 'Invalid email provided on row '.$rowNumber);
            }

            if(!User::where('email',$row['email'])->exists()) {
                return back()->with('error', 'Email provided not found, on row '.$rowNumber);
            }

            // validate the year taught is a number
            if(!is_numeric($row['year_taught'])) {
                return back()->with('error', 'Year taught must be a valid number on row '.$rowNumber);
            }

            // validate the module doesn't exist
            if(!Module::where('module_code', $row['module_code'])->where('year_taught', $row['year_taught'])->exists()) {
                return back()->with('error', 'The module provided does not exist, on '.$rowNumber);
            }
        }

        foreach($rows as $row) {
            $user = User::where('email', $row['email'])->first();
            $module = Module::where('module_code', $row['module_code'])
                      ->where('year_taught', $row['year_taught'])
                      ->first();
            // check if the user is already enrolled in the module, if they are skip
            if($module->users()->where('user_id',$user->id)->exists()) {
                continue;
            }
            $module->users()->attach($user->id);
            $user->notify(new ModuleEnrollmentNotification($module));
        }
        return back()->with('success', 'Users enrolled successfully!');
    }
}
