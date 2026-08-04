/*
|--------------------------------------------------------------------------
| DIGESTEX Operational Summary Card™
|--------------------------------------------------------------------------
|
| Displays the company's operational capability based on
| the information entered in Step 3.
|
| This card focuses on operational readiness rather than
| business classification.
|
|--------------------------------------------------------------------------
*/

import {
    Factory,
    Package,
    Clock3,
    Globe,
    BadgeCheck,
    Wrench,
} from "lucide-react";

import SummaryRow from "./SummaryRow";
import StatusRow from "./StatusRow";

export default function OperationalSummaryCard({ data = {} }) {
    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm">
            {/* Header */}

            <div className="flex items-center gap-3">
                <Factory className="h-7 w-7 text-indigo-600" />

                <div>
                    <h2 className="text-xl font-black">Operational Summary™</h2>

                    <p className="text-sm text-slate-500">
                        Live Operational Preview
                    </p>
                </div>
            </div>

            {/* Production */}

            <div className="mt-7">
                <SummaryRow
                    icon={Factory}
                    label="Production Capacity"
                    value={formatCapacity(
                        data.production_capacity,
                        data.capacity_unit,
                    )}
                />

                <SummaryRow
                    icon={Package}
                    label="Minimum Order"
                    value={formatCapacity(data.moq, data.moq_unit)}
                />

                <SummaryRow
                    icon={Clock3}
                    label="Lead Time"
                    value={data.lead_time ? `${data.lead_time} Days` : "-"}
                />

                <SummaryRow
                    icon={Globe}
                    label="Export Ready"
                    value={data.export_ready ? "Yes" : "No"}
                />
            </div>

            {/* Operational Readiness */}

            <div className="mt-8 rounded-2xl bg-slate-50 p-5">
                <div className="mb-4 flex items-center gap-2">
                    <BadgeCheck className="h-5 w-5 text-emerald-600" />

                    <span className="font-bold text-slate-700">
                        Operational Readiness
                    </span>
                </div>

                <StatusRow label="OEM Manufacturing" status={data.oem} />

                <StatusRow label="ODM Manufacturing" status={data.odm} />

                <StatusRow label="Private Label" status={data.private_label} />

                <StatusRow label="Full Package" status={data.full_package} />

                <StatusRow label="CMT Service" status={data.cmt} />

                <StatusRow
                    label="Design Support"
                    status={data.design_support}
                />

                <StatusRow
                    label="Sampling Service"
                    status={data.sampling_service}
                />

                <StatusRow
                    label="Quick Response"
                    status={data.quick_response}
                />

                <StatusRow
                    label="Production Flexibility"
                    status={data.production_flexibility}
                />

                <StatusRow
                    label="Small Batch Production"
                    status={data.small_batch}
                />

                <StatusRow label="Fast Sampling" status={data.fast_sampling} />

                <StatusRow
                    label="Custom Product Development"
                    status={data.custom_product_development}
                />
            </div>

            {/* Footer */}

            <div className="mt-6 flex items-center gap-2 rounded-2xl border border-indigo-100 bg-indigo-50 p-4">
                <Wrench className="h-5 w-5 text-indigo-600" />

                <p className="text-sm leading-6 text-slate-600">
                    This operational summary is used by Company Intelligence™,
                    Smart Business Matching™, Buyer Readiness™, and Executive
                    Dashboard™.
                </p>
            </div>
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Helper
|--------------------------------------------------------------------------
*/

function formatCapacity(value, unit) {
    if (!value) {
        return "-";
    }

    return unit ? `${value} ${unit}` : value;
}
