/*
|--------------------------------------------------------------------------
| DIGESTEX Score Card™
|--------------------------------------------------------------------------
|
| Generic score card used throughout the DIGESTEX
| onboarding experience.
|
| Examples:
|
| • Business Profile Score™
| • Capability Score™
| • Manufacturing Score™
| • Buyer Readiness™
| • ESG Readiness™
|
|--------------------------------------------------------------------------
*/

export default function ScoreCard({
    title,
    score = 0,
    maxScore = 100,
    description = "",
    color = "emerald",
}) {
    const percentage = Math.min(
        Math.max(Math.round((score / maxScore) * 100), 0),
        100,
    );

    const COLORS = {
        emerald: {
            background: "bg-emerald-50",
            border: "border-emerald-200",
            text: "text-emerald-600",
            title: "text-emerald-700",
            progress: "bg-emerald-500",
            track: "bg-emerald-100",
        },

        blue: {
            background: "bg-blue-50",
            border: "border-blue-200",
            text: "text-blue-600",
            title: "text-blue-700",
            progress: "bg-blue-500",
            track: "bg-blue-100",
        },

        amber: {
            background: "bg-amber-50",
            border: "border-amber-200",
            text: "text-amber-600",
            title: "text-amber-700",
            progress: "bg-amber-500",
            track: "bg-amber-100",
        },

        indigo: {
            background: "bg-indigo-50",
            border: "border-indigo-200",
            text: "text-indigo-600",
            title: "text-indigo-700",
            progress: "bg-indigo-500",
            track: "bg-indigo-100",
        },
    };

    const theme = COLORS[color] ?? COLORS.emerald;

    return (
        <div
            className={`rounded-3xl border p-7 ${theme.background} ${theme.border}`}
        >
            <h3 className={`text-lg font-black ${theme.title}`}>{title}</h3>

            <div className="mt-6 text-center">
                <div className={`text-5xl font-black ${theme.text}`}>
                    {percentage}%
                </div>

                <div className="mt-2 text-sm text-slate-600">
                    {score} / {maxScore}
                </div>
            </div>

            <div
                className={`mt-6 h-3 overflow-hidden rounded-full ${theme.track}`}
            >
                <div
                    className={`h-full rounded-full transition-all duration-500 ${theme.progress}`}
                    style={{
                        width: `${percentage}%`,
                    }}
                />
            </div>

            {description && (
                <p className="mt-5 text-sm leading-6 text-slate-600">
                    {description}
                </p>
            )}
        </div>
    );
}
