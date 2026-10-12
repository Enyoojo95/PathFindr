<?php

namespace App\Services;

use App\Models\User;

class PlatformStatisticsService
{
    /**
     * @return array{totalStudents: int, totalSchools: int}
     */
    public function registrationCounts(): array
    {
        $students = User::query()->where('role', User::ROLE_STUDENT);

        $totalSchools = (clone $students)
            ->whereNotNull('school_name')
            ->whereRaw("TRIM(school_name) <> ''")
            ->selectRaw('COUNT(DISTINCT LOWER(TRIM(school_name))) as total')
            ->value('total');

        return [
            'totalStudents' => (clone $students)->count(),
            'totalSchools' => (int) $totalSchools,
        ];
    }
}