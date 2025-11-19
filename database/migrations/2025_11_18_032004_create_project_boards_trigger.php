// database/migrations/xxxx_create_project_boards_trigger.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER after_project_insert
            AFTER INSERT ON projects
            FOR EACH ROW
            BEGIN
                INSERT INTO boards (project_id, board_name, position, created_at, updated_at)
                VALUES 
                    (NEW.id, "To Do", 1, NOW(), NOW()),
                    (NEW.id, "In Progress", 2, NOW(), NOW()),
                    (NEW.id, "Review", 3, NOW(), NOW()),
                    (NEW.id, "Done", 4, NOW(), NOW());
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_project_insert');
    }
};
