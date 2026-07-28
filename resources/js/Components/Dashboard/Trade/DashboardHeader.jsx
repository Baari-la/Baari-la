import { CalendarDays, Database, Globe2, Layers3 } from "lucide-react";

export default function DashboardHeader({ summary = {} }) {
    const records =
        summary.records !== null && summary.records !== undefined
            ? Number(summary.records).toLocaleString("en-US")
            : "-";

    return (
        <section
            className="
                mb-5
                overflow-hidden
                rounded-[32px]
                border
                border-slate-200
                bg-white
                shadow-sm
            "
        >
            <div className="p-7 lg:p-8">
                <div
                    className="
                        flex
                        flex-col
                        gap-8
                        xl:flex-row
                        xl:items-center
                        xl:justify-between
                    "
                >
                    {/* LEFT */}

                    <div className="max-w-3xl">
                        <div
                            className="
                                inline-flex
                                items-center
                                gap-2
                                text-[10px]
                                font-black
                                uppercase
                                tracking-[0.22em]
                                text-blue-600
                            "
                        >
                            <Globe2 className="h-4 w-4" />
                            Trade Intelligence
                        </div>

                        <h2
                            className="
                                mt-3
                                text-3xl
                                font-black
                                tracking-tight
                                text-slate-950
                                lg:text-4xl
                            "
                        >
                            Global Textile Trade Analytics
                        </h2>

                        <p
                            className="
                                mt-3
                                max-w-2xl
                                text-sm
                                leading-6
                                text-slate-500
                            "
                        >
                            Analyze textile export and import flows, trade
                            structure, market movements, and sector performance
                            through DIGESTEX industrial intelligence.
                        </p>
                    </div>

                    {/* METADATA */}

                    <div
                        className="
                            grid
                            gap-3
                            sm:grid-cols-2
                            xl:w-[520px]
                        "
                    >
                        <MetaCard
                            icon={Database}
                            label="Records"
                            value={records}
                        />

                        <MetaCard
                            icon={CalendarDays}
                            label="Last Update"
                            value={summary.lastUpdate ?? "-"}
                        />

                        <MetaCard
                            icon={Globe2}
                            label="Data Source"
                            value={summary.source ?? "Official Trade Data"}
                        />

                        <MetaCard
                            icon={Layers3}
                            label="Coverage"
                            value={summary.coverage ?? "2019–2026"}
                        />
                    </div>
                </div>
            </div>

            {/* DATASET STATUS */}

            <div
                className="
                    flex
                    flex-col
                    gap-2
                    border-t
                    border-slate-100
                    bg-slate-50/70
                    px-7
                    py-4
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                    lg:px-8
                "
            >
                <div
                    className="
                        flex
                        items-center
                        gap-2
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-[0.15em]
                        text-slate-500
                    "
                >
                    <span
                        className="
                            h-2
                            w-2
                            rounded-full
                            bg-emerald-500
                        "
                    />
                    Trade Dataset Available
                </div>

                <p className="text-xs text-slate-400">
                    DIGESTEX Textile Intelligence Dataset
                </p>
            </div>
        </section>
    );
}

function MetaCard({ icon: Icon, label, value }) {
    return (
        <div
            className="
                rounded-2xl
                border
                border-slate-200
                bg-slate-50
                p-4
            "
        >
            <div
                className="
                    flex
                    items-center
                    gap-2
                    text-[9px]
                    font-black
                    uppercase
                    tracking-[0.16em]
                    text-slate-400
                "
            >
                <Icon className="h-4 w-4 text-slate-500" />

                {label}
            </div>

            <div
                className="
                    mt-2
                    truncate
                    text-sm
                    font-black
                    text-slate-900
                "
                title={String(value)}
            >
                {value}
            </div>
        </div>
    );
}
