import {
    BrainCircuit,
    TrendingUp,
    AlertTriangle,
    Lightbulb,
} from "lucide-react";

export default function AIInsightCard({ insights = [] }) {
    return (
        <div className="rounded-3xl border border-slate-200 bg-white shadow-sm">
            {/* Header */}

            <div className="border-b border-slate-100 px-6 py-5">
                <div className="flex items-center gap-2">
                    <BrainCircuit size={20} className="text-violet-600" />

                    <h2 className="text-lg font-bold text-slate-900">
                        AI Industry Insight
                    </h2>
                </div>

                <p className="mt-1 text-sm text-slate-500">
                    EN : AI-generated executive summary based on trade
                    statistics.
                    <br />
                    ID : Ringkasan eksekutif yang dihasilkan AI berdasarkan
                    statistik perdagangan.
                </p>
            </div>

            {/* Body */}

            <div className="space-y-5 p-6">
                {insights.length === 0 && (
                    <div className="rounded-2xl border border-violet-100 bg-violet-50 p-5">
                        <div className="font-semibold text-violet-700">
                            AI analysis is not available yet.
                        </div>

                        <div className="mt-1 text-sm text-violet-600">
                            Analisis otomatis akan muncul setelah AI Engine
                            memproses data perdagangan.
                        </div>
                    </div>
                )}

                {insights.map((item, index) => (
                    <div
                        key={index}
                        className="rounded-2xl border border-slate-100 bg-slate-50 p-5"
                    >
                        <div className="flex items-center gap-2">
                            {item.type === "opportunity" && (
                                <TrendingUp
                                    size={18}
                                    className="text-emerald-600"
                                />
                            )}

                            {item.type === "risk" && (
                                <AlertTriangle
                                    size={18}
                                    className="text-red-600"
                                />
                            )}

                            {item.type === "recommendation" && (
                                <Lightbulb
                                    size={18}
                                    className="text-amber-500"
                                />
                            )}

                            <div className="font-bold text-slate-900">
                                {item.title}
                            </div>
                        </div>

                        <div className="mt-3 text-sm leading-6 text-slate-600">
                            {item.description}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
