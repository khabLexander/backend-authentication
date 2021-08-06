<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $this->createProjectsAuthors1();
        $this->createProjectsAuthors2();
    }

    function createProjectsAuthors1()
    {
        Project::factory(10)
            ->hasAuthors(3, [
                'age' => 20,
            ])
            ->create([
                'approved' => true
            ]);
    }

    function createProjectsAuthors2()
    {
        $projects = Project::factory(10)->create();

        foreach ($projects as $project) {
            Author::factory()
                ->count(3)
                ->for($project)
                ->hasPosts(3)
                ->create();
        }
    }
}
