import { usePage } from "@inertiajs/react";
import { Trophy, Medal, Award, TrendingUp, ChevronRight } from "lucide-react";

export default function CountryLeaderboard({
    dataPeriod = "January-April 2026",
    countries = [],
}) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const getRankIcon = (rank) => {
        switch (rank) {
            case 1:
                return <Trophy size={22} className="text-yellow-500" />;

            case 2:
                return <Medal size={22} className="text-slate-500" />;

            case 3:
                return <Award size={22} className="text-amber-700" />;

            default:
                return (
                    <span
                        className="
                            flex
                            h-8
                            w-8
                            items-center
                            justify-center
                            rounded-full
                            bg-slate-100
                            text-sm
                            font-bold
                            text-slate-700
                        "
                    >
                        {rank}
                    </span>
                );
        }
    };

    const gradeColor = (grade) => {
        switch (grade) {
            case "A+":
                return "bg-emerald-100 text-emerald-700";

            case "A":
                return "bg-blue-100 text-blue-700";

            case "B+":
                return "bg-violet-100 text-violet-700";

            case "B":
                return "bg-yellow-100 text-yellow-700";

            default:
                return "bg-slate-100 text-slate-700";
        }
    };

    return (
        <div
            className="
                overflow-hidden
                rounded-3xl
                border
                border-slate-200
                bg-white
                shadow-sm
            "
        >
            {/* Header */}

            <div className="border-b border-slate-100 px-6 py-5">
                <div className="flex items-center gap-3">
                    <Trophy size={28} className="text-yellow-500" />

                    <div>
                        <h2 className="text-2xl font-bold text-slate-900">
                            {isEn
                                ? "Country Intelligence Leaderboard"
                                : "Peringkat Intelijen Negara"}
                        </h2>

                        <p className="mt-1 text-sm text-slate-500">
                            {isEn ? "Reporting Period" : "Periode Pelaporan"}:{" "}
                            {dataPeriod}
                        </p>
                    </div>
                </div>
            </div>

            {/* Rankings */}

            <div className="p-6">
                <div className="space-y-4">
                    {countries.map((country, index) => (
                        <div
                            key={country.country_code}
                            className="
                                    rounded-2xl
                                    border
                                    border-slate-200
                                    p-5
                                    transition
                                    hover:-translate-y-1
                                    hover:shadow-md
                                "
                        >
                            <div className="flex items-center justify-between">
                                <div className="flex items-center gap-4">
                                    {getRankIcon(index + 1)}

                                    <div>
                                        <h3
                                            className="
                                                    text-lg
                                                    font-bold
                                                    text-slate-900
                                                "
                                        >
                                            {country.flag}{" "}
                                            {isEn
                                                ? country.country_name_en
                                                : country.country_name_id}
                                        </h3>

                                        <p
                                            className="
                                                    mt-1
                                                    text-sm
                                                    text-slate-500
                                                "
                                        >
                                            {isEn
                                                ? "Country Intelligence Score"
                                                : "Skor Intelijen Negara"}
                                        </p>
                                    </div>
                                </div>

                                <ChevronRight
                                    className="text-slate-400"
                                    size={20}
                                />
                            </div>

                            {/* Score */}

                            <div className="mt-5 grid gap-4 md:grid-cols-3">
                                <div
                                    className="
                                            rounded-2xl
                                            bg-slate-50
                                            p-4
                                        "
                                >
                                    <p className="text-xs text-slate-500">
                                        SCORE
                                    </p>

                                    <h4
                                        className="
                                                mt-2
                                                text-3xl
                                                font-bold
                                                text-slate-900
                                            "
                                    >
                                        {country.score?.score}
                                    </h4>
                                </div>

                                <div
                                    className="
                                            rounded-2xl
                                            bg-slate-50
                                            p-4
                                        "
                                >
                                    <p className="text-xs text-slate-500">
                                        GRADE
                                    </p>

                                    <span
                                        className={`
                                                mt-2
                                                inline-flex
                                                rounded-full
                                                px-3
                                                py-1
                                                text-sm
                                                font-bold
                                                ${gradeColor(
                                                    country.score?.grade,
                                                )}
                                            `}
                                    >
                                        {country.score?.grade}
                                    </span>
                                </div>

                                <div
                                    className="
                                            rounded-2xl
                                            bg-slate-50
                                            p-4
                                        "
                                >
                                    <p className="text-xs text-slate-500">
                                        RANKING
                                    </p>

                                    <h4
                                        className="
                                                mt-2
                                                text-3xl
                                                font-bold
                                                text-slate-900
                                            "
                                    >
                                        #{index + 1}
                                    </h4>
                                </div>
                            </div>

                            {/* Badges */}

                            {/* Insight */}

                            <div
                                className="
                                        mt-5
                                        rounded-2xl
                                        bg-emerald-50
                                        p-4
                                    "
                            >
                                <div className="flex items-center gap-2">
                                    <TrendingUp
                                        size={18}
                                        className="
                                                text-emerald-600
                                            "
                                    />

                                    <p
                                        className="
                                                text-xs
                                                font-bold
                                                uppercase
                                                tracking-wide
                                                text-emerald-700
                                            "
                                    >
                                        {isEn
                                            ? "Executive Insight"
                                            : "Insight Eksekutif"}
                                    </p>
                                </div>

                                <p
                                    className="
                                            mt-2
                                            text-sm
                                            leading-7
                                            text-slate-700
                                        "
                                >
                                    {country.score?.summary ??
                                        "No executive insight available."}
                                </p>
                            </div>
                        </div>
                    ))}

                    {countries.length === 0 && (
                        <div className="py-10 text-center">
                            <Trophy
                                size={42}
                                className="
                                    mx-auto
                                    text-yellow-500
                                "
                            />

                            <p className="mt-4 text-slate-600">
                                {isEn
                                    ? "No country rankings available."
                                    : "Belum ada peringkat negara."}
                            </p>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
