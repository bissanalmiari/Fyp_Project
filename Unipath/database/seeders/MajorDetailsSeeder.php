<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;

class MajorDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $majorDetails = [
            'Computer Science' => [
                'slug' => 'computer-science',
                'details_image' => 'cs2.png',
                'short_description' => 'Computer Science focuses on programming, problem-solving, and how software systems work.',
                'what_you_study' => 'Students usually study programming, algorithms, databases, software development, and computer systems.',
                'career_paths' => 'This major can lead to careers in software development, web development, data-related fields, and technology companies.',
            ],

            'Computer Engineering' => [
                'slug' => 'computer-engineering',
                'details_image' => 'cce2.png',
                'short_description' => 'Computer Engineering combines computing with hardware and electronic systems.',
                'what_you_study' => 'Students often study digital logic, computer architecture, embedded systems, electronics, and programming.',
                'career_paths' => 'This major can lead to careers in hardware systems, embedded systems, robotics, and technical engineering roles.',
            ],

            'Data Science' => [
                'slug' => 'data-science',
                'details_image' => 'Data-science2.png',
                'short_description' => 'Data Science focuses on analyzing data, discovering patterns, and using technology to support decisions.',
                'what_you_study' => 'Students often study statistics, programming, machine learning, data analysis, and visualization.',
                'career_paths' => 'This major can lead to careers in data analysis, machine learning, business intelligence, and AI-related roles.',
            ],

            'Cybersecurity' => [
                'slug' => 'cybersecurity',
                'details_image' => 'cybersecurity2.png',
                'short_description' => 'Cybersecurity focuses on protecting systems, networks, and digital information from threats.',
                'what_you_study' => 'Students usually study network security, ethical hacking, system protection, cryptography, and digital risk management.',
                'career_paths' => 'This major can lead to careers in security analysis, network protection, ethical hacking, and information security.',
            ],

            'Business Administration' => [
                'slug' => 'business-administration',
                'details_image' => 'business-administration2.png',
                'short_description' => 'Business Administration focuses on management, planning, organization, and how businesses operate.',
                'what_you_study' => 'Students often study management, marketing, operations, accounting, and entrepreneurship.',
                'career_paths' => 'This major can lead to careers in management, administration, entrepreneurship, and business operations.',
            ],

            'Marketing' => [
                'slug' => 'marketing',
                'details_image' => 'Marketing2.png',
                'short_description' => 'Marketing focuses on understanding customers, promoting ideas, and building strong brands.',
                'what_you_study' => 'Students often study branding, advertising, consumer behavior, digital marketing, and market research.',
                'career_paths' => 'This major can lead to careers in digital marketing, branding, sales, advertising, and social media strategy.',
            ],

            'Finance' => [
                'slug' => 'finance',
                'details_image' => 'Finance2.png',
                'short_description' => 'Finance focuses on money management, financial planning, and investment decisions.',
                'what_you_study' => 'Students usually study accounting basics, investment, budgeting, financial analysis, and corporate finance.',
                'career_paths' => 'This major can lead to careers in banking, financial analysis, investment, and business finance roles.',
            ],

            'Economics' => [
                'slug' => 'economics',
                'details_image' => 'Economics2.png',
                'short_description' => 'Economics studies how people, businesses, and governments use resources and make decisions.',
                'what_you_study' => 'Students often study microeconomics, macroeconomics, economic policy, markets, and data analysis.',
                'career_paths' => 'This major can lead to careers in research, policy, consulting, banking, and economic analysis.',
            ],

            'Nursing' => [
                'slug' => 'nursing',
                'details_image' => 'Nursing2.png',
                'short_description' => 'Nursing focuses on patient care, health support, and improving wellbeing.',
                'what_you_study' => 'Students usually study anatomy, patient care, clinical practice, health assessment, and medical support skills.',
                'career_paths' => 'This major can lead to careers in hospitals, clinics, community health, and patient care environments.',
            ],

            'Psychology' => [
                'slug' => 'psychology',
                'details_image' => 'psychology2.png',
                'short_description' => 'Psychology focuses on understanding thoughts, behavior, emotions, and mental processes.',
                'what_you_study' => 'Students often study human behavior, development, cognition, mental health, and research methods.',
                'career_paths' => 'This major can lead to careers in counseling support, human services, research, and educational settings.',
            ],

            'Education' => [
                'slug' => 'education',
                'details_image' => 'Education2.png',
                'short_description' => 'Education focuses on teaching, learning, and helping students grow academically and personally.',
                'what_you_study' => 'Students usually study teaching methods, communication, child development, classroom practice, and learning theory.',
                'career_paths' => 'This major can lead to careers in teaching, training, educational support, and academic guidance.',
            ],

            'Biology' => [
                'slug' => 'biology',
                'details_image' => 'Biology2.png',
                'short_description' => 'Biology is the study of living organisms, life processes, and the natural world.',
                'what_you_study' => 'Students often study genetics, microbiology, anatomy, ecology, cell biology, and laboratory science.',
                'career_paths' => 'This major can lead to careers in research, laboratories, healthcare support, environmental science, and biotechnology.',
            ],

            'Graphic Design' => [
                'slug' => 'graphic-design',
                'details_image' => 'graphic-design2.png',
                'short_description' => 'Graphic Design focuses on visual communication, creative ideas, and digital design work.',
                'what_you_study' => 'Students usually study branding, typography, layout design, digital tools, and visual storytelling.',
                'career_paths' => 'This major can lead to careers in branding, design studios, digital content, advertising, and visual media.',
            ],

            'Mathematics' => [
                'slug' => 'mathematics',
                'details_image' => 'maths2.png',
                'short_description' => 'Mathematics focuses on logic, patterns, structure, and solving analytical problems.',
                'what_you_study' => 'Students often study calculus, algebra, statistics, probability, and applied mathematical methods.',
                'career_paths' => 'This major can lead to careers in data analysis, education, research, finance, and analytical problem-solving fields.',
            ],

            'Architecture' => [
                'slug' => 'architecture',
                'details_image' => 'architecture2.png',
                'short_description' => 'Architecture combines creativity and structure to design buildings, spaces, and environments.',
                'what_you_study' => 'Students often study architectural design, drawing, structures, materials, space planning, and design software.',
                'career_paths' => 'This major can lead to careers in architectural design, urban planning, interior-related work, and creative structural design.',
            ],

            'Multimedia Design' => [
                'slug' => 'multimedia-design',
                'details_image' => 'multimedia-design2.png',
                'short_description' => 'Multimedia Design focuses on digital creativity, combining visuals, motion, interaction, and storytelling.',
                'what_you_study' => 'Students often study digital design, animation, video editing, interactive media, user experience, and creative software tools.',
                'career_paths' => 'This major can lead to careers in multimedia production, motion graphics, digital content creation, UX-related design, and creative media.',
            ],

            'Software Engineering' => [
                'slug' => 'software-engineering',
                'details_image' => 'software2.png',
                'short_description' => 'Software Engineering focuses on designing, building, testing, and maintaining software systems.',
                'what_you_study' => 'Students often study software design, programming, databases, testing, system analysis, and software development methodologies.',
                'career_paths' => 'This major can lead to careers in software development, backend development, mobile apps, quality assurance, and software architecture.',
            ],

            'Mechanical Engineering' => [
                'slug' => 'mechanical-engineering',
                'details_image' => 'mechanical2.png',
                'short_description' => 'Mechanical Engineering focuses on machines, motion, mechanics, and how physical systems work.',
                'what_you_study' => 'Students often study mechanics, thermodynamics, machine design, materials, manufacturing, and engineering analysis.',
                'career_paths' => 'This major can lead to careers in machine design, manufacturing, maintenance, automotive systems, and industrial engineering roles.',
            ],

            'Electrical Engineering' => [
                'slug' => 'electrical-engineering',
                'details_image' => 'Electrical2.png',
                'short_description' => 'Electrical Engineering focuses on circuits, power systems, electronics, and electrical technologies.',
                'what_you_study' => 'Students often study circuit analysis, electronics, power systems, control systems, signals, and electrical design.',
                'career_paths' => 'This major can lead to careers in power systems, electronics, control engineering, telecommunications, and electrical design roles.',
            ],

            'Industrial Engineering' => [
                'slug' => 'industrial-engineering',
                'details_image' => 'Industrial2.png',
                'short_description' => 'Industrial Engineering focuses on optimizing systems, processes, and efficiency in real-world operations.',
                'what_you_study' => 'Students often study operations research, systems optimization, production systems, statistics, and process improvement.',
                'career_paths' => 'This major can lead to careers in operations management, logistics, supply chain, process engineering, and system optimization roles.',
            ],

            'Dentistry' => [
                'slug' => 'dentistry',
                'details_image' => 'Dentist2.png',
                'short_description' => 'Dentistry focuses on oral health, dental care, and diagnosing problems related to teeth and gums.',
                'what_you_study' => 'Students often study anatomy, oral biology, dental materials, diagnosis, and clinical dental practice.',
                'career_paths' => 'This major can lead to careers in dental clinics, hospitals, oral health centers, and private dental practice.',
            ],

            'Pharmacy' => [
                'slug' => 'pharmacy',
                'details_image' => 'Pharmacy2.png',
                'short_description' => 'Pharmacy focuses on medicines, drug safety, and helping patients use treatments correctly.',
                'what_you_study' => 'Students often study pharmacology, chemistry, drug interactions, medical treatments, and patient counseling.',
                'career_paths' => 'This major can lead to careers in pharmacies, hospitals, pharmaceutical companies, and healthcare support roles.',
            ],

            'Medical Laboratory Sciences' => [
                'slug' => 'medical-laboratory-sciences',
                'details_image' => 'Medlab2.png',
                'short_description' => 'Medical Laboratory Sciences focuses on lab testing, medical analysis, and supporting diagnosis through science.',
                'what_you_study' => 'Students often study microbiology, hematology, clinical chemistry, lab techniques, and diagnostic testing.',
                'career_paths' => 'This major can lead to careers in medical labs, hospitals, diagnostic centers, and healthcare research.',
            ],

            'Interior Design' => [
                'slug' => 'interior-design',
                'details_image' => 'Interior2.png',
                'short_description' => 'Interior Design focuses on planning and designing indoor spaces that are functional and visually appealing.',
                'what_you_study' => 'Students often study space planning, materials, lighting, furniture design, color theory, and design software.',
                'career_paths' => 'This major can lead to careers in interior design firms, architecture offices, furniture design, and space planning.',
            ],

            'Physics' => [
                'slug' => 'physics',
                'details_image' => 'Physics2.png',
                'short_description' => 'Physics focuses on understanding matter, energy, motion, forces, and how the universe works.',
                'what_you_study' => 'Students often study mechanics, electricity, waves, thermodynamics, quantum physics, and experimental methods.',
                'career_paths' => 'This major can lead to careers in research, education, engineering support, data analysis, and laboratory work.',
            ],

            'Chemistry' => [
                'slug' => 'chemistry',
                'details_image' => 'Chemistry2.png',
                'short_description' => 'Chemistry focuses on substances, reactions, materials, and how matter changes.',
                'what_you_study' => 'Students often study organic chemistry, analytical chemistry, physical chemistry, lab methods, and chemical reactions.',
                'career_paths' => 'This major can lead to careers in laboratories, pharmaceuticals, environmental testing, research, and quality control.',
            ],

            'Social Sciences' => [
                'slug' => 'social-sciences',
                'details_image' => 'Social2.png',
                'short_description' => 'Social Sciences focuses on society, human relationships, culture, and social behavior.',
                'what_you_study' => 'Students often study social research, politics, culture, human behavior, communication, and social institutions.',
                'career_paths' => 'This major can lead to careers in research, social work support, NGOs, public service, education, and community development.',
            ],

            'Sociology' => [
                'slug' => 'sociology',
                'details_image' => 'Sociology2.png',
                'short_description' => 'Sociology focuses on how people live in groups, how societies work, and how social issues develop.',
                'what_you_study' => 'Students often study social behavior, inequality, culture, family, communities, research methods, and social change.',
                'career_paths' => 'This major can lead to careers in research, community work, social services, education, NGOs, and public policy support.',
            ],
        ];

        foreach ($majorDetails as $majorName => $details) {
            Major::where('name', $majorName)->update($details);
        }
    }
}