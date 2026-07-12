<?php

declare(strict_types=1);

namespace App\Services\Executive;

use App\Models\Company;

/**
 * ==========================================================================
 * DIGESTEX Operating System (DOS)
 * ==========================================================================
 * Executive AI Service
 * ==========================================================================
 *
 * Executive AI Orchestrator.
 *
 * Responsibilities
 * ----------------
 * • CEO Brief
 * • Executive Summary
 * • Explainability
 * • Recommendations
 * • Action Plan
 * • AI Narrative
 *
 */

class ExecutiveAIService
{
    public function __construct(
        protected ExecutiveIntelligenceService $intelligence,
    ) {
    }

    /**
     * Build Executive AI Result.
     */
    public function analyze(
        Company $company
    ): ExecutiveAIResult {

        $result = $this->intelligence
            ->intelligence($company);

        return new ExecutiveAIResult(

            company: $company,

            dashboard: $result['dashboard'],

            graph: $result['graph'],

            reasoning: $result['reasoning'],

            explanation: $result['explanation'],

            recommendations: $result['recommendations'],

            narrative: $this->buildNarrative($result),

            priorities: $this->priorities($result),

            opportunities: $this->opportunities($result),

            risks: $this->risks($result)

        );

    }
    /*
|--------------------------------------------------------------------------
| Executive Narrative
|--------------------------------------------------------------------------
*/

protected function buildNarrative(
    array $result
): string {

    $dashboard = $result['dashboard'];

    $score = $dashboard['executive_score'];

    if ($score >= 90) {

        return
            "The company demonstrates excellent readiness "
            ."for international business expansion. "
            ."Its operational capability, compliance, "
            ."market readiness and supply chain maturity "
            ."indicate a strong competitive position.";

    }

    if ($score >= 75) {

        return
            "The company has good operational capability "
            ."with several opportunities to strengthen "
            ."its export readiness and sustainability.";

    }

    return
        "The company requires strategic improvements "
        ."before expanding into higher-value markets.";

}
/*
|--------------------------------------------------------------------------
| Priorities
|--------------------------------------------------------------------------
*/

protected function priorities(
    array $result
): array {

    return [

        [

            'priority'=>1,

            'title'=>'Improve Compliance',

            'impact'=>'High',

        ],

        [

            'priority'=>2,

            'title'=>'Expand Export Markets',

            'impact'=>'Medium',

        ],

        [

            'priority'=>3,

            'title'=>'Technology Upgrade',

            'impact'=>'Medium',

        ],

    ];

}
/*
|--------------------------------------------------------------------------
| Opportunities
|--------------------------------------------------------------------------
*/

protected function opportunities(
    array $result
): array {

    return [

        'European Union',

        'United States',

        'Japan',

        'Circular Economy',

        'Technical Textile',

    ];

}
/*
|--------------------------------------------------------------------------
| Risks
|--------------------------------------------------------------------------
*/

protected function risks(
    array $result
): array {

    return [

        'Limited Certifications',

        'Limited Export Markets',

        'Technology Gap',

        'ESG Compliance',

    ];

}
/*
|--------------------------------------------------------------------------
| CEO Brief
|--------------------------------------------------------------------------
*/

public function ceoBrief(
    Company $company
): string {

    $analysis = $this->analyze($company);

    return implode("\n\n", [

        "Executive Score : "
        .$analysis->dashboard()['executive_score'],

        $analysis->narrative(),

        "Priority Actions:",

        collect($analysis->priorities())

            ->pluck('title')

            ->implode("\n"),

    ]);

}
}