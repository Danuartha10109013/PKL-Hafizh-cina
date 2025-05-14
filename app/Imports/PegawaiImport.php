<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class PegawaiImport implements ToCollection, ToModel
{
    private $current = 0;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        // dd($collection);
    }

    public function model(array $row)
    {
        $this->current++;

        if ($this->current > 1) {
            // dd($row);
            $count = User::where('username', $row[0])->count();
            if (empty($count)) {
                $user = new User;
                $user->username = $row[0];
                $user->name = $row[1];
                $user->email = $row[2];
                $user->password = Hash::make($row[3]);
                $user->role = $row[4];
                $user->status = $row[5];
                $user->position = $row[6];
                $user->token = Str::random(60);
                $user->save();
            }
        }
    }
}
