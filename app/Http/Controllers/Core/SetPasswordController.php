<?php

namespace App\Http\Controllers;

use App\Models\Core\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
class SetPasswordController extends Controller
{
    public function set_password(int $id): void
    {
        $redirect = $_SERVER['HTTP_REFERER'];

        $this->id = $id;

        $plainPassword = Str::password(8, true, true, false, false);
        $hashedPassword = Hash::make($plainPassword);

        User::where('id', $this->id)->update(['password' => $hashedPassword, 'open_password' => $plainPassword]);

        if (isset($redirect)) {
            header('Location: ' . $redirect);
            exit();

        } else {
            header("Location: /admin/reference-books/category-material-tree");
            exit;
        }

    }
}
