import { Package, TrendingUp, TrendingDown, Minus } from "lucide-react";
import { usePage } from "@inertiajs/react";

const defaultProducts = [
    {
        rank: 1,
        hsCode: "5205",
        product: "Cotton Yarn",
        exportValue: "US$ 0",
        growth: 0,
        share: 0,
    },
    {
        rank: 2,
        hsCode: "5402",
        product: "Polyester Yarn",
        exportValue: "US$ 0",
        growth: 0,
        share: 0,
    },
    {
        rank: 3,
        hsCode: "6006",
        product: "Knitted Fabric",
        exportValue: "US$ 0",
        growth: 0,
        share: 0,
    },
    {
        rank: 4,
        hsCode: "6109",
        product: "T-Shirts",
        exportValue: "US$ 0",
        growth: 0,
        share: 0,
    },
    {
        rank: 5,
        hsCode: "6203",
        product: "Men's Garments",
        exportValue: "US$ 0",
        growth: 0,
        share: 0,
    },
];

function GrowthIndicator({ value, isEn }) {
    const numericValue = Number(value || 0);

    if (numericValue > 0) {
        return (
            <div className="flex items-center justify-end gap-1 font-semibold text-emerald-600">
                <TrendingUp size={15} />

                <span>+{numericValue.toFixed(1)}%</span>
            </div>
        );
    }

    if (numericValue < 0) {
        return (
            <div className="flex items-center justify-end gap-1 font-semibold text-red-600">
                <TrendingDown size={15} />

                <span>{numericValue.toFixed(1)}%</span>
            </div>
        );
    }

    return (
        <div className="flex items-center justify-end gap-1 font-semibold text-slate-500">
            <Minus size={15} />

            <span>{isEn ? "Stable" : "Stabil"}</span>
        </div>
    );
}

export default function ProductPerformanceTable({
    title,
    products = defaultProducts,
}) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const displayTitle =
        title || (isEn ? "Top Product Performance" : "Kinerja Produk Utama");

    return (
        <div className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            {/* HEADER */}
            <div className="border-b border-slate-100 px-6 py-5">
                <div className="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div className="flex items-start gap-3">
                        <div className="rounded-xl bg-indigo-50 p-2">
                            <Package size={24} className="text-indigo-600" />
                        </div>

                        <div>
                            <h3 className="text-xl font-bold text-slate-900">
                                {displayTitle}
                            </h3>

                            <p className="mt-1 text-sm text-slate-500">
                                {isEn
                                    ? "Top textile products by export value"
                                    : "Produk tekstil utama berdasarkan nilai ekspor"}
                            </p>
                        </div>
                    </div>

                    {/* DATA PERIOD */}
                    <div className="shrink-0 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-500">
                        {isEn
                            ? "Data through June 2026"
                            : "Data sampai Juni 2026"}
                    </div>
                </div>
            </div>

            {/* TABLE */}
            <div className="overflow-x-auto">
                <table className="min-w-full">
                    <thead className="bg-slate-50">
                        <tr>
                            <th className="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                #
                            </th>

                            <th className="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                {isEn ? "Product" : "Produk"}
                            </th>

                            <th className="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                HS Code
                            </th>

                            <th className="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                {isEn ? "Export" : "Ekspor"}
                            </th>

                            <th className="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                {isEn ? "Growth" : "Pertumbuhan"}
                            </th>

                            <th className="w-24 px-5 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                                {isEn ? "Share" : "Pangsa"}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        {products.map((item) => (
                            <tr
                                key={`${item.rank}-${item.hs_code}`}
                                className="border-t border-slate-100 transition hover:bg-slate-50"
                            >
                                {/* RANK */}
                                <td className="px-5 py-4">
                                    <span className="rounded-full bg-indigo-600 px-3 py-1 text-xs font-bold text-white">
                                        {item.rank}
                                    </span>
                                </td>

                                {/* PRODUCT */}
                                <td className="px-5 py-4">
                                    <p className="max-w-[280px] font-semibold text-slate-900">
                                        {item.product?.length > 70
                                            ? `${item.product.substring(0, 70)}...`
                                            : item.product}
                                    </p>
                                </td>

                                {/* HS */}
                                <td className="px-5 py-4">
                                    <span className="rounded bg-slate-100 px-2 py-1 font-mono text-sm text-slate-900">
                                        {item.hs_code}
                                    </span>
                                </td>

                                {/* EXPORT */}
                                <td className="px-5 py-4 text-right font-semibold text-slate-900">
                                    US${" "}
                                    {Number(
                                        item.export_million ?? 0,
                                    ).toLocaleString(undefined, {
                                        maximumFractionDigits: 1,
                                    })}{" "}
                                    M
                                </td>

                                {/* GROWTH */}
                                <td className="px-5 py-4 text-right">
                                    <GrowthIndicator
                                        value={item.growth}
                                        isEn={isEn}
                                    />
                                </td>

                                {/* SHARE */}
                                <td className="w-24 px-5 py-4 text-center">
                                    <span className="font-semibold text-emerald-600">
                                        {Number(item.share ?? 0).toFixed(1)}%
                                    </span>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {/* TIER A TEASER */}
            <div className="border-t border-slate-200 bg-slate-50 px-6 py-6">
                <div className="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                    <div className="max-w-3xl">
                        <div className="text-xs font-black uppercase tracking-[0.2em] text-slate-700">
                            {isEn
                                ? "TIER A TRADE INTELLIGENCE"
                                : "TIER A TRADE INTELLIGENCE"}
                        </div>

                        <p className="mt-2 text-sm leading-relaxed text-slate-600">
                            {isEn
                                ? "Public preview. Tier A provides deeper product and HS-level trade intelligence, including export and import flows, country markets, trade value, volume, growth, and specialized unit-based analysis for garment products."
                                : "Preview publik. Tier A menyediakan product dan HS-level trade intelligence yang lebih mendalam, termasuk arus ekspor dan impor, pasar negara, nilai perdagangan, volume, pertumbuhan, serta analisis berbasis satuan khusus untuk produk garment."}
                        </p>
                    </div>

                    <div className="shrink-0">
                        <span className="inline-flex items-center rounded-xl bg-slate-900 px-5 py-3 text-xs font-black uppercase tracking-wider text-white">
                            {isEn ? "TIER A ACCESS" : "AKSES TIER A"}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    );
}
