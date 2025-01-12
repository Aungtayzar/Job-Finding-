<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Load Job listing from the file
        $joblistings = include (database_path('seeders/data/job_listings.php'));

        //get test User Id
        $testUserId = User::where('email','test@test.com')->value('id');

        //get all other use ids from user model
        $userIds = User::where('email','!=','test@test.com')->pluck('id')->toArray();

        foreach ($joblistings as $index=>&$listing){

            if($index < 2){
                //Assign test user id to first two job listing
                $listing['user_id'] = $testUserId;
            }else{
                //Assign user id to listing
                $listing['user_id'] = $userIds[array_rand($userIds)];
            }

            

            //Add timestamps 
            $listing['created_at'] = now();
            $listing['updated_at'] = now();

        }

        //Insert job listings
        DB::table('job_listings')->insert($joblistings);
        echo 'Jobs created successfully!';
    }
}
