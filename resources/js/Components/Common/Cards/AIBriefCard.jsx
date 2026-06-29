import Card from "../Layout/Card";

import { BrainCircuit, Sparkles } from "lucide-react";

export default function AIBriefCard({
    title = "AI Executive Brief",

    summary,

    recommendation,
}) {
    return (
        <Card className="bg-gradient-to-br from-indigo-950 to-slate-900 text-white border-0">
            <div className="flex items-center gap-3">
                <div className="rounded-xl bg-indigo-400/20 p-3">
                    <BrainCircuit size={24} className="text-cyan-300" />
                </div>

                <div>
                    <p className="text-xs uppercase tracking-widest text-cyan-300">
                        Artificial Intelligence
                    </p>

                    <h3 className="font-bold">{title}</h3>
                </div>
            </div>

            <p className="mt-6 leading-7 text-slate-300">{summary}</p>

            {recommendation && (
                <div className="mt-8 rounded-2xl border border-cyan-500/20 bg-cyan-500/10 p-5">
                    <div className="mb-3 flex items-center gap-2">
                        <Sparkles size={18} className="text-cyan-300" />

                        <span className="font-semibold text-cyan-300">
                            Recommendation
                        </span>
                    </div>

                    <p className="leading-7 text-slate-200">{recommendation}</p>
                </div>
            )}
        </Card>
    );
}
