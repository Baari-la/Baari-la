/*
|--------------------------------------------------------------------------
| Sustainability Section™
|--------------------------------------------------------------------------
|
| DIGESTEX Capability Framework™
|
| Manufacturing sustainability capability.
|
|--------------------------------------------------------------------------
*/

import { Leaf, Recycle, Sun, Droplets, BadgeCheck } from "lucide-react";

import CheckboxCard from "@/Components/Onboarding/Shared/CheckboxCard";

export default function SustainabilitySection({ framework, data, setData }) {
    const profile = framework?.capability_profile ?? "manufacturer";

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ======================================================
                Header
            ====================================================== */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-green-100 p-3">
                    <Leaf className="h-6 w-6 text-green-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        Sustainability Capability™
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        Describe your sustainability initiatives to strengthen
                        buyer confidence and ESG readiness.
                    </p>
                </div>
            </div>

            {/* ======================================================
                Framework
            ====================================================== */}

            <div className="mt-6 rounded-2xl border border-green-200 bg-green-50 p-5">
                <div className="font-semibold text-green-700">
                    Active Capability Framework
                </div>

                <div className="mt-2 text-lg font-bold">
                    {formatProfile(profile)}
                </div>
            </div>

            {/* ======================================================
                Sustainability Programs
            ====================================================== */}

            <div className="mt-8 grid gap-5 md:grid-cols-2">
                <CheckboxCard
                    icon={Leaf}
                    title="ESG Program"
                    description="Environmental, Social & Governance initiatives."
                    checked={data.esg_program ?? false}
                    onChange={(value) => setData("esg_program", value)}
                />

                <CheckboxCard
                    icon={Sun}
                    title="Renewable Energy"
                    description="Solar, biomass or renewable energy usage."
                    checked={data.renewable_energy ?? false}
                    onChange={(value) => setData("renewable_energy", value)}
                />

                <CheckboxCard
                    icon={Recycle}
                    title="Recycled Materials"
                    description="Use of recycled fibers or recycled raw materials."
                    checked={data.recycled_material ?? false}
                    onChange={(value) => setData("recycled_material", value)}
                />

                <CheckboxCard
                    icon={Droplets}
                    title="Wastewater Treatment"
                    description="Wastewater treatment facility (WWTP / IPAL)."
                    checked={data.wastewater_treatment ?? false}
                    onChange={(value) => setData("wastewater_treatment", value)}
                />
            </div>

            {/* ======================================================
                Notes
            ====================================================== */}

            <div className="mt-8">
                <label className="mb-2 block font-semibold">
                    Sustainability Notes
                </label>

                <textarea
                    rows={5}
                    value={data.sustainability_notes ?? ""}
                    onChange={(e) =>
                        setData("sustainability_notes", e.target.value)
                    }
                    placeholder="Describe your sustainability initiatives..."
                    className="w-full rounded-2xl border border-slate-300 p-4 focus:border-green-500 focus:outline-none"
                />
            </div>

            {/* ======================================================
                Sustainability Summary
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <div className="font-bold">Sustainability Summary™</div>

                <div className="mt-4 grid gap-3">
                    <SummaryRow
                        label="ESG Program"
                        value={yesNo(data.esg_program)}
                    />

                    <SummaryRow
                        label="Renewable Energy"
                        value={yesNo(data.renewable_energy)}
                    />

                    <SummaryRow
                        label="Recycled Materials"
                        value={yesNo(data.recycled_material)}
                    />

                    <SummaryRow
                        label="Wastewater Treatment"
                        value={yesNo(data.wastewater_treatment)}
                    />
                </div>
            </div>

            {/* ======================================================
                Intelligence
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-green-100 bg-green-50 p-6">
                <div className="flex items-center gap-2 font-bold text-green-700">
                    <BadgeCheck className="h-5 w-5" />
                    DIGESTEX Sustainability Intelligence™
                </div>

                <p className="mt-3 text-sm leading-6 text-slate-600">
                    Sustainability capability strengthens ESG readiness,
                    responsible sourcing, buyer confidence, and international
                    market visibility.
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
