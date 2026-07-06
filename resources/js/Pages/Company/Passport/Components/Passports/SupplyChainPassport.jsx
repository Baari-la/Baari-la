import { Truck, ChevronRight } from "lucide-react";

export default function SupplyChainPassport({ passport }) {
    const sections = [
        ["Factory", passport?.passport?.factory],

        ["Capacity", passport?.passport?.capacity],

        ["Products", passport?.passport?.products],

        ["Machinery", passport?.passport?.machinery],

        ["MOQ", passport?.passport?.moq],

        ["Lead Time", passport?.passport?.lead_time],

        ["Logistics", passport?.passport?.logistics],
    ];

    return (
        <div className="rounded-2xl border bg-white shadow-sm">
            <div className="flex items-center gap-3 border-b px-6 py-4">
                <Truck className="h-6 w-6 text-orange-600" />

                <div>
                    <h2 className="text-xl font-bold">Supply Chain Passport</h2>

                    <p className="text-sm text-slate-500">
                        Supply chain and logistics readiness.
                    </p>
                </div>
            </div>

            <div className="divide-y">
                {sections.map(([title, item]) => (
                    <div
                        key={title}
                        className="flex items-center justify-between px-6 py-4"
                    >
                        <div>
                            <h3 className="font-semibold">{title}</h3>

                            <p className="text-sm text-slate-500">
                                {item?.count ?? 0} item(s)
                            </p>
                        </div>

                        <div className="flex items-center gap-3">
                            <span
                                className={
                                    item?.is_complete
                                        ? "rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700"
                                        : "rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600"
                                }
                            >
                                {item?.is_complete ? "Ready" : "Incomplete"}
                            </span>

                            <ChevronRight className="h-5 w-5 text-slate-400" />
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
