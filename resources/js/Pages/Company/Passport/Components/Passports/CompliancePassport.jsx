import { ShieldCheck, ChevronRight } from "lucide-react";

export default function CompliancePassport({ passport }) {
    const sections = [
        ["Certifications", passport?.passport?.certifications],

        ["Social Compliance", passport?.passport?.social],

        ["Environmental", passport?.passport?.environmental],

        ["Traceability", passport?.passport?.traceability],

        ["Domestic Regulation", passport?.passport?.domestic_regulation],

        ["Market Regulation", passport?.passport?.market_regulation],
    ];

    return (
        <div className="rounded-2xl border bg-white shadow-sm">
            <div className="flex items-center gap-3 border-b px-6 py-4">
                <ShieldCheck className="h-6 w-6 text-emerald-600" />

                <div>
                    <h2 className="text-xl font-bold">Compliance Passport</h2>

                    <p className="text-sm text-slate-500">
                        Compliance readiness and certifications.
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
                                {item?.count ?? 0} record(s)
                            </p>
                        </div>

                        <div className="flex items-center gap-3">
                            <span
                                className={
                                    item?.is_complete
                                        ? "rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700"
                                        : "rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700"
                                }
                            >
                                {item?.is_complete ? "Available" : "Pending"}
                            </span>

                            <ChevronRight className="h-5 w-5 text-slate-400" />
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
