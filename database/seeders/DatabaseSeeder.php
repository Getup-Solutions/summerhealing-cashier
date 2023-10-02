<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Lead;
use App\Models\Role;
use App\Models\User;
use App\Models\Level;
use App\Models\Course;
use App\Models\Trainer;
use App\Models\Agegroup;
use App\Models\Facility;
use App\Models\Session;
use App\Models\Training;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Creating Roles - User, Trainer, Admin
        Role::factory()->create([
            'name' => 'User',
            'value' => 'USER_ROLE',
        ]);

        Role::factory()->create([
            'name' => 'Trainer',
            'value' => 'TRAINER_ROLE',
        ]);

        Role::factory()->create([
            'name' => 'Administrator',
            'value' => 'ADMIN_ROLE',
        ]);

        // Creating and assigning roles to Trainers and Admins
        User::factory()->create([
            'first_name' => config('admin.first_name'),
            'last_name' => config('admin.last_name'),
            'email' => config('admin.email'),
            'phone_number' => config('admin.phone_number'),
            'password' => config('admin.password'),
        ]);

        $adminUser = User::where('email', '=', config('admin.email'))->first();
        $adminUser->roles()->attach([1, 2, 3]);

        // Creating and assigning roles to Trainers and Admins
        User::factory()->create([
            'first_name' => config('user.first_name'),
            'last_name' => config('user.last_name'),
            'email' => config('user.email'),
            'phone_number' => config('user.phone_number'),
            'password' => config('user.password'),
        ]);

        $user = User::where('email', '=', config('user.email'))->first();
        $user->roles()->attach([1]);



        // User::factory()->create();



        // Populating 3 agegroups
        Agegroup::factory()->create([
            'title' => '14-18'
        ]);
        Agegroup::factory()->create([
            'title' => '18+'
        ]);
        Agegroup::factory()->create([
            'title' => 'All'
        ]);

        // Populating Levels
        Level::factory()->create([
            'name' => 'All',
            'value' => 'all',
        ]);
        Level::factory()->create([
            'name' => 'Beginners',
            'value' => 'beginners',
        ]);
        Level::factory()->create([
            'name' => 'Intermediate',
            'value' => 'intermediate',
        ]);
        Level::factory()->create([
            'name' => 'Advanced',
            'value' => 'advanced',
        ]);

        // Populating Course, Trainer and Subscription
        Course::factory(10)->create();

        for ($i = 0; $i < 10; $i++) {
            $trainer = Trainer::factory()->create();
            $trainer->user->roles()->attach([1, 2]);
        }

        Subscription::factory(10)->create();

        // Connecting many-to-many relations to Subscriptions and Courses
        $subscriptions = Subscription::all();

        Course::all()->random(5)->each(function ($course) use ($subscriptions) {
            $course->subscriptions()->attach(
                $subscriptions->random(rand(1, 10))->pluck('id')->toArray(),
                ['course_price' => 12.5]
            );
        });

        // Populating Sessions
        Session::factory(10)->create();

        Session::all()->random(5)->each(function ($session) use ($subscriptions) {
            $session->subscriptions()->attach(
                $subscriptions->random(rand(1, 10))->pluck('id')->toArray(),
                ['session_price' => 12.5]
            );
        });

        // Connecting many-to-many relations to Courses and Trainers
        $trainers = Trainer::all();

        Course::all()->random(10)->each(function ($course) use ($trainers) {
            $course->trainers()->attach(
                $trainers->random(rand(1, 10))->pluck('id')->toArray()
            );
        });

        Session::all()->random(10)->each(function ($session) use ($trainers) {
            $session->trainers()->attach(
                $trainers->random(rand(1, 10))->pluck('id')->toArray()
            );
        });


        Lead::factory(10)->create();

        Facility::factory(10)->create();
        Facility::all()->random(5)->each(function ($facility) use ($subscriptions) {
            $facility->subscriptions()->attach(
                $subscriptions->random(rand(1, 10))->pluck('id')->toArray(),
                ['facility_price' => 12.5]
            );
        });

        Training::factory(10)->create();


    }
}