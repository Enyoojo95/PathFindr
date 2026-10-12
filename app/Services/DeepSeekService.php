<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepSeekService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.deepseek.key');
        $this->baseUrl = rtrim(config('services.deepseek.base_url', 'https://api.deepseek.com'), '/');
    }

    /**
     * Get AI-powered career recommendations for a student.
     *
     * @param  float  $numerical   0–100 score
     * @param  float  $verbal      0–100 score
     * @param  float  $spatial     0–100 score
     * @param  float  $interest    0–100 engagement score
     * @return array{
     *   combination: string,
     *   careers: array<string>,
     *   universities: array<array{name: string, course: string}>,
     *   message: string,
     *   source: string
     * }
     */
    public function getRecommendation(
        float $numerical,
        float $verbal,
        float $spatial,
        float $interest
    ): array {
        if (($numerical + $verbal + $spatial + $interest) / 4 <= 50) {
            $recommendation = $this->fallback($numerical, $verbal, $spatial);
            $recommendation['careers'] = [];
            $recommendation['universities'] = [];
            $recommendation['message'] = 'Your current scores suggest focusing on strengthening your core skills first. Keep practising and exploring your interests; career and university suggestions will be more useful after your scores improve.';

            return $recommendation;
        }

        $systemPrompt = <<<'PROMPT'
You are a highly experienced Nigerian secondary school career counsellor with deep knowledge of
the Nigerian educational system (WAEC, NECO, JAMB) and Nigerian university admissions. Your job is to
analyse a JSS3 student's psychometric test scores and recommend the most suitable SS1 subject
combination, career paths, Nigerian universities, and a motivational message.

Always respond with ONLY valid JSON — no markdown, no code blocks, no extra commentary.
The JSON must follow this exact structure:
{
  "combination": "Science" | "Commercial" | "Arts",
  "careers": ["Career 1", "Career 2", "Career 3", "Career 4", "Career 5"],
  "universities": [
    {"name": "University Name", "course": "Recommended Course"},
        {"name": "...", "course": "..."}
  ],
  "message": "A warm, encouraging 2-3 sentence message in a friendly Nigerian tone."
}

Guidelines:
- Return 8-10 distinct Nigerian universities, with one suitable course for each; do not repeat a university.
- Match every course to the student's likely subject preparation and career direction. Give a balanced shortlist across federal, state, and technology-focused universities where relevant.
- For strong Science profiles with numerical and spatial scores of at least 60%, rigorously prioritize engineering fit: recommend relevant engineering courses and universities known to offer them, and explain the fit through the career choices. Consider Computer, Civil, Electrical/Electronic, Mechanical, Chemical, and related engineering disciplines where the scores support them.
- Use established Nigerian universities (for example, UNILAG, OAU, ABU, UNN, UNIBEN, UNILORIN, UI, FUTA, FUTO, FUT Minna, and UNIPORT). Do not invent institutions or claim admission eligibility; requirements can change, so avoid unsupported specific admission claims.
- Careers must be specific and realistic for Nigeria (e.g. "Software Engineer", not just "Engineer")
- The combination must match: Science (high numerical+spatial), Commercial (high numerical+verbal), Arts (high verbal+interest)
- If two student profiles fall in the same pathway, choose different but still relevant careers and universities based on which score is strongest and how far apart the scores are; do not repeat the same static list for similar students.
- The motivational message must feel personal, warm, and Nigerian in tone
- Do NOT include markdown, backticks, or any text outside the JSON object
PROMPT;

        $userMessage = sprintf(
            "Student Psychometric Scores:\n- Numerical Reasoning: %.1f%%\n- Verbal Reasoning: %.1f%%\n- Spatial Awareness: %.1f%%\n- Personal Interest Engagement: %.1f%%\n\nBased on these scores, provide career guidance for this JSS3 Nigerian student transitioning to SS1.",
            $numerical, $verbal, $spatial, $interest
        );

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post("{$this->baseUrl}/v1/chat/completions", [
                    'model'       => 'deepseek-chat',
                    'temperature' => 0.7,
                    'max_tokens'  => 1400,
                    'messages'    => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user',   'content' => $userMessage],
                    ],
                ]);

            if (!$response->successful()) {
                Log::warning('DeepSeek API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return $this->fallback($numerical, $verbal, $spatial);
            }

            $content = $response->json('choices.0.message.content', '');

            // Strip potential markdown code fences
            $content = preg_replace('/^```(?:json)?\s*/i', '', trim($content));
            $content = preg_replace('/\s*```$/', '', $content);

            $data = json_decode(trim($content), true);

            if (json_last_error() !== JSON_ERROR_NONE || empty($data['combination'])) {
                Log::warning('DeepSeek response not valid JSON', ['content' => $content]);
                return $this->fallback($numerical, $verbal, $spatial);
            }

            // Normalise & validate
            $data['combination'] = $this->normaliseCombo($data['combination']);
            $data['careers']     = array_slice((array) ($data['careers']     ?? []), 0, 5);
            $data['universities']= array_slice((array) ($data['universities'] ?? []), 0, 10);
            $data['message']     = $data['message'] ?? '';
            $data['source']      = 'ai';

            return $data;

        } catch (\Throwable $e) {
            Log::error('DeepSeek service exception', ['error' => $e->getMessage()]);
            return $this->fallback($numerical, $verbal, $spatial);
        }
    }

    // ─────────────────────────────────────────────────────────────────
    // Fallback: rule-based recommendations when the API is unavailable
    // ─────────────────────────────────────────────────────────────────
    private function fallback(float $numerical, float $verbal, float $spatial): array
    {
        $combo = 'Arts';
        if ($numerical >= 60 && $spatial >= 60)    $combo = 'Science';
        elseif ($numerical >= 50 && $verbal >= 50) $combo = 'Commercial';
        elseif ($verbal >= 60)                     $combo = 'Arts';

        $strengths = [
            'numerical' => $numerical,
            'verbal'    => $verbal,
            'spatial'   => $spatial,
        ];

        arsort($strengths);
        $topKey = array_key_first($strengths);
        $topLabel = match ($topKey) {
            'numerical' => 'numbers and logic',
            'verbal'    => 'ideas and communication',
            'spatial'   => 'visual thinking and problem solving',
            default     => 'your strengths',
        };

        $profileBucket = match ($combo) {
            'Science' => match ($topKey) {
                'numerical' => 'math',
                'spatial' => 'design',
                'verbal' => 'research',
                default => 'balanced',
            },
            'Commercial' => match ($topKey) {
                'numerical' => 'finance',
                'verbal' => 'strategy',
                'spatial' => 'operations',
                default => 'balanced',
            },
            'Arts' => match ($topKey) {
                'verbal' => 'communication',
                'numerical' => 'analysis',
                'spatial' => 'creative',
                default => 'balanced',
            },
            default => 'balanced',
        };

        $scoreSummary = sprintf(
            'You scored %.1f%% in numerical reasoning, %.1f%% in verbal reasoning, and %.1f%% in spatial awareness.',
            $numerical,
            $verbal,
            $spatial
        );

        $careerByCombo = [
            'Science' => [
                'math' => ['Medical Doctor', 'Software Engineer', 'Biomedical Scientist', 'Pharmacist', 'Data Analyst'],
                'design' => ['Architect', 'Civil Engineer', 'Product Designer', 'Surveyor', 'Structural Engineer'],
                'research' => ['Software Engineer', 'Research Scientist', 'Medical Lab Scientist', 'Data Scientist', 'Biotechnologist'],
                'balanced' => ['Medical Doctor', 'Software Engineer', 'Civil Engineer', 'Pharmacist', 'Architect'],
            ],
            'Commercial' => [
                'finance' => ['Chartered Accountant', 'Financial Analyst', 'Investment Banker', 'Auditor', 'Tax Consultant'],
                'strategy' => ['Business Analyst', 'Marketing Manager', 'Entrepreneur', 'Economist', 'Consultant'],
                'operations' => ['Supply Chain Manager', 'Logistics Analyst', 'Project Manager', 'Operations Manager', 'Procurement Officer'],
                'balanced' => ['Chartered Accountant', 'Business Analyst', 'Investment Banker', 'Economist', 'Entrepreneur'],
            ],
            'Arts' => [
                'communication' => ['Journalist', 'Public Relations Officer', 'Communications Specialist', 'Teacher', 'Media Producer'],
                'analysis' => ['Lawyer', 'Policy Analyst', 'Researcher', 'Humanitarian Officer', 'Economist'],
                'creative' => ['Content Creator', 'Graphic Designer', 'Event Planner', 'Creative Writer', 'Diplomat'],
                'balanced' => ['Lawyer', 'Journalist', 'Teacher', 'Public Relations Officer', 'Diplomat'],
            ],
        ];

        $universityByCombo = [
            'Science' => [
                'math' => [
                    ['name' => 'University of Lagos', 'course' => 'Medicine & Surgery'],
                    ['name' => 'University of Nigeria, Nsukka', 'course' => 'Computer Science'],
                    ['name' => 'Ahmadu Bello University, Zaria', 'course' => 'Biochemistry'],
                ],
                'design' => [
                    ['name' => 'Ahmadu Bello University, Zaria', 'course' => 'Architecture'],
                    ['name' => 'Obafemi Awolowo University', 'course' => 'Civil Engineering'],
                    ['name' => 'University of Lagos', 'course' => 'Surveying & Geo-Informatics'],
                ],
                'research' => [
                    ['name' => 'University of Lagos', 'course' => 'Computer Science'],
                    ['name' => 'University of Ibadan', 'course' => 'Biochemistry'],
                    ['name' => 'University of Nigeria, Nsukka', 'course' => 'Microbiology'],
                ],
                'balanced' => [
                    ['name' => 'University of Lagos', 'course' => 'Medicine & Surgery'],
                    ['name' => 'Ahmadu Bello University, Zaria', 'course' => 'Civil Engineering'],
                    ['name' => 'University of Nigeria, Nsukka', 'course' => 'Computer Science'],
                ],
            ],
            'Commercial' => [
                'finance' => [
                    ['name' => 'University of Lagos', 'course' => 'Accounting'],
                    ['name' => 'Obafemi Awolowo University', 'course' => 'Finance'],
                    ['name' => 'University of Benin', 'course' => 'Banking & Finance'],
                ],
                'strategy' => [
                    ['name' => 'University of Lagos', 'course' => 'Business Administration'],
                    ['name' => 'Obafemi Awolowo University', 'course' => 'Economics'],
                    ['name' => 'University of Ilorin', 'course' => 'Marketing'],
                ],
                'operations' => [
                    ['name' => 'University of Benin', 'course' => 'Business Administration'],
                    ['name' => 'Lagos State University', 'course' => 'Operations Management'],
                    ['name' => 'University of Lagos', 'course' => 'Logistics & Supply Chain Management'],
                ],
                'balanced' => [
                    ['name' => 'University of Lagos', 'course' => 'Accounting'],
                    ['name' => 'Obafemi Awolowo University', 'course' => 'Economics'],
                    ['name' => 'Lagos State University', 'course' => 'Business Administration'],
                ],
            ],
            'Arts' => [
                'communication' => [
                    ['name' => 'University of Lagos', 'course' => 'Mass Communication'],
                    ['name' => 'Obafemi Awolowo University', 'course' => 'Mass Communication'],
                    ['name' => 'University of Ibadan', 'course' => 'Theatre Arts'],
                ],
                'analysis' => [
                    ['name' => 'University of Ibadan', 'course' => 'Law'],
                    ['name' => 'University of Nigeria, Nsukka', 'course' => 'Political Science'],
                    ['name' => 'University of Lagos', 'course' => 'Public Administration'],
                ],
                'creative' => [
                    ['name' => 'University of Ibadan', 'course' => 'English & Literary Studies'],
                    ['name' => 'University of Lagos', 'course' => 'Creative Arts'],
                    ['name' => 'Obafemi Awolowo University', 'course' => 'English Language'],
                ],
                'balanced' => [
                    ['name' => 'University of Ibadan', 'course' => 'Law'],
                    ['name' => 'Obafemi Awolowo University', 'course' => 'Mass Communication'],
                    ['name' => 'University of Nigeria, Nsukka', 'course' => 'English & Literary Studies'],
                ],
            ],
        ];

        $careerList = $careerByCombo[$combo][$profileBucket] ?? $careerByCombo[$combo]['balanced'] ?? $careerByCombo['Arts']['balanced'];
        $universityList = $universityByCombo[$combo][$profileBucket] ?? $universityByCombo[$combo]['balanced'] ?? $universityByCombo['Arts']['balanced'];
        $additionalUniversities = match ($combo) {
            'Science' => [
                ['name' => 'Federal University of Technology, Akure', 'course' => 'Electrical & Electronics Engineering'],
                ['name' => 'Federal University of Technology, Minna', 'course' => 'Computer Engineering'],
                ['name' => 'Federal University of Technology, Owerri', 'course' => 'Mechanical Engineering'],
                ['name' => 'University of Ilorin', 'course' => 'Civil Engineering'],
                ['name' => 'University of Benin', 'course' => 'Chemical Engineering'],
                ['name' => 'University of Port Harcourt', 'course' => 'Petroleum Engineering'],
                ['name' => 'University of Ibadan', 'course' => 'Industrial & Production Engineering'],
            ],
            'Commercial' => [
                ['name' => 'University of Nigeria, Nsukka', 'course' => 'Accountancy'],
                ['name' => 'Ahmadu Bello University, Zaria', 'course' => 'Business Administration'],
                ['name' => 'University of Benin', 'course' => 'Economics'],
                ['name' => 'University of Ilorin', 'course' => 'Accounting'],
                ['name' => 'University of Port Harcourt', 'course' => 'Finance'],
                ['name' => 'University of Ibadan', 'course' => 'Economics'],
                ['name' => 'Nnamdi Azikiwe University', 'course' => 'Banking & Finance'],
            ],
            default => [
                ['name' => 'Ahmadu Bello University, Zaria', 'course' => 'Mass Communication'],
                ['name' => 'University of Nigeria, Nsukka', 'course' => 'English & Literary Studies'],
                ['name' => 'University of Benin', 'course' => 'Political Science'],
                ['name' => 'University of Ilorin', 'course' => 'Performing Arts'],
                ['name' => 'Lagos State University', 'course' => 'Law'],
                ['name' => 'Nnamdi Azikiwe University', 'course' => 'English Language'],
                ['name' => 'University of Port Harcourt', 'course' => 'Theatre & Film Studies'],
            ],
        };
        $universityList = collect(array_merge($universityList, $additionalUniversities))
            ->unique('name')
            ->take(10)
            ->values()
            ->all();

        $message = match ($combo) {
            'Science' => $scoreSummary . ' Your strongest area is ' . $topLabel . '. This makes a Science pathway a very strong fit for medicine, engineering, software, and other problem-solving careers. Keep building your skills in mathematics and practical study, because your profile is promising for STEM success in Nigeria.',
            'Commercial' => $scoreSummary . ' Your profile is strongest in ' . $topLabel . ', which suits Commercial subjects and careers in finance, business, and economics. With steady practice and discipline, you can do well in accounting, business administration, and entrepreneurial careers.',
            default => $scoreSummary . ' Your strongest skill appears to be ' . $topLabel . ', and that fits the Arts pathway very well. This supports careers in communication, education, law, leadership, and public service. Keep improving your confidence in writing, speaking, and critical thinking.',
        };

        return [
            'combination' => $combo,
            'careers' => $careerList,
            'universities' => $universityList,
            'message' => $message,
            'source' => 'fallback',
        ];
    }

    private function normaliseCombo(string $raw): string
    {
        $raw = strtolower(trim($raw));
        if (str_contains($raw, 'science'))     return 'Science';
        if (str_contains($raw, 'commercial'))  return 'Commercial';
        return 'Arts';
    }
}
