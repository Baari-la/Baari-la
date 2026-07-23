import { usePage } from "@inertiajs/react";

export default function SectorOverviewMap({ sector = "textile" }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const groups = {
        upstream: ["🌾 Fiber", "🧵 Yarn"],

        midstream: ["🧶 Fabric"],

        downstream: ["👔 Apparel"],
    };

    return (
        <div
            className="
                rounded-3xl
                border
                border-slate-200
                bg-white
                shadow-sm
            "
        >
            <div className="border-b px-6 py-5">
                <h2 className="text-xl font-bold">
                    {isEn ? "Sector Overview Map" : "Peta Sektor"}
                </h2>

                <p className="mt-1 text-sm text-slate-500">
                    Textile Value Chain
                </p>
            </div>

            <div className="grid gap-5 p-6 md:grid-cols-3">
                <GroupCard title="UPSTREAM" items={groups.upstream} />

                <GroupCard title="MIDSTREAM" items={groups.midstream} />

                <GroupCard title="DOWNSTREAM" items={groups.downstream} />
            </div>

            <div className="border-t bg-slate-50 px-6 py-5">
                <p className="font-bold text-violet-700">DIGESTEX AI</p>

                <p className="mt-2 text-sm leading-7 text-slate-600">
                    Indonesia demonstrates stronger competitiveness in
                    downstream textile products while maintaining strategic
                    dependence on imported upstream materials.
                </p>
            </div>
        </div>
    );
}

function GroupCard({ title, items }) {
    return (
        <div
            className="
                rounded-2xl
                border
                border-slate-200
                p-5
            "
        >
            <h3 className="font-bold text-slate-900">{title}</h3>

            <div className="mt-4 space-y-3">
                {items.map((item) => (
                    <div
                        key={item}
                        className="
                            rounded-xl
                            bg-slate-50
                            px-4
                            py-3
                            text-sm
                            font-medium
                        "
                    >
                        {item}
                    </div>
                ))}
            </div>
        </div>
    );
}
