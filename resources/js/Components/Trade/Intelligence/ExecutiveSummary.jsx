import { Sparkles } from "lucide-react";

export default function ExecutiveSummary({ summary }) {
    return (
        <section className="rounded-3xl border border-slate-200 bg-white shadow-sm">
            {/* Header */}
            <div className="border-b border-slate-100 px-8 py-6">
                <div className="flex items-center gap-3">
                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">
                        <Sparkles size={20} className="text-blue-600" />
                    </div>

                    <div>
                        <p className="text-xs font-semibold uppercase tracking-[0.25em] text-blue-600">
                            Executive Summary
                        </p>

                        <h2 className="mt-1 text-2xl font-bold text-slate-900">
                            Indonesia Textile Industry Overview
                        </h2>
                    </div>
                </div>
            </div>

            {/* Content */}
            <div className="px-8 py-8">
                <div className="prose prose-slate max-w-none">
                    <p className="text-lg leading-9 text-slate-700">
                        {summary ||
                            "Executive Summary will be generated automatically from the latest official trade statistics and AI analysis. This section provides a concise overview of export performance, import trends, trade balance, market developments, opportunities, and potential risks for Indonesia's textile industry."}
                    </p>
                </div>
            </div>

            {/* Footer */}
            <div className="flex flex-wrap items-center gap-3 border-t border-slate-100 bg-slate-50 px-8 py-4 text-sm text-slate-500">
                <span className="rounded-full bg-emerald-100 px-3 py-1 font-medium text-emerald-700">
                    Official Trade Statistics
                </span>

                <span className="rounded-full bg-blue-100 px-3 py-1 font-medium text-blue-700">
                    AI-Assisted Analysis
                </span>

                <span className="rounded-full bg-slate-200 px-3 py-1 font-medium text-slate-700">
                    Updated Monthly
                </span>
            </div>
        </section>
    );
}
