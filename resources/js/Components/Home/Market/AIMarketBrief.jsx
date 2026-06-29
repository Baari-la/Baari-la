import { BrainCircuit, Sparkles } from "lucide-react";

export default function AIMarketBrief() {
    return (
        <div className="rounded-3xl border border-indigo-200 bg-gradient-to-br from-indigo-950 to-slate-900 p-8 text-white shadow-lg">
            <div className="flex items-center gap-3">
                <div className="rounded-2xl bg-indigo-500/20 p-3">
                    <BrainCircuit size={28} className="text-cyan-300" />
                </div>

                <div>
                    <p className="text-xs font-bold uppercase tracking-[0.25em] text-cyan-300">
                        AI Executive Brief
                    </p>

                    <h3 className="mt-1 text-2xl font-black">
                        Market Intelligence
                    </h3>
                </div>
            </div>

            <div className="mt-8 space-y-5 text-sm leading-7 text-slate-300">
                <p>
                    Cotton prices remained relatively stable during the past
                    week, supported by steady global demand.
                </p>

                <p>
                    USD strengthened slightly against IDR, potentially
                    increasing imported raw material costs.
                </p>

                <p>
                    Ocean freight continued to normalize, creating opportunities
                    for export-oriented manufacturers.
                </p>

                <div className="rounded-2xl border border-cyan-500/30 bg-cyan-500/10 p-5">
                    <div className="mb-2 flex items-center gap-2">
                        <Sparkles size={18} className="text-cyan-300" />

                        <span className="font-bold text-cyan-300">
                            AI Recommendation
                        </span>
                    </div>

                    <p className="leading-7 text-slate-200">
                        Monitor polyester procurement over the next two weeks,
                        while maintaining cotton inventory levels. Current
                        logistics conditions remain favorable for export
                        shipments.
                    </p>
                </div>
            </div>
        </div>
    );
}
