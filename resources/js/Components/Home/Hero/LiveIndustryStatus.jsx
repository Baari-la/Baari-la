import {
    Activity,
    BrainCircuit,
    Database,
    Globe2,
    RefreshCw,
    Ship,
} from "lucide-react";

const items = [
    {
        icon: Activity,
        color: "text-emerald-400",
        label: "LIVE",
        value: "Industry Monitoring",
    },
    {
        icon: RefreshCw,
        color: "text-cyan-400",
        label: "Trade Data",
        value: "Updated Daily",
    },
    {
        icon: BrainCircuit,
        color: "text-violet-400",
        label: "AI Engine",
        value: "Monitoring Active",
    },
    {
        icon: Database,
        color: "text-amber-400",
        label: "Verified Data",
        value: "223,974 Records",
    },
    {
        icon: Globe2,
        color: "text-blue-400",
        label: "Coverage",
        value: "225 Countries",
    },
    {
        icon: Ship,
        color: "text-sky-400",
        label: "Supply Chain",
        value: "Connected",
    },
];

export default function LiveIndustryStatus() {
    return (
        <div className="border-t border-white/10 bg-slate-950/80 backdrop-blur">
            <div className="container mx-auto px-6">
                <div className="flex flex-wrap items-center justify-center gap-x-8 gap-y-5 py-5">
                    {items.map((item) => {
                        const Icon = item.icon;

                        return (
                            <div
                                key={item.label}
                                className="flex items-center gap-3"
                            >
                                <div
                                    className={`rounded-full bg-white/5 p-2 ${item.color}`}
                                >
                                    <Icon size={16} />
                                </div>

                                <div>
                                    <div className="text-[11px] font-bold uppercase tracking-widest text-slate-500">
                                        {item.label}
                                    </div>

                                    <div className="text-sm font-semibold text-slate-200">
                                        {item.value}
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        </div>
    );
}
