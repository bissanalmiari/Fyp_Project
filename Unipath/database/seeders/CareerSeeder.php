<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        $careers = [
            ['category_id'=>1, 'title'=>'Mechanical Engineer', 'description'=>'Designs and develops mechanical systems and machinery.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>10000, 'max_salary'=>30000 , 'image_path'=> 'images/careers-images/Mechanical Engineer.jpg'],
            ['category_id'=>1, 'title'=>'Industrial Engineer', 'description'=>'Improves processes and systems to increase efficiency.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>9000, 'max_salary'=>28000, 'image_path'=> 'images/careers-images/Industrial Engineer.jpg'],
             ['category_id'=>1, 'title'=>'Civil Engineer', 'description'=>'Plans, designs, and oversees construction and infrastructure projects.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>10000, 'max_salary'=>30000, 'image_path'=> 'images/careers-images/civil Engineer.jpg'],
            ['category_id'=>2, 'title'=>'Software Developer', 'description'=>'Designs, builds, and maintains software applications and systems.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>12000, 'max_salary'=>40000, 'image_path'=> 'images/careers-images/developer.jpg'],
            ['category_id'=>2, 'title'=>'Web Developer', 'description'=>'Builds and maintains websites using coding languages and frameworks.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>10000, 'max_salary'=>35000 , 'image_path'=> 'images/careers-images/developer.jpg'],
            ['category_id'=>2, 'title'=>'Mobile App Developer', 'description'=>'Creates and maintains applications for mobile devices.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>12000, 'max_salary'=>35000, 'image_path'=> 'images/careers-images/mobile developer.jpg'],
            ['category_id'=>2, 'title'=>'Front-End Developer', 'description'=>'Builds the user interface of websites and web apps.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>10000, 'max_salary'=>32000, 'image_path'=> 'images/careers-images/developer.jpg'],
            ['category_id'=>2, 'title'=>'Back-End Developer', 'description'=>'Develops server-side logic and databases for web applications.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>11000, 'max_salary'=>35000, 'image_path'=> 'images/careers-images/backend developer.jpg'],
            ['category_id'=>3, 'title'=>'Data Scientist', 'description'=>'Analyzes complex data to help organizations make informed decisions.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>15000, 'max_salary'=>45000, 'image_path'=> 'images/careers-images/data scientist.jpg'],
            ['category_id'=>3, 'title'=>'AI Engineer', 'description'=>'Develops AI models and machine learning systems.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>20000, 'max_salary'=>50000, 'image_path'=> 'images/careers-images/AI engineer.jpg'],
            ['category_id'=>4, 'title'=>'Physician', 'description'=>'Diagnoses and treats patients in hospitals or clinics.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>25000, 'max_salary'=>80000, 'image_path'=> 'images/careers-images/physican.jpg'],
            ['category_id'=>4, 'title'=>'Nurse', 'description'=>'Provides medical care and support to patients in healthcare settings.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>9000, 'max_salary'=>25000, 'image_path'=> 'images/careers-images/nurse.jpg'],
            ['category_id'=>4, 'title'=>'Dentist', 'description'=>'Provides oral health care and dental treatments.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>20000, 'max_salary'=>60000, 'image_path'=> 'images/careers-images/dentist.jpg'],
            ['category_id'=>6, 'title'=>'Biologist', 'description'=>'Studies living organisms and ecosystems.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>10000, 'max_salary'=>30000, 'image_path'=> 'images/careers-images/biologist.jpg'],
            ['category_id'=>6, 'title'=>'Chemist', 'description'=>'Conducts chemical research and experiments.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>9000, 'max_salary'=>28000, 'image_path'=> 'images/careers-images/chemist.jpg'],
            ['category_id'=>6, 'title'=>'Physicist', 'description'=>'Researches physical phenomena and theories.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>12000, 'max_salary'=>35000, 'image_path'=> 'images/careers-images/physicist.jpg'],
            ['category_id'=>6, 'title'=>'Environmental Scientist', 'description'=>'Analyzes environmental data and proposes solutions.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>10000, 'max_salary'=>30000, 'image_path'=> 'images/careers-images/Environmental Scientist.jpg'],
            ['category_id'=>6, 'title'=>'Mathematician', 'description'=>'Studies mathematical theories and solves quantitative problems.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>12000, 'max_salary'=>40000, 'image_path'=> 'images/careers-images/Mathematician.jpg'],
            ['category_id'=>7, 'title'=>'Social Worker', 'description'=>'Supports individuals and communities in need.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>7000, 'max_salary'=>22000, 'image_path'=> 'images/careers-images/social worker.jpg'],
            ['category_id'=>7, 'title'=>'Psychologist', 'description'=>'Studies human behavior and provides therapy.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>10000, 'max_salary'=>30000, 'image_path'=> 'images/careers-images/psychologist.jpg'],
            ['category_id'=>7, 'title'=>'Sociologist', 'description'=>'Researches social behavior and societal trends.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>9000, 'max_salary'=>28000, 'image_path'=> 'images/careers-images/social worker.jpg'],
            ['category_id'=>7, 'title'=>'Economist', 'description'=>'Analyzes economic data and trends.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>12000, 'max_salary'=>40000, 'image_path'=> 'images/careers-images/economist.jpg'],
            ['category_id'=>8, 'title'=>'Digital Marketing Specialist', 'description'=>'Manages online marketing campaigns including SEO, ads, and social media.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>7000, 'max_salary'=>22000, 'image_path'=> 'images/careers-images/digital Marketing Specialist.jpg'],
            ['category_id'=>8, 'title'=>'Business Analyst', 'description'=>'Analyzes business processes to improve performance.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>12000, 'max_salary'=>38000, 'image_path'=> 'images/careers-images/Business Analyst.jpg'],
            ['category_id'=>8, 'title'=>'Financial Analyst', 'description'=>'Analyzes financial data and helps guide investment decisions.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>12000, 'max_salary'=>40000, 'image_path'=> 'images/careers-images/Financial Analyst.jpg'],
            ['category_id'=>8, 'title'=>'Accountant', 'description'=>'Manages financial records and reporting.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>8000, 'max_salary'=>25000, 'image_path'=> 'images/careers-images/Accountant.jpg'],
            ['category_id'=>8, 'title'=>'HR Manager', 'description'=>'Oversees human resources and employee relations.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>10000, 'max_salary'=>30000, 'image_path'=> 'images/careers-images/HR Manager.jpg'],
            ['category_id'=>9, 'title'=>'Lawyer', 'description'=>'Provides legal advice and represents clients.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>15000, 'max_salary'=>50000, 'image_path'=> 'images/careers-images/lawyer.jpg'],
            ['category_id'=>9, 'title'=>'Judge', 'description'=>'Presides over legal cases and court proceedings.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>20000, 'max_salary'=>60000, 'image_path'=> 'images/careers-images/Judge.jpg'],
            ['category_id'=>9, 'title'=>'Paralegal', 'description'=>'Assists lawyers in legal research and documentation.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>7000, 'max_salary'=>20000, 'image_path'=> 'images/careers-images/Paralegal.jpg'],
            ['category_id'=>9, 'title'=>'Legal Consultant', 'description'=>'Provides expert legal advice to businesses or individuals.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>12000, 'max_salary'=>40000, 'image_path'=> 'images/careers-images/Legal Consultant.jpg'],
            ['category_id'=>9, 'title'=>'Compliance Officer', 'description'=>'Ensures companies follow laws and regulations.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>10000, 'max_salary'=>30000, 'image_path'=> 'images/careers-images/Legal Consultant.jpg'],
            ['category_id'=>10,'title'=>'Teacher','description'=>'Educates students.','is_active'=>true,'in_demand'=>true,'min_salary'=>7000,'max_salary'=>20000, 'image_path'=> 'images/careers-images/teacher.jpg'],
            ['category_id'=>10,'title'=>'Academic Advisor','description'=>'Guides students academically.','is_active'=>true,'in_demand'=>true,'min_salary'=>8000,'max_salary'=>22000, 'image_path'=> 'images/careers-images/academic advisor.jpg'],
            ['category_id'=>10,'title'=>'Education Consultant','description'=>'Provides expertise to improve educational systems.','is_active'=>true,'in_demand'=>true,'min_salary'=>10000,'max_salary'=>30000, 'image_path'=> 'images/careers-images/Education Consultant.jpg'],
            ['category_id'=>11, 'title'=>'Graphic Designer', 'description'=>'Creates visual content for branding, advertising, and digital media.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>8000, 'max_salary'=>20000, 'image_path'=> 'images/careers-images/Graphic Designer.jpg'],
            ['category_id'=>11, 'title'=>'Architect', 'description'=>'Designs buildings and oversees their construction and functionality.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>10000, 'max_salary'=>28000, 'image_path'=> 'images/careers-images/Architect.jpg'],
            ['category_id'=>11, 'title'=>'Animator', 'description'=>'Creates animations for films, games, and digital media.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>8000, 'max_salary'=>25000, 'image_path'=> 'images/careers-images/Animator.jpg'],
            ['category_id'=>11, 'title'=>'Interior Designer', 'description'=>'Designs functional and aesthetic interior spaces.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>8000, 'max_salary'=>25000, 'image_path'=> 'images/careers-images/Interior Designer.jpg'],
            ['category_id'=>11, 'title'=>'Fashion Designer', 'description'=>'Designs clothing and accessories with creative concepts.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>7000, 'max_salary'=>20000, 'image_path'=> 'images/careers-images/Fashion Designer.jpg'],
            ['category_id'=>12, 'title'=>'Historian', 'description'=>'Researches and interprets historical events and contexts.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>6000, 'max_salary'=>18000, 'image_path'=> 'images/careers-images/Historian.jpg'],
            ['category_id'=>12, 'title'=>'Linguist', 'description'=>'Studies language structure and communication.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>7000, 'max_salary'=>20000, 'image_path'=> 'images/careers-images/Linguist.jpg'],
            ['category_id'=>12, 'title'=>'Philosopher', 'description'=>'Explores fundamental questions about existence and knowledge.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>6000, 'max_salary'=>18000, 'image_path'=> 'images/careers-images/Philosopher.jpg'],
            ['category_id'=>12, 'title'=>'Writer', 'description'=>'Produces literary or journalistic content.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>5000, 'max_salary'=>15000, 'image_path'=> 'images/careers-images/Writer.jpg'],
            ['category_id'=>12, 'title'=>'Translator', 'description'=>'Converts written or spoken content between languages.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>6000, 'max_salary'=>18000, 'image_path'=> 'images/careers-images/Translator.jpg'],
            ['category_id'=>13, 'title'=>'Agronomist', 'description'=>'Works on improving agricultural productivity.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>9000, 'max_salary'=>27000, 'image_path'=> 'images/careers-images/Agronomist.jpg'],
            ['category_id'=>1, 'title'=>'Environmental Engineer', 'description'=>'Develops solutions to environmental problems and sustainability projects.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>10000, 'max_salary'=>30000, 'image_path'=> 'images/careers-images/Environmental Engineer.jpg'],
            ['category_id'=>13, 'title'=>'Wildlife Biologist', 'description'=>'Studies and protects wildlife populations and habitats.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>9000, 'max_salary'=>25000, 'image_path'=> 'images/careers-images/Wildlife Biologist.jpg'],
            ['category_id'=>13, 'title'=>'Fisheries Scientist', 'description'=>'Researches sustainable fishing practices and aquatic ecosystems.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>9000, 'max_salary'=>25000, 'image_path'=> 'images/careers-images/Fisheries Scientist.jpg'],
            ['category_id'=>13, 'title'=>'Forester', 'description'=>'Manages and conserves forested lands.', 'is_active'=>true, 'in_demand'=>true, 'min_salary'=>9000, 'max_salary'=>25000, 'image_path'=> 'images/careers-images/Forester.jpg'],
            ['category_id'=>14,'title'=>'Journalist','description'=>'Reports news.','is_active'=>true,'in_demand'=>true,'min_salary'=>7000,'max_salary'=>20000, 'image_path'=> 'images/careers-images/Journalist.jpg'],
            ['category_id'=>14,'title'=>'Content Creator','description'=>'Creates digital media content.','is_active'=>true,'in_demand'=>true,'min_salary'=>6000,'max_salary'=>18000 , 'image_path'=> 'images/careers-images/Content Creator.jpg'],
            ['category_id'=>15,'title'=>'Hotel Manager','description'=>'Manages hotel operations.','is_active'=>true,'in_demand'=>true,'min_salary'=>9000,'max_salary'=>25000 , 'image_path'=> 'images/careers-images/Hotel Manager.jpg'],
            ['category_id'=>15,'title'=>'Tour Guide','description'=>'Guides tourists.','is_active'=>true,'in_demand'=>true,'min_salary'=>6000,'max_salary'=>18000, 'image_path'=> 'images/careers-images/Tour Guide.jpg'],
            ['category_id'=>16,'title'=>'Event Manager','description'=>'Organizes events.','is_active'=>true,'in_demand'=>true,'min_salary'=>8000,'max_salary'=>22000 , 'image_path'=> 'images/careers-images/Event Manager.jpg'],
            ['category_id'=>16,'title'=>'Fitness Trainer','description'=>'Trains clients physically.','is_active'=>true,'in_demand'=>true,'min_salary'=>7000,'max_salary'=>20000, 'image_path'=> 'images/careers-images/Fitness Trainer.jpg'],
            ['category_id'=>17,'title'=>'Pilot','description'=>'Operates aircraft.','is_active'=>true,'in_demand'=>true,'min_salary'=>20000,'max_salary'=>60000, 'image_path'=> 'images/careers-images/Pilot.jpg'],
            ['category_id'=>17,'title'=>'Logistics Manager','description'=>'Manages supply chains.','is_active'=>true,'in_demand'=>true,'min_salary'=>12000,'max_salary'=>35000, 'image_path'=> 'images/careers-images/Logistics Manager.jpg'],
            ['category_id'=>18,'title'=>'Product Manager','description'=>'Leads product development.','is_active'=>true,'in_demand'=>true,'min_salary'=>15000,'max_salary'=>40000, 'image_path'=> 'images/careers-images/Product Manager.jpg'],
            ['category_id'=>18,'title'=>'Research Analyst','description'=>'Analyzes cross-field data.','is_active'=>true,'in_demand'=>true,'min_salary'=>10000,'max_salary'=>30000, 'image_path'=> 'images/careers-images/Research Analyst.jpg'],

        ];

        foreach ($careers as $career) {
            DB::table('careers')->insert(array_merge($career, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}