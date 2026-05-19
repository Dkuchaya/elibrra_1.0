<?php

namespace App\Console\Commands;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Publisher;
use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\User;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MigrateOldElibraryData extends Command
{
    protected $signature = 'elibrary:migrate-old-data {--default-password=password123}';
    protected $description = 'Migrate old CodeIgniter eLibrary data into the new Laravel structure.';

    public function handle(): int
    {
        $old = DB::connection('old_mysql');
        $defaultPassword = $this->option('default-password');

        $this->info('Migrating schools from old user school data...');
        $oldUsers = $old->table('users')->get();

        $schoolMap = [];
        foreach ($oldUsers->where('school_id', '!=', '') as $oldUser) {
            $legacySchoolId = trim((string) $oldUser->school_id);
            if ($legacySchoolId === '') {
                continue;
            }

            $name = trim((string) ($oldUser->school_name ?: $oldUser->first_name.' '.$oldUser->last_name));
            $name = $name ?: 'School '.$legacySchoolId;

            $school = School::firstOrCreate(
                ['legacy_school_id' => $legacySchoolId],
                [
                    'name' => $name,
                    'slug' => Str::slug($name).'-'.substr($legacySchoolId, -6),
                    'is_active' => true,
                ]
            );

            $schoolMap[$legacySchoolId] = $school->id;
        }

        $this->info('Migrating users...');
        foreach ($oldUsers as $oldUser) {
            $email = trim((string) $oldUser->email);
            if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email = 'legacy-user-'.$oldUser->id.'@example.local';
            }

            $schoolId = null;
            $legacySchoolId = trim((string) $oldUser->school_id);
            if ($legacySchoolId && isset($schoolMap[$legacySchoolId])) {
                $schoolId = $schoolMap[$legacySchoolId];
            }

            $user = User::updateOrCreate(
                ['legacy_user_id' => $oldUser->id],
                [
                    'school_id' => $schoolId,
                    'first_name' => $oldUser->first_name ?: null,
                    'last_name' => $oldUser->last_name ?: null,
                    'name' => trim(($oldUser->first_name ?? '').' '.($oldUser->last_name ?? '')) ?: $email,
                    'email' => $email,
                    'phone' => $oldUser->phone_number ?: null,
                    'gender' => $oldUser->gender ?: null,
                    'city' => $oldUser->city ?: null,
                    'password' => Hash::make($defaultPassword),
                    'must_change_password' => true,
                    'is_active' => (int) $oldUser->status === 1,
                ]
            );

            if ($schoolId) {
                $user->syncRoles(['Reader']);
            } else {
                $user->syncRoles(['Individual User/Student']);
            }

            if (! empty($oldUser->expiration_date)) {
                try {
                    $start = ! empty($oldUser->add_date) ? Carbon::parse($oldUser->add_date) : now();
                    $end = Carbon::parse($oldUser->expiration_date);

                    if ($schoolId) {
                        SchoolSubscription::firstOrCreate([
                            'school_id' => $schoolId,
                            'starts_at' => $start->toDateString(),
                            'expires_at' => $end->toDateString(),
                        ]);
                    } else {
                        UserSubscription::firstOrCreate([
                            'user_id' => $user->id,
                            'starts_at' => $start->toDateString(),
                            'expires_at' => $end->toDateString(),
                        ]);
                    }
                } catch (\Throwable $e) {
                    $this->warn('Invalid subscription date for user ID '.$oldUser->id);
                }
            }
        }

        $this->info('Migrating publishers...');
        foreach ($old->table('publisher')->get() as $oldPublisher) {
            Publisher::firstOrCreate(
                ['legacy_publisher_id' => $oldPublisher->publisherid],
                ['name' => trim($oldPublisher->publisher_name) ?: 'Unknown Publisher']
            );
        }

        $this->info('Migrating books, categories, and authors...');
        foreach ($old->table('books')->get() as $oldBook) {
            $category = null;
            if (! empty($oldBook->book_category)) {
                $category = BookCategory::firstOrCreate(['name' => trim($oldBook->book_category)]);
            }

            $publisher = null;
            if (! empty($oldBook->publisherid) && is_numeric($oldBook->publisherid)) {
                $publisher = Publisher::where('legacy_publisher_id', (int) $oldBook->publisherid)->first();
            }
            if (! $publisher && ! empty($oldBook->book_publisher)) {
                $publisher = Publisher::firstOrCreate(['name' => trim($oldBook->book_publisher)]);
            }

            $book = Book::updateOrCreate(
                ['legacy_isbn' => $oldBook->book_isbn],
                [
                    'publisher_id' => $publisher?->id,
                    'book_category_id' => $category?->id,
                    'isbn' => $oldBook->book_isbn ?: null,
                    'title' => $oldBook->book_title ?: 'Untitled Book',
                    'description' => $oldBook->book_descr ?: null,
                    'cover_image' => $oldBook->book_image ?: null,
                    'pdf_path' => $oldBook->book_url ?: null,
                    'visibility' => 'school',
                    'is_active' => true,
                ]
            );

            $authorNames = array_filter(array_map('trim', preg_split('/,| and |&/i', (string) $oldBook->book_author)));
            foreach ($authorNames as $authorName) {
                if (strtolower($authorName) === 'null' || $authorName === '') {
                    continue;
                }
                $author = Author::firstOrCreate(['name' => $authorName]);
                $book->authors()->syncWithoutDetaching([$author->id]);
            }
        }

        $this->info('Old eLibrary data migration completed.');
        return self::SUCCESS;
    }
}
