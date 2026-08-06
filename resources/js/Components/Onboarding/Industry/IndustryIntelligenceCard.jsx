/*
|--------------------------------------------------------------------------
| DIGESTEX Industry Intelligence Card™
|--------------------------------------------------------------------------
|
| Universal Intelligence Card.
|
| Displays the DIGESTEX Intelligence modules
| defined by the Industry Blueprint™.
|
|--------------------------------------------------------------------------
*/

import { Brain, CheckCircle2 } from "lucide-react";

export default function IndustryIntelligenceCard({ blueprint }) {
    /*
    |--------------------------------------------------------------------------
    | Blueprint
    |--------------------------------------------------------------------------
    */

    const {
        intelligence = [],
        intelligenceTitle = "DIGESTEX Industry Intelligence™",
        intelligenceDescription = "",
    } = blueprint ?? {};

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <div className="rounded-3xl border border-indigo-100 bg-indigo-50 p-6">
            {/* ======================================================
                Header
            ====================================================== */}

            <div className="flex items-start gap-4">
                <div className="rounded-xl bg-indigo-100 p-3">
                    <Brain className="h-6 w-6 text-indigo-600" />
                </div>

                <div>
                    <h3 className="text-lg font-bold text-indigo-700">
                        {intelligenceTitle}
                    </h3>

                    {intelligenceDescription && (
                        <p className="mt-2 text-sm leading-6 text-slate-600">
                            {intelligenceDescription}
                        </p>
                    )}
                </div>
            </div>

            {/* ======================================================
                Intelligence Modules
            ====================================================== */}

            <div className="mt-6 space-y-3">
                {intelligence.map((module) => (
                    <div
                        key={module}
                        className="flex items-center gap-3 rounded-xl bg-white p-3 shadow-sm"
                    >
                        <CheckCircle2 className="h-5 w-5 text-indigo-600" />

                        <span className="font-medium">{module}</span>
                    </div>
                ))}
            </div>
        </div>
    );
}
