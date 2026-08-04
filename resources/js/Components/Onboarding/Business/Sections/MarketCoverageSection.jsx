/*
|--------------------------------------------------------------------------
| Market Coverage Section™
|--------------------------------------------------------------------------
|
| Step 2
|
| Defines the company's market coverage and export
| experience for DIGESTEX Market Intelligence™.
|
|--------------------------------------------------------------------------
*/

import { Globe2, Plane, Building2, TrendingUp } from "lucide-react";

export default function MarketCoverageSection({
    locale,
    data,
    setData,
    errors = {},
}) {
    const isEn = locale === "en";

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* =======================================================
                Header
            ======================================================= */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-emerald-100 p-3">
                    <Globe2 className="h-6 w-6 text-emerald-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        {isEn ? "Market Coverage" : "Cakupan Pasar"}
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        {isEn
                            ? "Tell us where your products are sold. This information supports Buyer Matching™, Export Intelligence™, and Global Market Insights."
                            : "Beritahu kami ke mana produk Anda dipasarkan. Informasi ini mendukung Buyer Matching™, Export Intelligence™, dan Global Market Insights."}
                    </p>
                </div>
            </div>

            {/* =======================================================
                Market Coverage
            ======================================================= */}

            <div className="mt-8 grid gap-6 md:grid-cols-2">
                {/* Domestic */}

                <CheckCard
                    icon={Building2}
                    title={isEn ? "Domestic Market" : "Pasar Domestik"}
                    description={
                        isEn
                            ? "Serving customers within Indonesia."
                            : "Melayani pelanggan di Indonesia."
                    }
                    checked={data.domestic_market}
                    onChange={(value) => setData("domestic_market", value)}
                />

                {/* Export */}

                <CheckCard
                    icon={Plane}
                    title={isEn ? "Export Market" : "Pasar Ekspor"}
                    description={
                        isEn
                            ? "Selling products to international markets."
                            : "Menjual produk ke pasar internasional."
                    }
                    checked={data.export_market}
                    onChange={(value) => setData("export_market", value)}
                />
            </div>

            {/* =======================================================
                Export Experience
            ======================================================= */}

            <div className="mt-8">
                <label className="mb-2 flex items-center gap-2 font-semibold">
                    <TrendingUp className="h-4 w-4 text-slate-500" />

                    {isEn
                        ? "Export Experience (Years)"
                        : "Pengalaman Ekspor (Tahun)"}
                </label>

                <input
                    type="number"
                    min="0"
                    value={data.export_experience_years}
                    onChange={(e) =>
                        setData("export_experience_years", e.target.value)
                    }
                    className={`w-full rounded-xl border px-4 py-3 focus:border-indigo-500 focus:outline-none ${
                        errors.export_experience_years
                            ? "border-red-400"
                            : "border-slate-300"
                    }`}
                    placeholder={isEn ? "Example: 15" : "Contoh: 15"}
                />

                {errors.export_experience_years && (
                    <p className="mt-2 text-sm text-red-600">
                        {errors.export_experience_years}
                    </p>
                )}
            </div>

            {/* =======================================================
                Market Summary
            ======================================================= */}

            <div className="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <div className="font-bold">
                    {isEn ? "Market Summary" : "Ringkasan Pasar"}
                </div>

                <div className="mt-4 flex flex-wrap gap-2">
                    {data.domestic_market && <Badge label="Domestic Market" />}

                    {data.export_market && <Badge label="Export Market" />}

                    {data.export_market && data.export_experience_years && (
                        <Badge
                            label={`${data.export_experience_years} Years Export`}
                        />
                    )}

                    {!data.domestic_market && !data.export_market && (
                        <span className="text-sm text-slate-500">
                            {isEn
                                ? "No market selected."
                                : "Belum memilih cakupan pasar."}
                        </span>
                    )}
                </div>
            </div>

            {/* =======================================================
                Intelligence
            ======================================================= */}

            <div className="mt-8 rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
                <div className="font-bold text-emerald-700">
                    DIGESTEX Market Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "Market information helps DIGESTEX recommend buyers, identify export opportunities, and improve your company's visibility in global sourcing."
                        : "Informasi pasar membantu DIGESTEX merekomendasikan buyer, mengidentifikasi peluang ekspor, dan meningkatkan visibilitas perusahaan Anda dalam global sourcing."}
                </p>
            </div>
        </section>
    );
}

/*
|--------------------------------------------------------------------------
| Check Card
|--------------------------------------------------------------------------
*/

function CheckCard({ icon: Icon, title, description, checked, onChange }) {
    return (
        <label className="cursor-pointer rounded-2xl border border-slate-200 p-5 transition hover:border-indigo-300 hover:bg-slate-50">
            <div className="flex items-start gap-4">
                <input
                    type="checkbox"
                    checked={checked}
                    onChange={(e) => onChange(e.target.checked)}
                    className="mt-1 h-5 w-5 rounded border-slate-300 text-indigo-600"
                />

                <div className="flex-1">
                    <div className="flex items-center gap-2">
                        <Icon className="h-5 w-5 text-indigo-600" />

                        <div className="font-semibold">{title}</div>
                    </div>

                    <p className="mt-2 text-sm text-slate-500">{description}</p>
                </div>
            </div>
        </label>
    );
}

/*
|--------------------------------------------------------------------------
| Badge
|--------------------------------------------------------------------------
*/

function Badge({ label }) {
    return (
        <span className="rounded-full bg-indigo-100 px-4 py-2 text-sm font-semibold text-indigo-700">
            {label}
        </span>
    );
}
