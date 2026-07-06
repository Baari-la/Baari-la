import { Factory, ChevronRight } from "lucide-react";

export default function CapabilityPassport({ passport }) {
    const sections = [
        {
            title: "Production",
            value: passport?.passport?.production?.count ?? 0,
            complete: passport?.passport?.production?.is_complete,
        },

        {
            title: "Machinery",
            value: passport?.passport?.machinery?.count ?? 0,
            complete: passport?.passport?.machinery?.is_complete,
        },

        {
            title: "Products",
            value: passport?.passport?.products?.count ?? 0,
            complete: passport?.passport?.products?.is_complete,
        },

        {
            title: "MOQ",
            value: passport?.passport?.moq?.count ?? 0,
            complete: passport?.passport?.moq?.is_complete,
        },

        {
            title: "Lead Time",
            value: passport?.passport?.lead_time?.count ?? 0,
            complete: passport?.passport?.lead_time?.is_complete,
        },
    ];

    return (
        <div className="rounded-2xl border bg-white shadow-sm">
            <div className="flex items-center justify-between border-b px-6 py-4">
                <div className="flex items-center gap-3">
                    <Factory className="h-6 w-6 text-indigo-600" />

                    <div>
                        <h2 className="text-xl font-bold">
                            Capability Passport
                        </h2>

                        <p className="text-sm text-slate-500">
                            Manufacturing capability overview.
                        </p>
                    </div>
                </div>
            </div>

            <div className="divide-y">
                {sections.map((section) => (
                    <div
                        key={section.title}
                        className="flex items-center justify-between px-6 py-4"
                    >
                        <div>
                            <h3 className="font-semibold">{section.title}</h3>

                            <p className="text-sm text-slate-500">
                                {section.value} record(s)
                            </p>
                        </div>

                        <div className="flex items-center gap-3">
                            <span
                                className={
                                    section.complete
                                        ? "rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700"
                                        : "rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500"
                                }
                            >
                                {section.complete ? "Complete" : "Incomplete"}
                            </span>

                            <ChevronRight className="h-5 w-5 text-slate-400" />
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
