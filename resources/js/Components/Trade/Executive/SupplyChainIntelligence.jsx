import { usePage } from "@inertiajs/react";

export default function SupplyChainIntelligence({ data = {} }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

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

            <div className="border-b px-6 py-5">
                <h2 className="text-xl font-bold">
                    {isEn
                        ? "Supply Chain Intelligence"
                        : "Intelijen Rantai Pasok"}
                </h2>

                <p className="mt-1 text-sm text-slate-500">
                    {isEn
                        ? "Industrial ecosystem and value chain overview."
                        : "Gambaran ekosistem industri dan rantai nilai."}
                </p>
            </div>

            {/* Main Grid */}

            <div className="grid gap-5 p-6 lg:grid-cols-2">
                <Section title="UPSTREAM" items={data.upstream} />

                <Section title="MIDSTREAM" items={data.midstream} />

                <Section title="DOWNSTREAM" items={data.downstream} />

                <Section title="SUPPORTING" items={data.supporting} />

                <Section title="DIGITAL" items={data.digital} />

                <Section title="SUSTAINABILITY" items={data.sustainability} />
            </div>
            {/* AI Insight */}

            <div
                className="
                    border-t
                    bg-slate-50
                    px-6
                    py-5
                "
            >
                <p
                    className="
                        text-sm
                        font-bold
                        uppercase
                        tracking-wider
                        text-violet-700
                    "
                >
                    DIGESTEX AI
                </p>

                <p
                    className="
                        mt-2
                        text-sm
                        leading-7
                        text-slate-600
                    "
                >
                    {data.ai_insight}
                </p>
            </div>
        </div>
    );
}

function Section({ title, items = [] }) {
    return (
        <div
            className="
                rounded-2xl
                border
                border-slate-200
                p-5
            "
        >
            <h3
                className="
                    text-sm
                    font-bold
                    tracking-wider
                    text-slate-900
                "
            >
                {title}
            </h3>

            <div className="mt-4 space-y-3">
                {items?.length === 0 ? (
                    <p className="text-sm text-slate-400">No data available.</p>
                ) : (
                    items.map((item) => (
                        <div
                            key={item.id}
                            className="
                                rounded-xl
                                bg-slate-50
                                px-4
                                py-3
                            "
                        >
                            <p
                                className="
                                    font-medium
                                    text-slate-900
                                "
                            >
                                {item.label}
                            </p>

                            <p
                                className="
                                    mt-1
                                    text-xs
                                    leading-6
                                    text-slate-500
                                "
                            >
                                {item.description}
                            </p>
                        </div>
                    ))
                )}
            </div>
        </div>
    );
}
