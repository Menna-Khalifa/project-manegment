<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\BrandsController;
use App\Http\Controllers\Dashboard\BrandsUnitController;
use App\Http\Controllers\Dashboard\CompressorTypeController;
use App\Http\Controllers\Dashboard\DashboardAmerController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EquipmentsController;
use App\Http\Controllers\Dashboard\GroupController;
use App\Http\Controllers\Dashboard\InvoiceController;
use App\Http\Controllers\Dashboard\InvoicesAmerController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\ProjectAmerController;
use App\Http\Controllers\Dashboard\ProjectCapacityController;
use App\Http\Controllers\Dashboard\ProjectController;
use App\Http\Controllers\Dashboard\ProjectEquipmentController;
use App\Http\Controllers\Dashboard\ProjectInvoicesController;
use App\Http\Controllers\Dashboard\ProjectItemController;
use App\Http\Controllers\Dashboard\ProjectModelController;
use App\Http\Controllers\Dashboard\ProjectTeamController;
use App\Http\Controllers\Dashboard\ProjectTypeController;
use App\Http\Controllers\Dashboard\ProjectVoltController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\SectionItemsController;
use App\Http\Controllers\Dashboard\SectionsController;
use App\Http\Controllers\Dashboard\StoresController;
use App\Http\Controllers\Dashboard\TeamsController;
use App\Http\Controllers\Dashboard\UserController;
use App\Models\ProjectModel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;







/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('import_lists', function () {
    try {


        // Illuminate\Support\Facades\DB::table('brands')->delete();
        // Illuminate\Support\Facades\DB::table('project_types')->delete();
        // Illuminate\Support\Facades\DB::table('project_capacities')->delete();
        // Illuminate\Support\Facades\DB::table('project_volts')->delete();

        // مسار ملف الإكسيل - غيره حسب مكان الملف عندك
        $filePath = public_path('lists_data_americana.xlsx');

        // قراءة ملف الإكسيل
        $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();


        // إزالة الصف الأول (العناوين)
        array_shift($rows);

        Illuminate\Support\Facades\DB::beginTransaction();

        // مصفوفات لتخزين القيم الفريدة
        $brandStores = [];
        $brandUnits = [];
        $projectTypes = [];
        $capacities = [];
        $volts = [];

        // جمع كل القيم الفريدة
        foreach ($rows as $row) {
            // العمود الثالث: Brand Store
            if (!empty($row[2]) && !in_array($row[2], $brandStores)) {
                $brandStores[] = $row[2];
            }

            // العمود الرابع: Type of unit
            if (!empty($row[3]) && !in_array($row[3], $projectTypes)) {
                $projectTypes[] = $row[3];
            }

            // العمود الخامس: AC Capacity
            if (!empty($row[4]) && !in_array($row[4], $capacities)) {
                $capacities[] = $row[4];
            }

            // العمود السادس: Volt
            if (!empty($row[5]) && !in_array($row[5], $volts)) {
                $volts[] = $row[5];
            }

            // العمود السابع: AC Brand
            if (!empty($row[6]) && !in_array($row[6], $brandUnits)) {
                $brandUnits[] = $row[6];
            }
        }

        // حفظ Brand Stores
        foreach ($brandStores as $brand) {
            Illuminate\Support\Facades\DB::table('brands')->insertOrIgnore([
                'name' => $brand,
                'description' => null,
                'type' => 'store',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // حفظ Brand Units
        foreach ($brandUnits as $brand) {
            Illuminate\Support\Facades\DB::table('brands')->insertOrIgnore([
                'name' => $brand,
                'description' => null,
                'type' => 'unit',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // حفظ Project Types
        foreach ($projectTypes as $type) {
            Illuminate\Support\Facades\DB::table('project_types')->insertOrIgnore([
                'name' => $type,
                'description' => null,
                'type' => 'project',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // حفظ Capacities
        foreach ($capacities as $capacity) {
            Illuminate\Support\Facades\DB::table('project_capacities')->insertOrIgnore([
                'name' => $capacity,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // حفظ Volts
        foreach ($volts as $volt) {
            Illuminate\Support\Facades\DB::table('project_volts')->insertOrIgnore([
                'value' => $volt,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        Illuminate\Support\Facades\DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'تم استيراد البيانات بنجاح',
            'stats' => [
                'brand_stores' => count($brandStores),
                'brand_units' => count($brandUnits),
                'project_types' => count($projectTypes),
                'capacities' => count($capacities),
                'volts' => count($volts)
            ]
        ]);
    } catch (\Exception $e) {
        Illuminate\Support\Facades\DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('import_lists2', function () {
    try {

        // Illuminate\Support\Facades\DB::table('project_types')->delete();
        // Illuminate\Support\Facades\DB::table('project_models')->delete();

        // مسار ملف الإكسيل - غيره حسب مكان الملف عندك
        $filePath = public_path('lists2_data_americana.xlsx');

        // قراءة ملف الإكسيل
        $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();


        // إزالة الصف الأول (العناوين)
        array_shift($rows);

        Illuminate\Support\Facades\DB::beginTransaction();

        // مصفوفات لتخزين القيم الفريدة
        $projectTypes = [];
        $projectModels = [];

        // جمع كل القيم الفريدة
        foreach ($rows as $row) {

            // العمود الرابع: Type of unit
            if (!empty($row[0]) && !in_array($row[0], $projectTypes)) {
                $projectTypes[] = $row[0];
            }

            // العمود السابع: AC Brand
            if (!empty($row[1]) && !in_array($row[1], $projectModels)) {
                $projectModels[] = $row[1];
            }
        }

        // حفظ Project Types
        foreach ($projectTypes as $type) {
            Illuminate\Support\Facades\DB::table('project_types')->insertOrIgnore([
                'name' => $type,
                'description' => null,
                'type' => 'maintenance',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // جلب كل الـ IDs من project_types اللي نوعها maintenance
        $projectTypeIds = Illuminate\Support\Facades\DB::table('project_types')
            ->where('type', 'maintenance')
            ->pluck('id')
            ->toArray();

        // حفظ Project Models
        foreach ($projectModels as $model) {
            // اختيار project_type_id عشوائي من الـ IDs الموجودة
            $randomProjectTypeId = $projectTypeIds[array_rand($projectTypeIds)];

            Illuminate\Support\Facades\DB::table('project_models')->insert([
                'project_type_id' => $randomProjectTypeId,
                'name' => $model,
                'description' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }


        Illuminate\Support\Facades\DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'تم استيراد البيانات بنجاح',
            'stats' => [
                'project_types' => count($projectTypes),
                'project_models' => count($projectModels),
            ]
        ]);
    } catch (\Exception $e) {
        Illuminate\Support\Facades\DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('import_stores', function () {
    try {
        // مسار ملف الإكسيل
        $filePath = public_path('stores_data_americana.xlsx');

        // قراءة ملف الإكسيل
        $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // إزالة الصف الأول (العناوين)
        array_shift($rows);

        Illuminate\Support\Facades\DB::beginTransaction();

        // جلب كل الـ brands من نوع store مع أسمائها
        $brands = Illuminate\Support\Facades\DB::table('brands')
            ->where('type', 'store')
            ->pluck('id', 'name')
            ->toArray();

        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            try {
                // تخطي الصفوف الفارغة
                if (empty($row[0]) && empty($row[1]) && empty($row[2])) {
                    continue;
                }

                // العمود 0: BRAND ID (UUID)
                $uuid = trim($row[0] ?? '');

                // العمود 1: Brand Store
                $brandStoreName = trim($row[1] ?? '');

                // العمود 2: Store Name
                $storeName = trim($row[2] ?? '');

                // العمود 3: City
                $city = trim($row[3] ?? '');

                // العمود 4: Google_location
                $googleLocation = trim($row[4] ?? '');

                // العمود 5: Email_Address
                $email = trim($row[5] ?? '');

                // البحث عن brand_id المطابق
                $brandId = null;

                // البحث بالتطابق التام أولاً
                if (isset($brands[$brandStoreName])) {
                    $brandId = $brands[$brandStoreName];
                } else {
                    // البحث بالتطابق الجزئي (case-insensitive)
                    foreach ($brands as $brandName => $id) {
                        if (
                            stripos($brandName, $brandStoreName) !== false ||
                            stripos($brandStoreName, $brandName) !== false
                        ) {
                            $brandId = $id;
                            break;
                        }
                    }
                }

                // إذا لم يتم العثور على brand
                if (!$brandId) {
                    $failedCount++;
                    $errors[] = "صف " . ($index + 2) . ": لم يتم العثور على Brand Store: $brandStoreName";
                    continue;
                }

                // التحقق من وجود البيانات المطلوبة
                if (empty($uuid) || empty($storeName) || empty($email)) {
                    $failedCount++;
                    $errors[] = "صف " . ($index + 2) . ": بيانات ناقصة (UUID, Store Name, Email مطلوبة)";
                    continue;
                }

                // حفظ المتجر
                Illuminate\Support\Facades\DB::table('stores')->insert([
                    'brand_id' => $brandId,
                    'uuid' => $uuid,
                    'name' => $storeName,
                    'email' => $email,
                    'phone' => null,
                    'country' => 'KSA',
                    'city' => $city ?: null,
                    'state' => null,
                    'address' => $googleLocation ?: null,
                    'zip' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
                $errors[] = "صف " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        Illuminate\Support\Facades\DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'تم استيراد البيانات',
            'stats' => [
                'success' => $successCount,
                'failed' => $failedCount,
                'total' => $successCount + $failedCount
            ],
            'errors' => $errors
        ]);
    } catch (\Exception $e) {
        Illuminate\Support\Facades\DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('import_others', function () {
    // دالة مساعدة لتحميل الملفات من الروابط
    $downloadFileFromUrl = function ($url, $directory, $prefix = 'file') {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            // تحويل Google Drive links لروابط مباشرة
            if (strpos($url, 'drive.google.com') !== false) {
                preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches);
                if (isset($matches[1])) {
                    $fileId = $matches[1];
                    $url = "https://drive.google.com/uc?export=download&id={$fileId}";
                } else {
                    Log::error("Cannot extract Google Drive file ID from: {$url}");
                    return null;
                }
            }

            // إنشاء المجلد
            $fullPath = public_path('uploads/' . $directory);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            // اسم الملف
            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'pdf';
            $filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $extension;
            $filePath = $fullPath . '/' . $filename;

            // فتح الملف للكتابة
            $fp = fopen($filePath, 'wb');

            if ($fp === false) {
                Log::error("Cannot open file for writing: {$filePath}");
                return null;
            }

            // إعداد cURL
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_FILE => $fp,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                CURLOPT_ENCODING => '',
                CURLOPT_BINARYTRANSFER => true,
                // مهم لـ Google Drive
                CURLOPT_COOKIEJAR => storage_path('app/cookies.txt'),
                CURLOPT_COOKIEFILE => storage_path('app/cookies.txt'),
            ]);

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            $errno = curl_errno($ch);

            curl_close($ch);
            fclose($fp);

            // التحقق من الأخطاء
            if ($errno !== 0 || !$result) {
                @unlink($filePath);
                Log::error("cURL Error from {$url}: [{$errno}] {$error}");
                return null;
            }

            if ($httpCode < 200 || $httpCode >= 300) {
                @unlink($filePath);
                Log::error("HTTP Error from {$url}: HTTP {$httpCode}");
                return null;
            }

            // التحقق من حجم الملف
            clearstatcache();
            $fileSize = filesize($filePath);

            if ($fileSize === false || $fileSize < 100) {
                @unlink($filePath);
                Log::error("File too small from {$url}: {$fileSize} bytes");
                return null;
            }

            // التحقق من أن الملف PDF (إذا كان المفروض يكون PDF)
            $handle = fopen($filePath, 'rb');
            $header = fread($handle, 10);
            fclose($handle);

            // Check if it's HTML (Google Drive error page)
            if (stripos($header, '<html') !== false || stripos($header, '<!DOCTYPE') !== false) {
                @unlink($filePath);
                Log::error("Downloaded HTML instead of file from {$url}");
                return null;
            }

            Log::info("Successfully downloaded from {$url}: {$fileSize} bytes");

            return $directory . '/' . $filename;
        } catch (\Exception $e) {
            Log::error("Exception downloading from {$url}: " . $e->getMessage());
            if (isset($filePath) && file_exists($filePath)) {
                @unlink($filePath);
            }
            return null;
        }
    };

    // دالة لتحويل المدينة إلى منطقة
    $getCityRegion = function ($city) {
        $westernCities = ['جدة', 'jeddah', 'مكة', 'makkah', 'الطائف', 'taif', 'ينبع', 'yanbu'];
        $centralCities = ['الرياض', 'riyadh', 'القصيم', 'qassim', 'حائل', 'hail'];
        $easternCities = ['الدمام', 'dammam', 'الخبر', 'khobar', 'الأحساء', 'ahsa', 'الجبيل', 'jubail'];

        $cityLower = strtolower($city);

        foreach ($westernCities as $wCity) {
            if (stripos($cityLower, $wCity) !== false) return 'western_province';
        }
        foreach ($centralCities as $cCity) {
            if (stripos($cityLower, $cCity) !== false) return 'central_province';
        }
        foreach ($easternCities as $eCity) {
            if (stripos($cityLower, $eCity) !== false) return 'eastern_province';
        }

        return 'general';
    };

    // دالة لتحويل الأولوية
    $mapPriority = function ($priority) {
        $priorityLower = strtolower(trim($priority));
        if (in_array($priorityLower, ['high', 'عالي', 'urgent'])) return 'high';
        if (in_array($priorityLower, ['low', 'منخفض'])) return 'low';
        return 'medium';
    };

    // دالة لتحويل حالة الطلب
    $mapRequestStatus = function ($status) {
        $statusLower = strtolower(trim($status));
        if (stripos($statusLower, 'cancel') !== false || stripos($statusLower, 'ملغي') !== false) return 'cancelled';
        if (stripos($statusLower, 'complet') !== false || stripos($statusLower, 'مكتمل') !== false) return 'completed';
        if (stripos($statusLower, 'working') !== false || stripos($statusLower, 'تحت التنفيذ') !== false) return 'under_working';
        if (stripos($statusLower, 'hold') !== false || stripos($statusLower, 'معلق') !== false) return 'on_hold';
        return 'new_order';
    };

    // دالة لتحويل حالة الدفع
    $mapPaymentStatus = function ($status) {
        $statusLower = strtolower(trim($status));
        if (stripos($statusLower, 'paid') !== false || stripos($statusLower, 'مدفوع') !== false) return 'paid';
        if (stripos($statusLower, 'cancel') !== false || stripos($statusLower, 'ملغي') !== false) return 'canceled';
        if (stripos($statusLower, 'issue') !== false || stripos($statusLower, 'مشكلة') !== false) return 'invoice_issuse';
        if (stripos($statusLower, 'ready') !== false || stripos($statusLower, 'جاهز') !== false) return 'ready_of_invoicing';
        if (stripos($statusLower, 'submit') !== false || stripos($statusLower, 'مقدم') !== false) return 'submitted';
        return 'pending';
    };

    try {
        $filePath = public_path('others_data_25.xlsx');
        $filePath2 = public_path('others_data_24.xlsx');


        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'ملف الإكسيل غير موجود'
            ], 404);
        }

        $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $spreadsheet2 = PhpOffice\PhpSpreadsheet\IOFactory::load($filePath2);
        $worksheet2 = $spreadsheet2->getActiveSheet();
        $rows2 = $worksheet2->toArray();

        // إزالة الصف الأول (العناوين)
        array_shift($rows);
        array_shift($rows2);

        $rows = array_merge($rows, $rows2);

        // تجميع الصفوف حسب PO_number
        $groupedByPO = [];
        foreach ($rows as $index => $row) {
            if (empty($row[0])) {
                continue;
            }
            $poNumber = trim($row[0]);
            if (!isset($groupedByPO[$poNumber])) {
                $groupedByPO[$poNumber] = [];
            }
            $groupedByPO[$poNumber][] = ['index' => $index, 'data' => $row];
        }

        Illuminate\Support\Facades\DB::beginTransaction();

        // جلب البيانات المرجعية
        $brands = Illuminate\Support\Facades\DB::table('brands')->pluck('id', 'name')->toArray();
        $stores = Illuminate\Support\Facades\DB::table('stores')->pluck('id', 'name')->toArray();
        $users = Illuminate\Support\Facades\DB::table('users')->pluck('id', 'name')->toArray();
        $projectTypes = Illuminate\Support\Facades\DB::table('project_types')->pluck('id', 'name')->toArray();
        $projectCapacities = Illuminate\Support\Facades\DB::table('project_capacities')->pluck('id', 'name')->toArray();
        $projectVolts = Illuminate\Support\Facades\DB::table('project_volts')->pluck('id', 'value')->toArray();
        $projectModels = Illuminate\Support\Facades\DB::table('project_models')->pluck('id', 'name')->toArray();

        $successCount = 0;
        $failedCount = 0;
        $errors = [];
        $processedInvoices = []; // لتتبع الفواتير المعالجة

        // معالجة كل مجموعة PO
        foreach ($groupedByPO as $poNumber => $rowsGroup) {
            try {
                // استخدام أول صف لإنشاء المشروع
                $firstRow = $rowsGroup[0]['data'];
                $firstIndex = $rowsGroup[0]['index'];

                // استخراج البيانات من الصف الأول
                $PO_numbers = trim($firstRow[0] ?? '');
                $Acopy_of_PO = trim($firstRow[1] ?? '');
                $City = trim($firstRow[2] ?? '');
                $Brand_Store = trim($firstRow[3] ?? '');
                $Store_Name = trim($firstRow[4] ?? '');
                $Request_Priority = trim($firstRow[10] ?? '');
                $Request_Status = trim($firstRow[11] ?? '');
                $Date = trim($firstRow[12] ?? '');
                $Amount = trim($firstRow[19] ?? '');
                $Additional_works = trim($firstRow[20] ?? '');
                $Comments = trim($firstRow[21] ?? '');
                $User = trim($firstRow[22] ?? '');

                // البحث عن المستخدم
                $userId = null;
                foreach ($users as $userName => $id) {
                    if (stripos($userName, $User) !== false || stripos($User, $userName) !== false) {
                        $userId = $id;
                        break;
                    }
                }

                if (!$userId) {
                    $userId = 1; // المستخدم الافتراضي
                }

                // البحث عن أو إنشاء المتجر
                $storeId = null;
                foreach ($stores as $storeName => $id) {
                    if (stripos($storeName, $Store_Name) !== false || stripos($Store_Name, $storeName) !== false) {
                        $storeId = $id;
                        break;
                    }
                }

                // إذا لم يتم العثور على المتجر، قم بإنشائه
                if (!$storeId && !empty($Store_Name)) {
                    $brandId = null;
                    foreach ($brands as $brandName => $id) {
                        if (stripos($brandName, $Brand_Store) !== false || stripos($Brand_Store, $brandName) !== false) {
                            $brandId = $id;
                            break;
                        }
                    }

                    if ($brandId) {
                        $storeId = Illuminate\Support\Facades\DB::table('stores')->insertGetId([
                            'brand_id' => $brandId,
                            'uuid' => Illuminate\Support\Str::uuid(),
                            'name' => $Store_Name,
                            'email' => strtolower(str_replace(' ', '_', $Store_Name)) . '@store.com',
                            'phone' => null,
                            'country' => 'KSA',
                            'city' => $City ?: null,
                            'state' => null,
                            'address' => null,
                            'zip' => null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        $stores[$Store_Name] = $storeId;
                    }
                }

                if (!$storeId) {
                    $failedCount += count($rowsGroup);
                    $errors[] = "PO $poNumber: لم يتم العثور على المتجر أو البراند";
                    continue;
                }

                // تحميل ملف PO
                // $poFilePath = $Acopy_of_PO;
                $poFilePath = null;
                if (!empty($Acopy_of_PO)) {
                    $poFilePath = $downloadFileFromUrl($Acopy_of_PO, 'project_files', 'po');
                }

                // تحويل التاريخ
                $projectDate = now();
                if (!empty($Date)) {
                    try {
                        if (is_numeric($Date)) {
                            $projectDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($Date);
                        } else {
                            $projectDate = new \DateTime($Date);
                        }
                    } catch (\Exception $e) {
                        $projectDate = now();
                    }
                }

                // إنشاء المشروع مرة واحدة فقط
                $projectAmerId = Illuminate\Support\Facades\DB::table('project_amers')->insertGetId([
                    'po_num' => $PO_numbers,
                    'dept' => 'project',
                    'region' => $getCityRegion($City),
                    'store_id' => $storeId,
                    'user_id' => $userId,
                    'po_file' => $poFilePath,
                    'priority' => $mapPriority($Request_Priority),
                    'date' => $projectDate,
                    'request_status' => $mapRequestStatus($Request_Status),
                    'amount' => floatval(str_replace(',', '', $Amount)) ?: 0,
                    'notes' => trim($Comments . "\n" . $Additional_works),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // معالجة جميع الصفوف في هذه المجموعة لإنشاء project_items
                foreach ($rowsGroup as $rowData) {
                    $row = $rowData['data'];
                    $index = $rowData['index'];

                    try {
                        $Type_of_unit = trim($row[5] ?? '');
                        $AC_Capacity = trim($row[6] ?? '');
                        $Volt = trim($row[7] ?? '');
                        $Qty = trim($row[8] ?? '');
                        $AC_Brand = trim($row[9] ?? '');
                        $Invoice = trim($row[17] ?? '');
                        $Invoice_copy = trim($row[18] ?? '');
                        $Payment_Status = trim($row[16] ?? '');
                        $ItemAmount = trim($row[19] ?? '');
                        $ItemComments = trim($row[21] ?? '');

                        // البحث عن نوع المشروع
                        $projectTypeId = null;
                        foreach ($projectTypes as $typeName => $id) {
                            if (stripos($typeName, $Type_of_unit) !== false || stripos($Type_of_unit, $typeName) !== false) {
                                $projectTypeId = $id;
                                break;
                            }
                        }

                        // البحث عن السعة
                        $projectCapacityId = null;
                        foreach ($projectCapacities as $capacityName => $id) {
                            if (stripos($capacityName, $AC_Capacity) !== false || stripos($AC_Capacity, $capacityName) !== false) {
                                $projectCapacityId = $id;
                                break;
                            }
                        }

                        // البحث عن الفولت
                        $projectVoltId = null;
                        foreach ($projectVolts as $voltValue => $id) {
                            if (stripos($voltValue, $Volt) !== false || stripos($Volt, $voltValue) !== false) {
                                $projectVoltId = $id;
                                break;
                            }
                        }

                        // البحث عن البراند للمكيف
                        $acBrandId = null;
                        foreach ($brands as $brandName => $id) {
                            if (stripos($brandName, $AC_Brand) !== false || stripos($AC_Brand, $brandName) !== false) {
                                $acBrandId = $id;
                                break;
                            }
                        }

                        // البحث عن الموديل
                        $projectModelId = null;
                        foreach ($projectModels as $modelName => $id) {
                            if (stripos($modelName, $AC_Brand) !== false || stripos($AC_Brand, $modelName) !== false) {
                                $projectModelId = $id;
                                break;
                            }
                        }

                        // إنشاء عنصر المشروع
                        if ($projectTypeId) {
                            Illuminate\Support\Facades\DB::table('project_amer_items')->insert([
                                'project_amer_id' => $projectAmerId,
                                'project_type_id' => $projectTypeId,
                                'project_model_id' => $projectModelId,
                                'project_capacity_id' => $projectCapacityId,
                                'project_volt_id' => $projectVoltId,
                                'brand_id' => $acBrandId,
                                'qty' => intval($Qty) ?: 1,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // إنشاء الفاتورة إذا كانت موجودة ولم يتم معالجتها من قبل
                        if (!empty($Invoice) && !isset($processedInvoices[$Invoice])) {
                            $invoiceFilePath = null;
                            if (!empty($Invoice_copy)) {
                                $invoiceFilePath = $downloadFileFromUrl($Invoice_copy, 'invoice_files', 'invoice');
                            } else {
                                $invoiceFilePath = $Invoice_copy;
                            }

                            if ($invoiceFilePath) {
                                // التحقق من عدم وجود الفاتورة في قاعدة البيانات
                                $existingInvoice = Illuminate\Support\Facades\DB::table('invoice_amers')
                                    ->where('invoice_number', $Invoice)
                                    ->first();

                                if (!$existingInvoice) {
                                    Illuminate\Support\Facades\DB::table('invoice_amers')->insert([
                                        'project_amer_id' => $projectAmerId,
                                        'invoice_number' => $Invoice,
                                        'amount' => floatval(str_replace(',', '', $ItemAmount)) ?: 0,
                                        'payment_file' => $invoiceFilePath,
                                        'status' => $mapPaymentStatus($Payment_Status),
                                        'date' => $projectDate,
                                        'notes' => $ItemComments,
                                        'created_by' => $userId,
                                        'approved_at' => null,
                                        'approved_by' => null,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                    $processedInvoices[$Invoice] = true;
                                }
                            }
                        }

                        $successCount++;
                    } catch (\Exception $e) {
                        $failedCount++;
                        $errors[] = "صف " . ($index + 2) . ": " . $e->getMessage();
                    }
                }
            } catch (\Exception $e) {
                $failedCount += count($rowsGroup);
                $errors[] = "PO $poNumber: " . $e->getMessage();
            }
        }

        Illuminate\Support\Facades\DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'تم استيراد البيانات بنجاح',
            'stats' => [
                'success' => $successCount,
                'failed' => $failedCount,
                'total' => $successCount + $failedCount
            ],
            'errors' => $errors
        ]);
    } catch (\Exception $e) {
        Illuminate\Support\Facades\DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('import_compressors', function () {
    // دالة مساعدة لتحميل الملفات من الروابط
    $downloadFileFromUrl = function ($url, $directory, $prefix = 'file') {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            // تحويل Google Drive links لروابط مباشرة
            if (strpos($url, 'drive.google.com') !== false) {
                preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches);
                if (isset($matches[1])) {
                    $fileId = $matches[1];
                    $url = "https://drive.google.com/uc?export=download&id={$fileId}";
                } else {
                    Log::error("Cannot extract Google Drive file ID from: {$url}");
                    return null;
                }
            }

            // إنشاء المجلد
            $fullPath = public_path('uploads/' . $directory);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            // اسم الملف
            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'pdf';
            $filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $extension;
            $filePath = $fullPath . '/' . $filename;

            // فتح الملف للكتابة
            $fp = fopen($filePath, 'wb');

            if ($fp === false) {
                Log::error("Cannot open file for writing: {$filePath}");
                return null;
            }

            // إعداد cURL
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_FILE => $fp,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                CURLOPT_ENCODING => '',
                CURLOPT_BINARYTRANSFER => true,
                // مهم لـ Google Drive
                CURLOPT_COOKIEJAR => storage_path('app/cookies.txt'),
                CURLOPT_COOKIEFILE => storage_path('app/cookies.txt'),
            ]);

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            $errno = curl_errno($ch);

            curl_close($ch);
            fclose($fp);

            // التحقق من الأخطاء
            if ($errno !== 0 || !$result) {
                @unlink($filePath);
                Log::error("cURL Error from {$url}: [{$errno}] {$error}");
                return null;
            }

            if ($httpCode < 200 || $httpCode >= 300) {
                @unlink($filePath);
                Log::error("HTTP Error from {$url}: HTTP {$httpCode}");
                return null;
            }

            // التحقق من حجم الملف
            clearstatcache();
            $fileSize = filesize($filePath);

            if ($fileSize === false || $fileSize < 100) {
                @unlink($filePath);
                Log::error("File too small from {$url}: {$fileSize} bytes");
                return null;
            }

            // التحقق من أن الملف PDF (إذا كان المفروض يكون PDF)
            $handle = fopen($filePath, 'rb');
            $header = fread($handle, 10);
            fclose($handle);

            // Check if it's HTML (Google Drive error page)
            if (stripos($header, '<html') !== false || stripos($header, '<!DOCTYPE') !== false) {
                @unlink($filePath);
                Log::error("Downloaded HTML instead of file from {$url}");
                return null;
            }

            Log::info("Successfully downloaded from {$url}: {$fileSize} bytes");

            return $directory . '/' . $filename;
        } catch (\Exception $e) {
            Log::error("Exception downloading from {$url}: " . $e->getMessage());
            if (isset($filePath) && file_exists($filePath)) {
                @unlink($filePath);
            }
            return null;
        }
    };

    // دالة لتحويل المنطقة من النص إلى enum
    $mapRegion = function ($region) {
        $regionLower = strtolower(trim($region));
        if (stripos($regionLower, 'western') !== false || stripos($regionLower, 'غربية') !== false) return 'western_province';
        if (stripos($regionLower, 'central') !== false || stripos($regionLower, 'وسطى') !== false) return 'central_province';
        if (stripos($regionLower, 'eastern') !== false || stripos($regionLower, 'شرقية') !== false) return 'eastern_province';
        return 'general';
    };

    // دالة لتحويل القسم
    $mapDept = function ($dept) {
        $deptLower = strtolower(trim($dept));
        if (stripos($deptLower, 'facility') !== false) return 'facility';
        if (stripos($deptLower, 'maintenance') !== false) {
            if (stripos($deptLower, 'replacing') !== false) return 'maintenance_replacing';
            return 'maintenance';
        }
        if (stripos($deptLower, 'other') !== false) return 'other';
        return 'project';
    };

    // دالة لتحويل الأولوية
    $mapPriority = function ($priority) {
        $priorityLower = strtolower(trim($priority));
        if (in_array($priorityLower, ['high', 'عالي', 'urgent'])) return 'high';
        if (in_array($priorityLower, ['low', 'منخفض'])) return 'low';
        return 'medium';
    };

    // دالة لتحويل حالة الطلب
    $mapRequestStatus = function ($status) {
        $statusLower = strtolower(trim($status));
        if (stripos($statusLower, 'cancel') !== false || stripos($statusLower, 'ملغي') !== false) return 'cancelled';
        if (stripos($statusLower, 'complet') !== false || stripos($statusLower, 'مكتمل') !== false) return 'completed';
        if (stripos($statusLower, 'working') !== false || stripos($statusLower, 'تحت التنفيذ') !== false) return 'under_working';
        if (stripos($statusLower, 'hold') !== false || stripos($statusLower, 'معلق') !== false) return 'on_hold';
        return 'new_order';
    };

    // دالة لتحويل حالة الدفع
    $mapPaymentStatus = function ($status) {
        $statusLower = strtolower(trim($status));
        if (stripos($statusLower, 'paid') !== false || stripos($statusLower, 'مدفوع') !== false) return 'paid';
        if (stripos($statusLower, 'cancel') !== false || stripos($statusLower, 'ملغي') !== false) return 'canceled';
        if (stripos($statusLower, 'issue') !== false || stripos($statusLower, 'مشكلة') !== false) return 'invoice_issuse';
        if (stripos($statusLower, 'ready') !== false || stripos($statusLower, 'جاهز') !== false) return 'ready_of_invoicing';
        if (stripos($statusLower, 'submit') !== false || stripos($statusLower, 'مقدم') !== false) return 'submitted';
        return 'pending';
    };

    try {
        $filePath2024 = public_path('compressors_data_2024.xlsx');
        $filePath2025 = public_path('compressors_data_2025.xlsx');

        if (!file_exists($filePath2024) && !file_exists($filePath2025)) {
            return response()->json([
                'success' => false,
                'message' => 'ملفات الإكسيل غير موجودة'
            ], 404);
        }

        $rows = [];

        // قراءة ملف 2024 إذا كان موجوداً
        if (file_exists($filePath2024)) {
            $spreadsheet2024 = PhpOffice\PhpSpreadsheet\IOFactory::load($filePath2024);
            $worksheet2024 = $spreadsheet2024->getActiveSheet();
            $rows2024 = $worksheet2024->toArray();
            array_shift($rows2024); // إزالة العناوين
            $rows = array_merge($rows, $rows2024);
        }

        // قراءة ملف 2025 إذا كان موجوداً
        if (file_exists($filePath2025)) {
            $spreadsheet2025 = PhpOffice\PhpSpreadsheet\IOFactory::load($filePath2025);
            $worksheet2025 = $spreadsheet2025->getActiveSheet();
            $rows2025 = $worksheet2025->toArray();
            array_shift($rows2025); // إزالة العناوين
            $rows = array_merge($rows, $rows2025);
        }

        // تجميع الصفوف حسب PO_number
        $groupedByPO = [];
        foreach ($rows as $index => $row) {
            if (empty($row[0])) {
                continue;
            }
            $poNumber = trim($row[0]);
            if (!isset($groupedByPO[$poNumber])) {
                $groupedByPO[$poNumber] = [];
            }
            $groupedByPO[$poNumber][] = ['index' => $index, 'data' => $row];
        }

        // dd($groupedByPO);

        Illuminate\Support\Facades\DB::beginTransaction();

        // جلب البيانات المرجعية
        $brands = Illuminate\Support\Facades\DB::table('brands')->pluck('id', 'name')->toArray();
        $stores = Illuminate\Support\Facades\DB::table('stores')->pluck('id', 'name')->toArray();
        $storesByUuid = Illuminate\Support\Facades\DB::table('stores')->pluck('id', 'uuid')->toArray();
        $users = Illuminate\Support\Facades\DB::table('users')->pluck('id', 'name')->toArray();
        $projectTypes = Illuminate\Support\Facades\DB::table('project_types')->pluck('id', 'name')->toArray();
        $projectModels = Illuminate\Support\Facades\DB::table('project_models')->pluck('id', 'name')->toArray();

        $successCount = 0;
        $failedCount = 0;
        $errors = [];
        $processedInvoices = [];

        // معالجة كل مجموعة PO
        foreach ($groupedByPO as $poNumber => $rowsGroup) {
            try {
                // استخدام أول صف لإنشاء المشروع
                $firstRow = $rowsGroup[0]['data'];
                $firstIndex = $rowsGroup[0]['index'];

                // استخراج البيانات من الصف الأول
                $PO_numbers = trim($firstRow[0] ?? '');
                $Acopy_of_PO = trim($firstRow[1] ?? '');
                $Dept = trim($firstRow[2] ?? '');
                $PM_Name = trim($firstRow[3] ?? '');
                $Region = trim($firstRow[4] ?? '');
                $City = trim($firstRow[5] ?? '');
                $Store_ID = trim($firstRow[6] ?? '');
                $Brand_Store = trim($firstRow[7] ?? '');
                $Store_Name = trim($firstRow[8] ?? '');
                $Google_location = trim($firstRow[9] ?? '');
                $Email_Address = trim($firstRow[10] ?? '');
                $Request_Priority = trim($firstRow[17] ?? '');
                $Request_Status = trim($firstRow[18] ?? '');
                $Date = trim($firstRow[19] ?? '');
                $Amount = trim($firstRow[26] ?? '');
                $Additional_works = trim($firstRow[27] ?? '');
                $Comments = trim($firstRow[28] ?? '');
                $User = trim($firstRow[29] ?? '');

                // البحث عن المستخدم
                $userId = null;
                foreach ($users as $userName => $id) {
                    if (stripos($userName, $User) !== false || stripos($User, $userName) !== false) {
                        $userId = $id;
                        break;
                    }
                }

                if (!$userId) {
                    $userId = 1; // المستخدم الافتراضي
                }

                // البحث عن المتجر بالـ UUID أولاً، ثم بالاسم
                $storeId = null;

                // البحث بالـ UUID
                if (!empty($Store_ID) && isset($storesByUuid[$Store_ID])) {
                    $storeId = $storesByUuid[$Store_ID];
                }

                // البحث بالاسم إذا لم يتم العثور عليه بالـ UUID
                if (!$storeId) {
                    foreach ($stores as $storeName => $id) {
                        if (stripos($storeName, $Store_Name) !== false || stripos($Store_Name, $storeName) !== false) {
                            $storeId = $id;
                            break;
                        }
                    }
                }

                // إذا لم يتم العثور على المتجر، قم بإنشائه
                if (!$storeId && !empty($Store_Name)) {
                    $brandId = null;
                    foreach ($brands as $brandName => $id) {
                        if (stripos($brandName, $Brand_Store) !== false || stripos($Brand_Store, $brandName) !== false) {
                            $brandId = $id;
                            break;
                        }
                    }

                    if ($brandId) {
                        $storeId = Illuminate\Support\Facades\DB::table('stores')->insertGetId([
                            'brand_id' => $brandId,
                            'uuid' => !empty($Store_ID) ? $Store_ID : Illuminate\Support\Str::uuid(),
                            'name' => $Store_Name,
                            'email' => !empty($Email_Address) ? $Email_Address : strtolower(str_replace(' ', '_', $Store_Name)) . '@store.com',
                            'phone' => null,
                            'country' => 'KSA',
                            'city' => $City ?: null,
                            'state' => null,
                            'address' => $Google_location ?: null,
                            'zip' => null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        $stores[$Store_Name] = $storeId;
                        if (!empty($Store_ID)) {
                            $storesByUuid[$Store_ID] = $storeId;
                        }
                    }
                }

                if (!$storeId) {
                    $failedCount += count($rowsGroup);
                    $errors[] = "PO $poNumber: لم يتم العثور على المتجر أو البراند";
                    continue;
                }

                // تحميل ملف PO
                // $poFilePath = $Acopy_of_PO;
                $poFilePath = null;
                if (!empty($Acopy_of_PO)) {
                    $poFilePath = $downloadFileFromUrl($Acopy_of_PO, 'project_files', 'po');
                }

                // تحويل التاريخ
                $projectDate = now();
                if (!empty($Date)) {
                    try {
                        if (is_numeric($Date)) {
                            $projectDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($Date);
                        } else {
                            $projectDate = new \DateTime($Date);
                        }
                    } catch (\Exception $e) {
                        $projectDate = now();
                    }
                }

                // إنشاء المشروع مرة واحدة فقط
                $projectAmerId = Illuminate\Support\Facades\DB::table('project_amers')->insertGetId([
                    'po_num' => $PO_numbers,
                    'dept' => $mapDept($Dept),
                    'region' => $mapRegion($Region),
                    'store_id' => $storeId,
                    'user_id' => $userId,
                    'po_file' => $poFilePath,
                    'priority' => $mapPriority($Request_Priority),
                    'date' => $projectDate,
                    'request_status' => $mapRequestStatus($Request_Status),
                    'amount' => floatval(str_replace(',', '', $Amount)) ?: 0,
                    'notes' => trim($Comments . "\n" . $Additional_works),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // معالجة جميع الصفوف في هذه المجموعة لإنشاء project_items
                foreach ($rowsGroup as $rowData) {
                    $row = $rowData['data'];
                    $index = $rowData['index'];

                    try {
                        $Type = trim($row[11] ?? '');
                        $Model_of_Quotation = trim($row[12] ?? '');
                        $Model_of_Americana = trim($row[13] ?? '');
                        $Model_of_Supplier = trim($row[14] ?? '');
                        $Qty = trim($row[15] ?? '');
                        $Note = trim($row[16] ?? '');
                        $Invoice = trim($row[24] ?? '');
                        $Invoice_copy = trim($row[25] ?? '');
                        $Payment_Status = trim($row[23] ?? '');
                        $ItemAmount = trim($row[26] ?? '');
                        $ItemComments = trim($row[28] ?? '');

                        // البحث عن نوع المشروع
                        $projectTypeId = null;
                        foreach ($projectTypes as $typeName => $id) {
                            if (stripos($typeName, $Type) !== false || stripos($Type, $typeName) !== false) {
                                $projectTypeId = $id;
                                break;
                            }
                        }

                        // البحث عن الموديل Model_of_Quotation
                        $projectModelId = null;
                        $modelToSearch = !empty($Model_of_Quotation) ? $Model_of_Quotation : $Model_of_Americana;

                        if (!empty($modelToSearch)) {
                            foreach ($projectModels as $modelName => $id) {
                                if (stripos($modelName, $modelToSearch) !== false || stripos($modelToSearch, $modelName) !== false) {
                                    $projectModelId = $id;
                                    break;
                                }
                            }
                        }

                        // إنشاء عنصر المشروع
                        if ($projectTypeId) {
                            Illuminate\Support\Facades\DB::table('project_amer_items')->insert([
                                'project_amer_id' => $projectAmerId,
                                'project_type_id' => $projectTypeId,
                                'project_model_id' => $projectModelId,
                                'project_capacity_id' => null,
                                'project_volt_id' => null,
                                'brand_id' => null,
                                'qty' => intval($Qty) ?: 1,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }

                        // إنشاء الفاتورة إذا كانت موجودة ولم يتم معالجتها من قبل
                        if (!empty($Invoice) && !isset($processedInvoices[$Invoice])) {
                            // $invoiceFilePath = $Invoice_copy;
                            $invoiceFilePath = null;
                            if (!empty($Invoice_copy)) {
                                $invoiceFilePath = $downloadFileFromUrl($Invoice_copy, 'invoice_files', 'invoice');
                            } else {
                                $invoiceFilePath = $Invoice_copy;
                            }

                            if ($invoiceFilePath) {
                                // التحقق من عدم وجود الفاتورة في قاعدة البيانات
                                $existingInvoice = Illuminate\Support\Facades\DB::table('invoice_amers')
                                    ->where('invoice_number', $Invoice)
                                    ->first();

                                if (!$existingInvoice) {
                                    Illuminate\Support\Facades\DB::table('invoice_amers')->insert([
                                        'project_amer_id' => $projectAmerId,
                                        'invoice_number' => $Invoice,
                                        'amount' => floatval(str_replace(',', '', $ItemAmount)) ?: 0,
                                        'payment_file' => $invoiceFilePath,
                                        'status' => $mapPaymentStatus($Payment_Status),
                                        'date' => $projectDate,
                                        'notes' => $ItemComments,
                                        'created_by' => $userId,
                                        'approved_at' => null,
                                        'approved_by' => null,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                    $processedInvoices[$Invoice] = true;
                                }
                            }
                        }

                        $successCount++;
                    } catch (\Exception $e) {
                        $failedCount++;
                        $errors[] = "صف " . ($index + 2) . ": " . $e->getMessage();
                    }
                }
            } catch (\Exception $e) {
                $failedCount += count($rowsGroup);
                $errors[] = "PO $poNumber: " . $e->getMessage();
            }
        }

        Illuminate\Support\Facades\DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'تم استيراد بيانات الكومبريسور بنجاح',
            'stats' => [
                'success' => $successCount,
                'failed' => $failedCount,
                'total' => $successCount + $failedCount
            ],
            'errors' => $errors
        ]);
    } catch (\Exception $e) {
        Illuminate\Support\Facades\DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('import_masters', function () {

    // دالة مساعدة لتحميل الملفات من الروابط
    $downloadFileFromUrl = function ($url, $directory, $prefix = 'file') {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            // تحويل Google Drive links لروابط مباشرة
            if (strpos($url, 'drive.google.com') !== false) {
                preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches);
                if (isset($matches[1])) {
                    $fileId = $matches[1];
                    $url = "https://drive.google.com/uc?export=download&id={$fileId}";
                } else {
                    Log::error("Cannot extract Google Drive file ID from: {$url}");
                    return null;
                }
            }

            // إنشاء المجلد
            $fullPath = public_path('uploads/' . $directory);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            // اسم الملف
            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'pdf';
            $filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $extension;
            $filePath = $fullPath . '/' . $filename;

            // فتح الملف للكتابة
            $fp = fopen($filePath, 'wb');

            if ($fp === false) {
                Log::error("Cannot open file for writing: {$filePath}");
                return null;
            }

            // إعداد cURL
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_FILE => $fp,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                CURLOPT_ENCODING => '',
                CURLOPT_BINARYTRANSFER => true,
                // مهم لـ Google Drive
                CURLOPT_COOKIEJAR => storage_path('app/cookies.txt'),
                CURLOPT_COOKIEFILE => storage_path('app/cookies.txt'),
            ]);

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            $errno = curl_errno($ch);

            curl_close($ch);
            fclose($fp);

            // التحقق من الأخطاء
            if ($errno !== 0 || !$result) {
                @unlink($filePath);
                Log::error("cURL Error from {$url}: [{$errno}] {$error}");
                return null;
            }

            if ($httpCode < 200 || $httpCode >= 300) {
                @unlink($filePath);
                Log::error("HTTP Error from {$url}: HTTP {$httpCode}");
                return null;
            }

            // التحقق من حجم الملف
            clearstatcache();
            $fileSize = filesize($filePath);

            if ($fileSize === false || $fileSize < 100) {
                @unlink($filePath);
                Log::error("File too small from {$url}: {$fileSize} bytes");
                return null;
            }

            // التحقق من أن الملف PDF (إذا كان المفروض يكون PDF)
            $handle = fopen($filePath, 'rb');
            $header = fread($handle, 10);
            fclose($handle);

            // Check if it's HTML (Google Drive error page)
            if (stripos($header, '<html') !== false || stripos($header, '<!DOCTYPE') !== false) {
                @unlink($filePath);
                Log::error("Downloaded HTML instead of file from {$url}");
                return null;
            }

            Log::info("Successfully downloaded from {$url}: {$fileSize} bytes");

            return $directory . '/' . $filename;
        } catch (\Exception $e) {
            Log::error("Exception downloading from {$url}: " . $e->getMessage());
            if (isset($filePath) && file_exists($filePath)) {
                @unlink($filePath);
            }
            return null;
        }
    };


    // دالة لتحويل المنطقة من النص إلى enum
    $mapRegion = function ($region) {
        $regionLower = strtolower(trim($region));
        if (stripos($regionLower, 'western') !== false || stripos($regionLower, 'غربية') !== false) return 'western_province';
        if (stripos($regionLower, 'central') !== false || stripos($regionLower, 'وسطى') !== false) return 'central_province';
        if (stripos($regionLower, 'eastern') !== false || stripos($regionLower, 'شرقية') !== false) return 'eastern_province';
        return 'general';
    };

    // دالة لتحويل القسم
    $mapDept = function ($dept) {
        $deptLower = strtolower(trim($dept));
        if (stripos($deptLower, 'facility') !== false) return 'facility';
        if (stripos($deptLower, 'maintenance') !== false) {
            if (stripos($deptLower, 'replacing') !== false) return 'maintenance_replacing';
            return 'maintenance_replacing';
        }
        if (stripos($deptLower, 'other') !== false) return 'other';
        return 'project';
    };

    // دالة لتحويل الأولوية
    $mapPriority = function ($priority) {
        $priorityLower = strtolower(trim($priority));
        if (in_array($priorityLower, ['high', 'عالي', 'urgent'])) return 'high';
        if (in_array($priorityLower, ['low', 'منخفض'])) return 'low';
        return 'medium';
    };

    // دالة لتحويل حالة الطلب
    $mapRequestStatus = function ($status) {
        $statusLower = strtolower(trim($status));
        if (stripos($statusLower, 'cancel') !== false || stripos($statusLower, 'ملغي') !== false) return 'cancelled';
        if (stripos($statusLower, 'complet') !== false || stripos($statusLower, 'مكتمل') !== false) return 'completed';
        if (stripos($statusLower, 'working') !== false || stripos($statusLower, 'تحت التنفيذ') !== false) return 'under_working';
        if (stripos($statusLower, 'hold') !== false || stripos($statusLower, 'معلق') !== false) return 'on_hold';
        return 'new_order';
    };

    // دالة لتحويل حالة الدفع
    $mapPaymentStatus = function ($status) {
        $statusLower = strtolower(trim($status));
        if (stripos($statusLower, 'paid') !== false || stripos($statusLower, 'مدفوع') !== false) return 'paid';
        if (stripos($statusLower, 'cancel') !== false || stripos($statusLower, 'ملغي') !== false) return 'canceled';
        if (stripos($statusLower, 'issue') !== false || stripos($statusLower, 'مشكلة') !== false) return 'invoice_issuse';
        if (stripos($statusLower, 'ready') !== false || stripos($statusLower, 'جاهز') !== false) return 'ready_of_invoicing';
        if (stripos($statusLower, 'submit') !== false || stripos($statusLower, 'مقدم') !== false) return 'submitted';
        return 'pending';
    };

    try {
        $filePath2024 = public_path('import_master_2024.xlsx');
        $filePath2025 = public_path('import_master_2025.xlsx');

        if (!file_exists($filePath2024) && !file_exists($filePath2025)) {
            return response()->json([
                'success' => false,
                'message' => 'ملفات الإكسيل غير موجودة'
            ], 404);
        }

        $rows = [];

        // قراءة ملف 2024 إذا كان موجوداً
        if (file_exists($filePath2024)) {
            $spreadsheet2024 = PhpOffice\PhpSpreadsheet\IOFactory::load($filePath2024);
            $worksheet2024 = $spreadsheet2024->getActiveSheet();
            $rows2024 = $worksheet2024->toArray();
            array_shift($rows2024); // إزالة العناوين
            $rows = array_merge($rows, $rows2024);
        }

        // قراءة ملف 2025 إذا كان موجوداً
        if (file_exists($filePath2025)) {
            $spreadsheet2025 = PhpOffice\PhpSpreadsheet\IOFactory::load($filePath2025);
            $worksheet2025 = $spreadsheet2025->getActiveSheet();
            $rows2025 = $worksheet2025->toArray();
            array_shift($rows2025); // إزالة العناوين
            $rows = array_merge($rows, $rows2025);
        }

        // تجميع الصفوف حسب PO_number
        $groupedByPO = [];
        foreach ($rows as $index => $row) {
            if (empty($row[0])) {
                continue;
            }
            $poNumber = trim($row[0]);
            if (!isset($groupedByPO[$poNumber])) {
                $groupedByPO[$poNumber] = [];
            }
            $groupedByPO[$poNumber][] = ['index' => $index, 'data' => $row];
        }

        Illuminate\Support\Facades\DB::beginTransaction();

        // جلب البيانات المرجعية
        $brands = Illuminate\Support\Facades\DB::table('brands')->pluck('id', 'name')->toArray();
        $stores = Illuminate\Support\Facades\DB::table('stores')->pluck('id', 'name')->toArray();
        $storesByUuid = Illuminate\Support\Facades\DB::table('stores')->pluck('id', 'uuid')->toArray();
        $users = Illuminate\Support\Facades\DB::table('users')->pluck('id', 'name')->toArray();
        $projectTypes = Illuminate\Support\Facades\DB::table('project_types')->pluck('id', 'name')->toArray();
        $projectCapacities = Illuminate\Support\Facades\DB::table('project_capacities')->pluck('id', 'name')->toArray();
        $projectVolts = Illuminate\Support\Facades\DB::table('project_volts')->pluck('id', 'value')->toArray();
        $projectModels = Illuminate\Support\Facades\DB::table('project_models')->pluck('id', 'name')->toArray();

        $successCount = 0;
        $failedCount = 0;
        $errors = [];
        $processedInvoices = [];

        // معالجة كل مجموعة PO
        foreach ($groupedByPO as $poNumber => $rowsGroup) {
            try {
                // استخدام أول صف لإنشاء المشروع
                $firstRow = $rowsGroup[0]['data'];
                $firstIndex = $rowsGroup[0]['index'];

                // استخراج البيانات من الصف الأول
                $PO_numbers = trim($firstRow[0] ?? '');
                $Acopy_of_PO = trim($firstRow[1] ?? '');
                $Dept = trim($firstRow[2] ?? '');
                $PM_Name = trim($firstRow[3] ?? '');
                $Region = trim($firstRow[4] ?? '');
                $City = trim($firstRow[5] ?? '');
                $Store_ID = trim($firstRow[6] ?? '');
                $Brand_Store = trim($firstRow[7] ?? '');
                $Store_Name = trim($firstRow[8] ?? '');
                $Google_location = trim($firstRow[9] ?? '');
                $Email_Address = trim($firstRow[10] ?? '');
                $Request_Priority = trim($firstRow[16] ?? '');
                $Request_Status = trim($firstRow[17] ?? '');
                $Date = trim($firstRow[18] ?? '');
                $Amount = trim($firstRow[25] ?? '');
                $Additional_works = trim($firstRow[26] ?? '');
                $Comments = trim($firstRow[27] ?? '');
                $User = trim($firstRow[28] ?? '');

                // البحث عن المستخدم
                $userId = null;
                foreach ($users as $userName => $id) {
                    if (stripos($userName, $User) !== false || stripos($User, $userName) !== false) {
                        $userId = $id;
                        break;
                    }
                }

                if (!$userId) {
                    $userId = 1; // المستخدم الافتراضي
                }

                // البحث عن المتجر بالـ UUID أولاً، ثم بالاسم
                $storeId = null;

                // البحث بالـ UUID
                if (!empty($Store_ID) && isset($storesByUuid[$Store_ID])) {
                    $storeId = $storesByUuid[$Store_ID];
                }

                // البحث بالاسم إذا لم يتم العثور عليه بالـ UUID
                if (!$storeId) {
                    foreach ($stores as $storeName => $id) {
                        if (stripos($storeName, $Store_Name) !== false || stripos($Store_Name, $storeName) !== false) {
                            $storeId = $id;
                            break;
                        }
                    }
                }

                // إذا لم يتم العثور على المتجر، قم بإنشائه
                if (!$storeId && !empty($Store_Name)) {
                    $brandId = null;
                    foreach ($brands as $brandName => $id) {
                        if (stripos($brandName, $Brand_Store) !== false || stripos($Brand_Store, $brandName) !== false) {
                            $brandId = $id;
                            break;
                        }
                    }

                    if ($brandId) {
                        $storeId = Illuminate\Support\Facades\DB::table('stores')->insertGetId([
                            'brand_id' => $brandId,
                            'uuid' => !empty($Store_ID) ? $Store_ID : Illuminate\Support\Str::uuid(),
                            'name' => $Store_Name,
                            'email' => !empty($Email_Address) ? $Email_Address : strtolower(str_replace(' ', '_', $Store_Name)) . '@store.com',
                            'phone' => null,
                            'country' => 'KSA',
                            'city' => $City ?: null,
                            'state' => null,
                            'address' => $Google_location ?: null,
                            'zip' => null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        $stores[$Store_Name] = $storeId;
                        if (!empty($Store_ID)) {
                            $storesByUuid[$Store_ID] = $storeId;
                        }
                    }
                }

                if (!$storeId) {
                    $failedCount += count($rowsGroup);
                    $errors[] = "PO $poNumber: لم يتم العثور على المتجر أو البراند";
                    continue;
                }

                // تحميل ملف PO
                // $poFilePath = $Acopy_of_PO;
                $poFilePath = null;
                if (!empty($Acopy_of_PO)) {
                    $poFilePath = $downloadFileFromUrl($Acopy_of_PO, 'project_files', 'po');
                }

                // تحويل التاريخ
                $projectDate = now();
                if (!empty($Date)) {
                    try {
                        if (is_numeric($Date)) {
                            $projectDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($Date);
                        } else {
                            $projectDate = new \DateTime($Date);
                        }
                    } catch (\Exception $e) {
                        $projectDate = now();
                    }
                }

                // إنشاء المشروع مرة واحدة فقط
                $projectAmerId = Illuminate\Support\Facades\DB::table('project_amers')->insertGetId([
                    'po_num' => $PO_numbers,
                    'dept' => $mapDept($Dept),
                    'region' => $mapRegion($Region),
                    'store_id' => $storeId,
                    'user_id' => $userId,
                    'po_file' => $poFilePath,
                    'priority' => $mapPriority($Request_Priority),
                    'date' => $projectDate,
                    'request_status' => $mapRequestStatus($Request_Status),
                    'amount' => floatval(str_replace(',', '', $Amount)) ?: 0,
                    'notes' => trim($Comments . "\n" . $Additional_works),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // معالجة جميع الصفوف في هذه المجموعة لإنشاء project_items
                foreach ($rowsGroup as $rowData) {
                    $row = $rowData['data'];
                    $index = $rowData['index'];

                    try {
                        $Type_of_unit = trim($row[11] ?? '');
                        $AC_Capacity = trim($row[12] ?? '');
                        $Volt = trim($row[13] ?? '');
                        $Qty = trim($row[14] ?? '');
                        $AC_Brand = trim($row[15] ?? '');
                        $Invoice = trim($row[23] ?? '');
                        $Invoice_copy = trim($row[24] ?? '');
                        $Payment_Status = trim($row[22] ?? '');
                        $ItemAmount = trim($row[25] ?? '');
                        $ItemComments = trim($row[27] ?? '');

                        // البحث عن نوع المشروع
                        $projectTypeId = null;
                        foreach ($projectTypes as $typeName => $id) {
                            if (stripos($typeName, $Type_of_unit) !== false || stripos($Type_of_unit, $typeName) !== false) {
                                $projectTypeId = $id;
                                break;
                            }
                        }

                        // البحث عن السعة
                        $projectCapacityId = null;
                        foreach ($projectCapacities as $capacityName => $id) {
                            if (stripos($capacityName, $AC_Capacity) !== false || stripos($AC_Capacity, $capacityName) !== false) {
                                $projectCapacityId = $id;
                                break;
                            }
                        }

                        // البحث عن الفولت
                        $projectVoltId = null;
                        foreach ($projectVolts as $voltValue => $id) {
                            if (stripos($voltValue, $Volt) !== false || stripos($Volt, $voltValue) !== false) {
                                $projectVoltId = $id;
                                break;
                            }
                        }

                        // البحث عن البراند للمكيف
                        $acBrandId = null;
                        foreach ($brands as $brandName => $id) {
                            if (stripos($brandName, $AC_Brand) !== false || stripos($AC_Brand, $brandName) !== false) {
                                $acBrandId = $id;
                                break;
                            }
                        }

                        // البحث عن الموديل
                        $projectModelId = null;
                        foreach ($projectModels as $modelName => $id) {
                            if (stripos($modelName, $AC_Brand) !== false || stripos($AC_Brand, $modelName) !== false) {
                                $projectModelId = $id;
                                break;
                            }
                        }

                        // إنشاء عنصر المشروع
                        if ($projectTypeId) {
                            Illuminate\Support\Facades\DB::table('project_amer_items')->insert([
                                'project_amer_id' => $projectAmerId,
                                'project_type_id' => $projectTypeId,
                                'project_model_id' => $projectModelId,
                                'project_capacity_id' => $projectCapacityId,
                                'project_volt_id' => $projectVoltId,
                                'brand_id' => $acBrandId,
                                'qty' => intval($Qty) ?: 1,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                            $successCount++; // تم نقله هنا ليحسب فقط الصفوف المحفوظة فعلياً
                        } else {
                            $failedCount++;
                            $errors[] = "صف " . ($index + 2) . ": لم يتم العثور على نوع المشروع";
                        }

                        // إنشاء الفاتورة إذا كانت موجودة ولم يتم معالجتها من قبل
                        if (!empty($Invoice) && !isset($processedInvoices[$Invoice])) {
                            // $invoiceFilePath = $Invoice_copy;
                            $invoiceFilePath = null;
                            if (!empty($Invoice_copy)) {
                                $invoiceFilePath = $downloadFileFromUrl($Invoice_copy, 'invoice_files', 'invoice');
                            } else {
                                $invoiceFilePath = $Invoice_copy;
                            }

                            if ($invoiceFilePath) {
                                // التحقق من عدم وجود الفاتورة في قاعدة البيانات
                                $existingInvoice = Illuminate\Support\Facades\DB::table('invoice_amers')
                                    ->where('invoice_number', $Invoice)
                                    ->first();

                                if (!$existingInvoice) {
                                    Illuminate\Support\Facades\DB::table('invoice_amers')->insert([
                                        'project_amer_id' => $projectAmerId,
                                        'invoice_number' => $Invoice,
                                        'amount' => floatval(str_replace(',', '', $ItemAmount)) ?: 0,
                                        'payment_file' => $invoiceFilePath,
                                        'status' => $mapPaymentStatus($Payment_Status),
                                        'date' => $projectDate,
                                        'notes' => $ItemComments,
                                        'created_by' => $userId,
                                        'approved_at' => null,
                                        'approved_by' => null,
                                        'created_at' => now(),
                                        'updated_at' => now()
                                    ]);
                                    $processedInvoices[$Invoice] = true;
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        $failedCount++;
                        $errors[] = "صف " . ($index + 2) . ": " . $e->getMessage();
                    }
                }
            } catch (\Exception $e) {
                $failedCount += count($rowsGroup);
                $errors[] = "PO $poNumber: " . $e->getMessage();
            }
        }

        Illuminate\Support\Facades\DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'تم استيراد بيانات الماستر بنجاح',
            'stats' => [
                'success' => $successCount,
                'failed' => $failedCount,
                'total' => $successCount + $failedCount
            ],
            'errors' => $errors
        ]);
    } catch (\Exception $e) {
        Illuminate\Support\Facades\DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('test-pdf-download', function () {
    // الرابط الأصلي
    $url = 'https://drive.google.com/file/d/1EIAYqd9CCAMKcg69XVtJpqqnDn0RmMeP/view';

    // استخراج الـ File ID وتحويله لرابط تحميل مباشر
    preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches);
    if (isset($matches[1])) {
        $fileId = $matches[1];
        // الرابط المباشر للتحميل
        $directUrl = "https://drive.google.com/uc?export=download&id={$fileId}";
    } else {
        return 'Invalid Google Drive URL';
    }

    $ch = curl_init($directUrl);
    $fp = fopen(storage_path('app/public/test.pdf'), 'wb');

    curl_setopt_array($ch, [
        CURLOPT_FILE => $fp,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_BINARYTRANSFER => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0',
    ]);

    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    $size = filesize(storage_path('app/public/test.pdf'));
    return "Downloaded! Size: {$size} bytes - Check storage/app/public/test.pdf";
});

Route::get('view_report', function () {
    // return view('dashboard.report_pdf.invoices');

    // تشغيل أوامر الكاش
    Artisan::call('optimize:clear');

    $user = Auth::user();

    // جلب كل الصلاحيات
    $permissions = $user->getAllPermissions();

    dd($permissions->pluck('name'));
});

require __DIR__ . '/auth.php';

Route::group([
    'middleware' => ['auth']
], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


    Route::prefix('admins')->controller(AdminController::class)->group(function () {
        Route::get('/index', 'index')->name('admin.index');
        Route::get('{admin}/show', 'show')->name('admin.show');
        Route::get('/create', 'create')->name('admin.create');
        Route::post('/store', 'store')->name('admin.store');
        Route::get('{admin}/edit', 'edit')->name('admin.edit');
        Route::post('{admin}/edit_status', 'edit_status')->name('admin.edit_status');
        Route::post('{admin}/update', 'update')->name('admin.update');
        Route::post('{admin}/delete', 'delete')->name('admin.delete');
        // assign_role
        Route::post('{admin}/assign_role', 'assign_role')->name('admin.assign_role');
    });

    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('/index', 'index')->name('user.index');
        Route::get('{user}/show', 'show')->name('user.show');
        Route::get('/create', 'create')->name('user.create');
        Route::post('/store', 'store')->name('user.store');
        Route::get('{user}/edit', 'edit')->name('user.edit');
        Route::post('{user}/edit_status', 'edit_status')->name('user.edit_status');
        Route::post('{user}/update', 'update')->name('user.update');
        Route::post('{user}/delete', 'delete')->name('user.delete');
        // assign_role
        Route::post('{user}/assign_role', 'assign_role')->name('user.assign_role');
    });

    // profile
    Route::get('{user}/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('{user}/update_profile', [ProfileController::class, 'update_profile'])->name('update_profile');
    Route::post('{user}/update_profile_photo', [ProfileController::class, 'update_profile_photo'])->name('update_profile_photo');

    Route::prefix('groups')->controller(GroupController::class)->group(function () {
        Route::get('/index', 'index')->name('groups.index');
        Route::get('{group}/show', 'show')->name('groups.show');
        Route::get('/create', 'create')->name('groups.create');
        Route::post('/store', 'store')->name('groups.store');
        Route::get('{group}/edit', 'edit')->name('groups.edit');
        Route::post('{group}/update', 'update')->name('groups.update');
        Route::post('{group}/destroy', 'destroy')->name('groups.destroy');
    });

    Route::prefix('roles')->controller(RolesController::class)->group(function () {
        Route::get('/index', 'index')->name('roles.index');
        Route::get('{role}/show', 'show')->name('roles.show');
        Route::get('/create', 'create')->name('roles.create');
        Route::post('/store', 'store')->name('roles.store');
        Route::get('{role}/edit', 'edit')->name('roles.edit');
        Route::post('{role}/update', 'update')->name('roles.update');
        Route::post('{role}/delete', 'delete')->name('roles.delete');
    });


    Route::prefix('sections')->controller(SectionsController::class)->group(function () {
        Route::get('/index', 'index')->name('sections.index');
        Route::get('{section}/show', 'show')->name('sections.show');
        Route::get('/create', 'create')->name('sections.create');
        Route::post('/store', 'store')->name('sections.store');
        Route::post('/ajax-store', 'ajaxStore')->name('sections.ajax-store');
        Route::get('{section}/edit', 'edit')->name('sections.edit');
        Route::post('{section}/update', 'update')->name('sections.update');
        Route::post('{section}/destroy', 'destroy')->name('sections.destroy');
    });

    Route::prefix('section_items')->controller(SectionItemsController::class)->group(function () {
        Route::get('/index', 'index')->name('section_items.index');
        Route::get('{section_item}/show', 'show')->name('section_items.show');
        Route::get('/create', 'create')->name('section_items.create');
        Route::post('/store', 'store')->name('section_items.store');
        Route::post('/ajax-store', 'ajaxStore')->name('section_items.ajax-store');
        Route::get('{section_item}/edit', 'edit')->name('section_items.edit');
        Route::post('{section_item}/update', 'update')->name('section_items.update');
        Route::post('{section_item}/destroy', 'destroy')->name('section_items.destroy');
    });

    Route::prefix('equipments')->controller(EquipmentsController::class)->group(function () {
        Route::get('/index', 'index')->name('equipments.index');
        Route::get('{equipment}/show', 'show')->name('equipments.show');
        Route::get('/create', 'create')->name('equipments.create');
        Route::post('/store', 'store')->name('equipments.store');
        Route::get('{equipment}/edit', 'edit')->name('equipments.edit');
        Route::post('{equipment}/update', 'update')->name('equipments.update');
        Route::post('{equipment}/destroy', 'destroy')->name('equipments.destroy');
    });

    Route::prefix('projects')->controller(ProjectController::class)->group(function () {
        Route::get('/index', 'index')->name('projects.index');
        Route::get('{project}/show', 'show')->name('projects.show');
        Route::get('/create', 'create')->name('projects.create');
        Route::post('/store', 'store')->name('projects.store');
        Route::get('{project}/edit', 'edit')->name('projects.edit');
        Route::post('{project}/update', 'update')->name('projects.update');
        Route::post('{project}/destroy', 'destroy')->name('projects.destroy');
    });

    Route::prefix('project-items')->controller(ProjectItemController::class)->group(function () {
        Route::get('/index', 'index')->name('project-items.index');
        Route::get('{project_item}/show', 'show')->name('project-items.show');
        Route::get('/create', 'create')->name('project-items.create');
        Route::post('/store', 'store')->name('project-items.store');
        Route::get('{project_item}/edit', 'edit')->name('project-items.edit');
        Route::post('{project_item}/update', 'update')->name('project-items.update');
        Route::post('{project_item}/destroy', 'destroy')->name('project-items.destroy');
        Route::post('{project_item}/update-received-qty', 'updateReceivedQty')->name('project-items.update-received-qty');
        Route::post('{project_item}/update-executed-qty', 'updateExecutedQty')->name('project-items.update-executed-qty');
    });

    Route::prefix('project-teams')->controller(ProjectTeamController::class)->group(function () {
        Route::get('/index', 'index')->name('project-teams.index');
        Route::get('{project_team}/show', 'show')->name('project-teams.show');
        Route::get('/create', 'create')->name('project-teams.create');
        Route::post('/store', 'store')->name('project-teams.store');
        Route::get('{project_team}/edit', 'edit')->name('project-teams.edit');
        Route::post('{project_team}/update', 'update')->name('project-teams.update');
        Route::post('{project_team}/destroy', 'destroy')->name('project-teams.destroy');
        Route::post('/bulk-assign', 'bulkAssign')->name('project-teams.bulk-assign');
        Route::post('{project_team}/transfer', 'transfer')->name('project-teams.transfer');
        Route::get('/get-leader', 'getLeader')->name('project-teams.get-leader');
    });

    Route::prefix('project-equipments')->controller(ProjectEquipmentController::class)->group(function () {
        Route::get('/index', 'index')->name('project-equipments.index');
        Route::get('{project_equipment}/show', 'show')->name('project-equipments.show');
        Route::get('/create', 'create')->name('project-equipments.create');
        Route::post('/store', 'store')->name('project-equipments.store');
        Route::get('{project_equipment}/edit', 'edit')->name('project-equipments.edit');
        Route::post('{project_equipment}/update', 'update')->name('project-equipments.update');
        Route::post('{project_equipment}/destroy', 'destroy')->name('project-equipments.destroy');
        Route::post('/bulk-assign', 'bulkAssign')->name('project-equipments.bulk-assign');
        Route::post('{project_equipment}/update-status', 'updateStatus')->name('project-equipments.update-status');
        Route::get('available-equipment/{project_id?}', 'getAvailableEquipment')->name('project-equipments.available-equipment');
    });

    // Invoices routes
    Route::prefix('invoices')->controller(ProjectInvoicesController::class)->group(function () {
        Route::get('/index', 'index')->name('invoices.index');
        Route::get('/create', 'create')->name('invoices.create');
        Route::post('/store', 'store')->name('invoices.store');
        Route::get('{invoice}/show', 'show')->name('invoices.show');
        Route::get('{invoice}/edit', 'edit')->name('invoices.edit');
        Route::post('{invoice}/update', 'update')->name('invoices.update');
        Route::post('{invoice}/destroy', 'destroy')->name('invoices.destroy');
        Route::post('{invoice}/approve', 'approve')->name('invoices.approve');
        Route::post('{invoice}/reject', 'reject')->name('invoices.reject');
    });

    Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('notifications.index');
        Route::get('/{notification}/show', 'show')->name('notifications.show');
        Route::get('/mark-all-as-read', 'markAllAsRead')->name('notifications.markAllAsRead');
        Route::delete('/{notification}', 'destroy')->name('notifications.destroy');
    });

    // Dashboard Project Americana
    Route::get('/dashboard_amer', [DashboardAmerController::class, 'index'])->name('dashboard_amer');

    // Optional: API endpoint للحصول على البيانات بصيغة JSON
    Route::get('/dashboard_amer/stats', [DashboardAmerController::class, 'getStats'])->name('dashboard_amer.stats');

    // Brands routes
    Route::prefix('brands')->controller(BrandsController::class)->group(function () {
        Route::get('/index', 'index')->name('brands.index');
        Route::get('/create', 'create')->name('brands.create');
        Route::post('/store', 'store')->name('brands.store');
        Route::get('{brand}/show', 'show')->name('brands.show');
        Route::get('{brand}/edit', 'edit')->name('brands.edit');
        Route::post('{brand}/update', 'update')->name('brands.update');
        Route::post('{brand}/destroy', 'destroy')->name('brands.destroy');
    });

    // Brands unit routes
    Route::prefix('brand_units')->controller(BrandsUnitController::class)->group(function () {
        Route::get('/index', 'index')->name('brand_units.index');
        Route::get('/create', 'create')->name('brand_units.create');
        Route::post('/store', 'store')->name('brand_units.store');
        Route::get('{brand_unit}/show', 'show')->name(name: 'brand_units.show');
        Route::get('{brand_unit}/edit', 'edit')->name('brand_units.edit');
        Route::post('{brand_unit}/update', 'update')->name('brand_units.update');
        Route::post('{brand_unit}/destroy', 'destroy')->name('brand_units.destroy');
    });

    // stores routes
    Route::prefix('stores')->controller(StoresController::class)->group(function () {
        Route::get('/index', 'index')->name('stores.index');
        Route::get('/create', 'create')->name('stores.create');
        Route::post('/store', 'store')->name('stores.store');
        Route::get('{store}/show', 'show')->name('stores.show');
        Route::get('{store}/edit', 'edit')->name('stores.edit');
        Route::post('{store}/update', 'update')->name('stores.update');
        Route::post('{store}/destroy', 'destroy')->name('stores.destroy');
    });

    Route::prefix('project_amers')->controller(ProjectAmerController::class)->group(function () {
        Route::get('/index', 'index')->name('project_amers.index');
        Route::get('{project_amer}/show', 'show')->name('project_amers.show');
        Route::get('/create', 'create')->name('project_amers.create');
        Route::post('/store', 'store')->name('project_amers.store');
        Route::get('{project_amer}/edit', 'edit')->name('project_amers.edit');
        Route::post('{project_amer}/update', 'update')->name('project_amers.update');
        Route::post('{project_amer}/destroy', 'destroy')->name('project_amers.destroy');
        Route::get('{project_amer}/download-service-completion', 'downloadServiceCompletionPDF')->name('project_amers.download_service_completion');
        Route::get('{project_amer}/download-release-unit', 'downloadReleaseUnitPDF')->name('project_amers.download_release_unit');
        Route::get('/get-store-details/{id}', 'getStoreDetails')->name('project_amers.get_store_details');
    });

    // Project Types routes
    Route::prefix('project_types')->controller(ProjectTypeController::class)->group(function () {
        Route::get('/index', 'index')->name('project_types.index');
        Route::get('/maintenance/index', 'maintenanceIndex')->name('maintenance_types.index');
        Route::get('{type}/show', 'show')->name('project_types.show');
        Route::get('/create', 'create')->name('project_types.create');
        Route::get('/maintenance/create', 'maintenanceCreate')->name('maintenance_types.create');
        Route::post('/store', 'store')->name('project_types.store');
        Route::get('{type}/edit', 'edit')->name('project_types.edit');
        Route::get('/maintenance/{type}/edit', 'maintenanceEdit')->name('maintenance_types.edit');
        Route::match(['post', 'put'], '{type}/update', 'update')->name('project_types.update');
        Route::match(['post', 'delete'], '{type}/destroy', 'destroy')->name('project_types.destroy');
    });

    // Project Capacities routes
    Route::prefix('project_capacities')->controller(ProjectCapacityController::class)->group(function () {
        Route::get('/index', 'index')->name('project_capacities.index');
        Route::get('{capacity}/show', 'show')->name('project_capacities.show');
        Route::get('/create', 'create')->name('project_capacities.create');
        Route::post('/store', 'store')->name('project_capacities.store');
        Route::get('{capacity}/edit', 'edit')->name('project_capacities.edit');
        Route::match(['post', 'put'], '{capacity}/update', 'update')->name('project_capacities.update');
        Route::match(['post', 'delete'], '{capacity}/destroy', 'destroy')->name('project_capacities.destroy');
    });

    // Project Volts routes
    Route::prefix('project_volts')->controller(ProjectVoltController::class)->group(function () {
        Route::get('/index', 'index')->name('project_volts.index');
        Route::get('{volt}/show', 'show')->name('project_volts.show');
        Route::get('/create', 'create')->name('project_volts.create');
        Route::post('/store', 'store')->name('project_volts.store');
        Route::get('{volt}/edit', 'edit')->name('project_volts.edit');
        Route::match(['post', 'put'], '{volt}/update', 'update')->name('project_volts.update');
        Route::match(['post', 'delete'], '{volt}/destroy', 'destroy')->name('project_volts.destroy');
    });

    // Project Models routes
    Route::prefix('project_models')->controller(ProjectModelController::class)->group(function () {
        Route::get('/index', 'index')->name('project_models.index');
        Route::get('{model}/show', 'show')->name('project_models.show');
        Route::get('/create', 'create')->name('project_models.create');
        Route::post('/store', 'store')->name('project_models.store');
        Route::get('{model}/edit', 'edit')->name('project_models.edit');
        Route::match(['post', 'put'], '{model}/update', 'update')->name('project_models.update');
        Route::match(['post', 'delete'], '{model}/destroy', 'destroy')->name('project_models.destroy');
    });

    // Invoices Amer routes
    Route::prefix('invoices_amer')->controller(InvoicesAmerController::class)->group(function () {
        Route::get('/index', 'index')->name('invoices_amer.index');
        Route::get('/create', 'create')->name('invoices_amer.create');
        Route::post('/store', 'store')->name('invoices_amer.store');
        Route::get('{invoice_amer}/show', 'show')->name('invoices_amer.show');
        Route::get('{invoice_amer}/edit', 'edit')->name('invoices_amer.edit');
        Route::post('{invoice_amer}/update', 'update')->name('invoices_amer.update');
        Route::post('{invoice_amer}/destroy', 'destroy')->name('invoices_amer.destroy');
        Route::post('{invoice_amer}/approve', 'approve')->name('invoices_amer.approve');
        Route::post('{invoice_amer}/reject', 'reject')->name('invoices_amer.reject');
        Route::post('{invoice_amer}/update-status', 'updateStatus')->name('invoices_amer.update-status');
    });

    // Reports Routes
    Route::controller(ReportController::class)->prefix('reports')->name('reports.')->group(function () {
        Route::get('/index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');

        Route::get('/get-report-type-data', 'getReportTypeData')->name('getReportTypeData'); // هنا
        Route::get('/get-project-store',  'getProjectStore')->name('getProjectStore');
        Route::get('/get-project-items', 'getProjectItems')->name('getProjectItems');

        Route::get('{report}', 'show')->name('show');
        Route::get('{report}/edit', 'edit')->name('edit');
        Route::put('{report}/update', 'update')->name('update');
        Route::delete('{report}/destroy', 'destroy')->name('destroy');
        Route::get('{report}/download-pdf', 'downloadPdf')->name('download-pdf');
        Route::delete('{report}/delete-image', 'deleteImage')->name('delete-image');
    });

    // Route to fetch chart data
    Route::get('/charts-data', [DashboardController::class, 'getChartsData'])->name('charts.data');
});
