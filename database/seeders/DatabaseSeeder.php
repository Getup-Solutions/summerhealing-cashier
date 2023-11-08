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
use App\Models\Calendar;
use App\Models\Day;
use App\Models\Facility;
use App\Models\Session;
use App\Models\Training;
use App\Models\Subscriptionplan;
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

        Trainer::create(['user_id' => $adminUser->id]);

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

        // Populating Course, Trainer and Subscriptionplan
        Course::factory(10)->create();

        // for ($i = 0; $i < 10; $i++) {
        //     $trainer = Trainer::factory()->create();
        //     $trainer->user->roles()->attach([1, 2]);
        // }

        // Subscriptionplan::factory()->create();

        // Connecting many-to-many relations to Subscriptionplans and Courses
        // $subscriptionplans = Subscriptionplan::all();

        // Course::all()->random(5)->each(function ($course) use ($subscriptionplans) {
        //     $course->subscriptionplans()->attach(
        //         $subscriptionplans->random(rand(1, 10))->pluck('id')->toArray(),
        //         ['course_price' => 12.5]
        //     );
        // });

        // Populating Sessions
        // Session::factory(10)->create();

        // Session::all()->random(5)->each(function ($session) use ($subscriptionplans) {
        //     $session->subscriptionplans()->attach(
        //         $subscriptionplans->random(rand(1, 10))->pluck('id')->toArray(),
        //         ['session_price' => 12.5]
        //     );
        // });

        // Connecting many-to-many relations to Courses and Trainers
        // $trainers = Trainer::all();

        // Course::all()->random(10)->each(function ($course) use ($trainers) {
        //     $course->trainers()->attach(
        //         $trainers->random(rand(1, 10))->pluck('id')->toArray()
        //     );
        // });

        // Session::all()->random(10)->each(function ($session) use ($trainers) {
        //     $session->trainers()->attach(
        //         $trainers->random(rand(1, 10))->pluck('id')->toArray()
        //     );
        // });


        // Lead::factory(10)->create();

        Facility::factory(10)->create();
        // Facility::all()->random(5)->each(function ($facility) use ($subscriptionplans) {
        //     $facility->subscriptionplans()->attach(
        //         $subscriptionplans->random(rand(1, 10))->pluck('id')->toArray(),
        //         ['facility_price' => 12.5]
        //     );
        // });

        // Training::factory(10)->create();



        // Seeding calendar
        for ($i = 0; $i < 365; $i++) {
            Calendar::create([
                'formated_date' => date("Y/m/d", strtotime("today + " . $i . " day")),
                'day' => date("d", strtotime("today + " . $i . " day")),
                'day_name' => date("l", strtotime("today + " . $i . " day")),
                'month_name' => date("F", strtotime("today + " . $i . " day")),
                // 'is_today'=> date("Y/m/d",strtotime("today + ".$i." day")) === date("Y/m/d",strtotime("today"))
            ]);
        }

        //Seeding Days
        $days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        foreach ($days as $day) {
            Day::create([
                'day_name' => $day
            ]);
        }

        //seeding classes or sessions

        $sessions = [
            [
                "title" => "Vinyasa Flow",
                "description" => "Image result for infrared sauna benefits
                    The benefits of infrared saunas include helping relieve inflammation, stiffness and soreness by increasing blood circulation and allowing the deep, penetrating infrared heat to relax muscles and carry off metabolic waste products, while delivering oxygen-rich blood to the muscles for a faster recovery.
                    The variable nature of Vinyasa Yoga helps to develop a more balanced body as well as prevent repetitive motion injuries that can happen if you are always doing the same thing every day.",
                "excerpt" => "The variable nature of Vinyasa Yoga helps to develop a more balanced body as well as prevent repetitive motion injuries that can happen if you are always doing the same thing every day.",
                "price" => "25",
            ],
            [
                "title" => "Infra-Red Sauna",
                "description" => "Fits 1-2 people
                        Second sauna fits 5
                        For more times text 0432293294 your prefered booking
                        The benefits of using an infrared sauna are similar to those experienced with a traditional sauna. These include:
                        better sleep
                        relaxation
                        detoxification
                        weight loss
                        relief from sore muscles
                        relief from joint pain such as arthritis
                        clear and tighter skin
                        improved circulation
                        help for people with chronic fatigue syndrome
                        People have been using saunas for centuries for all sorts of health conditions. While there are several studies and research on traditional saunas, there aren’t as many studies that look specifically at infrared saunas:",
                "excerpt" => "Fits 1-2 people
                        Second sauna fits 5
                        For more times text 0432293294 your prefered booking
                        The benefits of using an infrared sauna are similar to those experienced with a traditional sauna. ",
                "price" => "40",
            ],
            [
                "title" => "Prana Vinyasa",
                "description" => "Prana Flow Yoga is a liberating, evolutionary and rhythmic flow class that encompasses chanting, and breathing control. This type of yoga is set to music, Prana Flow Yoga is an energetic flowing yoga. The aim is a direct experience of prana, or life-energy.",
                "excerpt" => "Prana Flow Yoga is a liberating, evolutionary and rhythmic flow class that encompasses chanting, and breathing control.",
                "price" => "30",
            ],
            [
                "title" => "Reiki and Restorative Yoga",
                "description" => "Restorative yoga is a style of yoga that encourages physical, mental, and emotional relaxation. Appropriate for all levels, restorative yoga is practiced at a slow pace, focusing on long holds, stillness, and deep breathing and Reiki energy healing

                            Unlike more active yoga styles such as vinyasa or Bikram, you can expect to hold a pose for 5 minutes or more, only performing a handful of poses in one restorative yoga session.
                            
                            Read on to learn more about restorative yoga, poses to try, and the benefits of this gentle style of yoga.
                            
                            What is restorative yoga?
                            Gentle, supportive, and therapeutic are just a few words that describe restorative yoga. At its core, restorative yoga is a practice of passive healing.
                            
                            This yoga style is known for its ability to activate the parasympathetic nervous system",
                "excerpt" => "Restorative yoga is a style of yoga that encourages physical, mental, and emotional relaxation. Appropriate for all levels, restorative yoga is practiced at a slow pace, focusing on long holds, stillness, and deep breathing and Reiki energy healing",
                "price" => "30",
            ],
            [
                "title" => "Yin Yoga & Sound Journey",
                "description" => "Benefits of a regular Yin yoga practice:
                                - Calms and balances the mind and body
                                - Reduces stress and anxiety
                                - Increases circulation
                                - Improves flexibility
                                - Releases fascia and improves joint mobility
                                - Balances the internal organs and improves the flow of chi or prana
                                
                                While “yang” yoga focuses on your muscles, yin yoga targets your deep connective tissues, like your fascia, ligaments, joints, and bones. It’s slower and more meditative, giving you space to turn inward and tune into both your mind and the physical sensations of your body. Because you’re holding poses for a longer period of time than you would in other traditional types of yoga, yin yoga helps you stretch and lengthen those rarely-used tissues while also teaching you how to breathe through discomfort and sit with your thoughts.
                                
                                The practice of yin yoga is based on ancient Chinese philosophies and Taoist principles which believe there are pathways of Qi (energy) that run through our bodies. By stretching and deepening into poses, we’re opening up any blockages and releasing that energy to flow freely.",
                "excerpt" => "The practice of yin yoga is based on ancient Chinese philosophies and Taoist principles which believe there are pathways of Qi (energy) that run through our bodies. By stretching and deepening into poses, we’re opening up any blockages and releasing that energy to flow freely.",
                "price" => "30",
            ],
            [
                "title" => "Pump Weights Class",
                "description" => "This is a weights class, we use the barbell. Feel free to join whether you are new to weight training or advanced. It is a dynamic cardio and strength training class",
                "excerpt" => "This is a weights class, we use the barbell. ",
                "price" => "30",
            ],
            [
                "title" => "Ice Bath and Infra Red Sauna Class",
                "description" => "This Class involves
                                30 minute infra red sauna
                                5 minute ice bath
                                30 min breathwork and meditation
                                
                                Regular cold exposure has the ability to help reduce stress, strengthen the immune system, improve body composition, improve cardiovascular fitness, and elevate mood, attention and mental clarity.
                                
                                Just half an hour of contrast water therapy can aid you to: reduce swelling, increase energy and focus, create mental and physical resilience, reduce lethargy, decrease soreness and inflammation and combat stress.
                               
                                This practice of immersing either one particular part or even the whole body in oppositely temperated water has been around for thousands of years but has risen to fame in recent years due to the advocacy of the elite sporting population.
                                
                                By repeatedly submerging body parts or the whole body into hot and cold baths at a set temperature for a specific amount of time, alternating at a specific rate and for a set number of intervals, this method is argued to assist in alleviating circulation-related health issues by opening and closing the blood vessels to achieve a form of pumping effect through the body.",
                "excerpt" => "This Class involves 30 minute infra red sauna,5 minute ice bath,30 min breathwork and meditation",
                "price" => "30",
            ],
        ];


        foreach ($sessions as $session) {
            Session::create([
                "title" => $session["title"],
                "description" => $session["description"],
                "excerpt" => $session["excerpt"],
                "price" => $session["price"],
                // "agegroup" => null,
                // "size" => null,
                // "level" => null,
                "slug" => preg_replace('/[^A-Za-z0-9-]+/', '-', $session["title"]),
            ]);
        }

        //seeding subscription plans

        // $subscriptionplans = [
        //     [
        //         "title" => "Silver Intoduction",
        //         "description" => "UNLIMITED YOGA CLASSES , 20% OFF WELLNESS CENTRE",
        //         "validity" => "1 Month",
        //         "price" => "$59",
        //     ],
        //     [
        //         "title" => "Crown  Introduction",
        //         "description" => "ONE MONTH INTRO MEMBERSHIP,UNLIMITED YOGA, REBIRTHING BREATHWORK, AERIAL YOGA, SOUND MEDITATION, 1X INFRA RED SAUNA A WEEK, 20% OFF WELLNESS CENTRE",
        //         "validity" => "1 Month",
        //         "price" => "$159",
        //     ],
        //     [
        //         "title" => "Gold Membership",
        //         "description" => "MINIMUM 2 MONTHS, UNLIMITED YOGA CLASSES, 20% OFF WELLNESS PROGRAM",
        //         "validity" => "2 Months",
        //         "price" => "$30 per week",
        //     ],
        //     [
        //         "title" => "Crown Membership",
        //         "description" => " MINIMUM 2 MONTHS, UNLIMITED YOGA, REBIRTHING BREATHWORK ,AERIAL YOGA, SOUND MEDITATION, SHAMANIC  1x INFRA-RED SAUNAS PER WEEK, WELLNESS PROGRAM 50% OFF - massage, chakra bed, one on one yoga, rebirthing breathwork ",
        //         "validity" => "2 Months",
        //         "price" => "$40 per week",
        //     ],
        //     [
        //         "title" => "Concession Yogi Membership",
        //         "description" => "  MINIMUM 2 MONTHS, UNLIMITED YOGA CLASSES, WELLNESS PROGRAM 20% OFF",
        //         "validity" => "2 Months",
        //         "price" => "$25 per week",
        //     ],
        //     [
        //         "title" => "Live Stream And Video On Demand",
        //         "description" => "LIVE STREAM AND VIDEO ON DEMAND, CHOOSE ANY CLASS ",
        //         "validity" => "2 Months",
        //         "price" => "$10 per week",
        //     ],
        //     [
        //         "title" => "10 Visit Pass ",
        //         "description" => "10 VISIT PASS CONTRIBUTION EXPIRES IN 12 MONTHS",
        //         "validity" => "12 Months",
        //         "price" => "$210",
        //     ],
        // ];
    }
}
