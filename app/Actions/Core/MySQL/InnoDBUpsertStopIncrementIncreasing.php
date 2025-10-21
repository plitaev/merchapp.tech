<?php
namespace App\Actions\Core\MySQL;

use App\Models\Core\TelegramScheduleDeleteMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InnoDBUpsertStopIncrementIncreasing
{
    public function handle(Model $model, $column = 'id') {
        $table = app($model::class)->getTable();

        $count = $model::count();
        if ($count > 0) {
            DB::unprepared("SET @NEW_AI = (SELECT MAX(`".$column."`)+1 FROM `".$table."`);");
            DB::unprepared("SET @ALTER_SQL = CONCAT('ALTER TABLE `".$table."` AUTO_INCREMENT =', @NEW_AI);");
            DB::unprepared("PREPARE NEWSQL FROM @ALTER_SQL;");
            DB::unprepared("EXECUTE NEWSQL;");
        }

    }
}
