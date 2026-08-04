/*
|--------------------------------------------------------------------------
| Production Flexibility Section™
|--------------------------------------------------------------------------
|
| DIGESTEX Capability Framework™
|
| Measures manufacturing flexibility and responsiveness.
|
|--------------------------------------------------------------------------
*/

import {
    Zap,
    PackageCheck,
    Boxes,
    Palette,
    Clock3,
    Sparkles,
    BadgeCheck,
} from "lucide-react";

import CheckboxCard from "@/Components/Onboarding/Shared/CheckboxCard";

export default function FlexibilitySection({ framework, data, setData }) {
    const profile = framework?.capability_profile ?? "manufacturer";

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ======================================================
                Header
            ====================================================== */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-violet-100 p-3">
                    <Zap className="h-6 w-6 text-violet-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        Production Flexibility™
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        Show buyers how flexible your manufacturing operation is
                        in handling different order requirements.
                    </p>
                </div>
            </div>

            {/* ======================================================
                Framework
            ====================================================== */}

            <div className="mt-6 rounded-2xl border border-violet-200 bg-violet-50 p-5">
                <div className="font-semibold text-violet-700">
                    Active Capability Framework
                </div>

                <div className="mt-2 text-lg font-bold">
                    {formatProfile(profile)}
                </div>
            </div>

            {/* ======================================================
                Flexibility Options
            ====================================================== */}

            <div className="mt-8 grid gap-5 md:grid-cols-2">
                <CheckboxCard
                    icon={Boxes}
                    title="Accept Small MOQ"
                    description="Able to handle small production quantities."
                    checked={data.accept_small_moq ?? false}
                    onChange={(value) => setData("accept_small_moq", value)}
                />

                <CheckboxCard
                    icon={PackageCheck}
                    title="Sampling Service"
                    description="Provide samples before bulk production."
                    checked={data.sampling_service ?? false}
                    onChange={(value) => setData("sampling_service", value)}
                />

                <CheckboxCard
                    icon={Palette}
                    title="Custom Development"
                    description="Develop custom colors, fabrics or products."
                    checked={data.custom_development ?? false}
                    onChange={(value) => setData("custom_development", value)}
                />

                <CheckboxCard
                    icon={Sparkles}
                    title="Make-to-Order"
                    description="Production based on customer specifications."
                    checked={data.make_to_order ?? false}
                    onChange={(value) => setData("make_to_order", value)}
                />

                <CheckboxCard
                    icon={Clock3}
                    title="Fast Replenishment"
                    description="Support repeat orders with shorter lead time."
                    checked={data.fast_replenishment ?? false}
                    onChange={(value) => setData("fast_replenishment", value)}
                />

                <CheckboxCard
                    icon={Zap}
                    title="Urgent Order Support"
                    description="Able to prioritize urgent production requests."
                    checked={data.urgent_order ?? false}
                    onChange={(value) => setData("urgent_order", value)}
                />
            </div>

            {/* ======================================================
                Summary
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <div className="font-bold">Flexibility Summary™</div>

                <div className="mt-4 grid gap-3">
                    <SummaryRow
                        label="Small MOQ"
                        value={yesNo(data.accept_small_moq)}
                    />

                    <SummaryRow
                        label="Sampling"
                        value={yesNo(data.sampling_service)}
                    />

                    <SummaryRow
                        label="Custom Development"
                        value={yesNo(data.custom_development)}
                    />

                    <SummaryRow
                        label="Make-to-Order"
                        value={yesNo(data.make_to_order)}
                    />

                    <SummaryRow
                        label="Fast Replenishment"
                        value={yesNo(data.fast_replenishment)}
                    />

                    <SummaryRow
                        label="Urgent Orders"
                        value={yesNo(data.urgent_order)}
                    />
                </div>
            </div>

            {/* ======================================================
                DIGESTEX Intelligence
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-violet-100 bg-violet-50 p-6">
                <div className="flex items-center gap-2 font-bold text-violet-700">
                    <BadgeCheck className="h-5 w-5" />
                    DIGESTEX Flexibility Intelligence™
                </div>

                <p className="mt-3 text-sm leading-6 text-slate-600">
                    Flexible manufacturers are more attractive to brands, buying
                    offices, private labels, and companies seeking responsive
                    supply chain partners.
                </p>
            </div>
        </section>
    );
}

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function formatProfile(profile) {
    return profile
        .replaceAll("_", " ")
        .replace(/\b\w/g, (c) => c.toUpperCase());
}

function yesNo(value) {
    return value ? "Yes" : "No";
}

function SummaryRow({ label, value }) {
    return (
        <div className="flex items-center justify-between border-b border-slate-200 py-2">
            <span className="text-sm text-slate-500">{label}</span>

            <span className="font-medium">{value}</span>
        </div>
    );
}
