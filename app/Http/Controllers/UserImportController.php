<?php

namespace App\Http\Controllers;

use App\Notifications\UserCreatedNotification;
use App\Services\CSVService;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Gate;


class UserImportController extends Controller
{
    private array $requiredHeaders = ['email','name','role'];
    private array $roles = ['student', 'lecturer', 'teaching assistant'];

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
            return back()->with('error','Incorrect headers. Expected: email, name, role');
        }

        foreach($rows as $index => $row) {
            $rowNumber = $index + 2;

            // check that all the properties are present
            if(empty($row['email']) || empty($row['name']) || empty($row['role'])) {
                return back()->with('error', 'Missing required fields on row '.$rowNumber);
            }

            // validate that the email is in the correct form
            if(!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', 'Invalid email provided on row '.$rowNumber);
            }

            // check that the role matches one of the valid roles
            if(!in_array(strtolower($row['role']), $this->roles)) {
                return back()->with('error', 'Invalid role provided on row '.$rowNumber.'. Must be one of: Student, Lecturer, Teaching Assistant');
            }
        }

        foreach ($rows as $row) {
            // if the user already exists skip them
            if(User::where('email', $row['email'])->exists()) {
                continue;
            }

            // create a random 16 character temporary password
            $password = Str::random(16);

            $user = User::create([
                'email'=>$row['email'],
                'name'=>$row['name'],
                'password'=> $password,
            ]);

            $roleId = match(strtolower($row['role'])) {
                'student' => 1,
                'teaching assistant' => 2,
                'lecturer' => 3,
                default => 1,
            };

            // generate a unique token for the user
            $resetToken = Password::createToken($user);

            $user->roles()->attach($roleId);
            $user->notify(new UserCreatedNotification($resetToken));
        }
        return back()->with('success','Users imported successfully!');
    }
}
