[1mdiff --git a/database/factories/LogFactory.php b/database/factories/LogFactory.php[m
[1mindex dd7cc48..3969372 100644[m
[1m--- a/database/factories/LogFactory.php[m
[1m+++ b/database/factories/LogFactory.php[m
[36m@@ -2,9 +2,9 @@[m
 [m
 namespace Database\Factories;[m
 [m
[32m+[m[32muse Carbon\Carbon;[m
 use App\Models\Log;[m
 use Illuminate\Database\Eloquent\Factories\Factory;[m
[31m-use Carbon\Carbon;[m
 [m
 class LogFactory extends Factory[m
 {[m
[1mdiff --git a/tests/Unit/Models/LogModelTest.php b/tests/Unit/Models/LogModelTest.php[m
[1mindex b6daa44..0c80e10 100644[m
[1m--- a/tests/Unit/Models/LogModelTest.php[m
[1m+++ b/tests/Unit/Models/LogModelTest.php[m
[36m@@ -2,10 +2,10 @@[m
 [m
 namespace Tests\Unit\Models;[m
 [m
[32m+[m[32muse Carbon\Carbon;[m
 use App\Models\Log;[m
 use Tests\TestCase;[m
 use Illuminate\Foundation\Testing\RefreshDatabase;[m
[31m-use Carbon\Carbon;[m
 [m
 class LogModelTest extends TestCase[m
 {[m
