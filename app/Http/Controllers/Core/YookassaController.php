<?php
namespace App\Http\Controllers\Core;
use Illuminate\Support\Facades\DB;

class YookassaController
{
    public function callback() {
        DB::table('pddata')->insertOrIgnore(['ddata' => 'ok']);

        $source = file_get_contents('php://input');
        DB::table('pddata')->insertOrIgnore(['ddata' => $source]);
    }
}
