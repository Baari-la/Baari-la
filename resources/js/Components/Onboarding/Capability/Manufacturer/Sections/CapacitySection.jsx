/*
|--------------------------------------------------------------------------
| Capacity Section™
|--------------------------------------------------------------------------
|
| DIGESTEX Capability Framework™
|
| Production capacity and operational capability.
|
|--------------------------------------------------------------------------
*/
import { usePage } from "@inertiajs/react";
import { Gauge, Package, CalendarClock, Boxes } from "lucide-react";

export default function CapacitySection({ framework, data, setData }) {
    const { locale } = usePage().props;
    const isEn = locale === "en";

    const profile = framework?.capability_profile ?? "manufacturer";
    const installed = Number(data.installed_monthly_capacity) || 0;

    const used = Number(data.used_monthly_capacity) || 0;

    const utilization =
        installed > 0 ? ((used / installed) * 100).toFixed(1) : 0;

    const available = Math.max(installed - used, 0);

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ======================================================
                Header
            ====================================================== */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-indigo-100 p-3">
                    <Gauge className="h-6 w-6 text-indigo-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        {isEn
                            ? "Capacity Intelligence™"
                            : "Capacity Intelligence™"}
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        {isEn
                            ? "Provide your installed production capacity and current capacity utilization. DIGESTEX automatically calculates utilized capacity, available capacity, production status, and buyer readiness."
                            : "Informasikan kapasitas terpasang dan utilisasi kapasitas perusahaan Anda. DIGESTEX akan menghitung secara otomatis kapasitas terpakai, kapasitas tersedia, status produksi, serta kesiapan perusahaan menerima order baru."}
                    </p>
                </div>
            </div>

            {/* ======================================================
                Active Framework
            ====================================================== */}

            <div className="mt-6 rounded-2xl border border-indigo-200 bg-indigo-50 p-5">
                <div className="font-semibold text-indigo-700">
                    Active Capability Framework
                </div>

                <div className="mt-2 text-lg font-bold">
                    {formatProfile(profile)}
                </div>
            </div>

            {/* ======================================================
                Capacity Form
            ====================================================== */}

            <div className="mt-8 grid gap-6 md:grid-cols-2">
                <InputField
                    icon={Package}
                    label={
                        isEn
                            ? "Installed Monthly Capacity"
                            : "Kapasitas Terpasang per Bulan"
                    }
                    value={data.installed_monthly_capacity ?? ""}
                    placeholder="1000"
                    onChange={(value) =>
                        setData("installed_monthly_capacity", value)
                    }
                />

                <InputField
                    icon={Gauge}
                    label={
                        isEn
                            ? "Current Used Monthly Capacity"
                            : "Kapasitas Terpakai per Bulan"
                    }
                    value={data.used_monthly_capacity ?? ""}
                    placeholder="820"
                    onChange={(value) =>
                        setData("used_monthly_capacity", value)
                    }
                />

                <InputField
                    icon={Boxes}
                    label={
                        isEn
                            ? "Minimum Order Quantity (MOQ)"
                            : "Minimum Order Quantity (MOQ)"
                    }
                    value={data.moq ?? ""}
                    placeholder="500 kg"
                    onChange={(value) => setData("moq", value)}
                />

                <InputField
                    icon={CalendarClock}
                    label={isEn ? "Lead Time" : "Lead Time"}
                    value={data.lead_time ?? ""}
                    placeholder="30 Days"
                    onChange={(value) => setData("lead_time", value)}
                />
            </div>

            {/* ======================================================
                Summary
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <div className="font-bold">Capacity Summary™</div>

                <div className="mt-4 grid gap-3">
                    <SummaryRow
                        label="Framework"
                        value={formatProfile(profile)}
                    />

                    <SummaryRow
                        label={
                            isEn
                                ? "Installed Monthly Capacity"
                                : "Kapasitas Terpasang per Bulan"
                        }
                        value={data.monthly_capacity || "-"}
                    />

                    <SummaryRow label="MOQ" value={data.moq || "-"} />

                    <SummaryRow
                        label="Lead Time"
                        value={data.lead_time || "-"}
                    />

                    <SummaryRow
                        label={
                            isEn
                                ? "Capacity Utilization"
                                : "Utilisasi Kapasitas"
                        }
                    />
                </div>
            </div>

            {/* ======================================================
                DIGESTEX Intelligence
            ====================================================== */}

            <div className="mt-8 rounded-2xl border border-indigo-100 bg-indigo-50 p-5">
                <div className="font-bold text-indigo-700">
                    DIGESTEX Capacity Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "Installed capacity information enables DIGESTEX to calculate utilized capacity, available production capacity, production status, and buyer readiness."
                        : "Informasi kapasitas terpasang memungkinkan DIGESTEX menghitung kapasitas terpakai, kapasitas tersedia, status produksi, dan kesiapan perusahaan menerima order baru."}
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

function InputField({ icon: Icon, label, value, onChange, placeholder }) {
    return (
        <div>
            <label className="mb-2 flex items-center gap-2 font-semibold">
                <Icon className="h-4 w-4 text-slate-500" />

                {label}
            </label>

            <input
                type="text"
                value={value}
                placeholder={placeholder}
                onChange={(e) => onChange(e.target.value)}
                className="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:outline-none"
            />
        </div>
    );
}

function SummaryRow({ label, value }) {
    return (
        <div className="flex items-center justify-between border-b border-slate-200 py-2">
            <span className="text-sm text-slate-500">{label}</span>

            <span className="font-medium">{value}</span>
        </div>
    );
}
