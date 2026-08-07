/*
|--------------------------------------------------------------------------
| DIGESTEX Trade Role Card™
|--------------------------------------------------------------------------
|
| Step 4
| Trade & Supply Chain Profile™
|
| Launch Ready Version
|--------------------------------------------------------------------------
*/

import { usePage } from "@inertiajs/react";
import { Globe, Ship, Factory, Store, TrendingUp } from "lucide-react";

export default function TradeRoleCard({ data, setData }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const t = (en, id) => (isEn ? en : id);

    const options = [
        {
            key: "export",
            icon: Globe,
            title: t("Export Products", "Ekspor Produk"),
            description: t(
                "We export products to international markets.",
                "Kami mengekspor produk ke pasar internasional.",
            ),
        },

        {
            key: "import",
            icon: Ship,
            title: t("Import Raw Materials", "Impor Bahan Baku"),
            description: t(
                "We import raw materials or supporting materials.",
                "Kami mengimpor bahan baku atau bahan pendukung.",
            ),
        },

        {
            key: "supplier",
            icon: Factory,
            title: t("Supply to Exporters", "Memasok ke Eksportir"),
            description: t(
                "We supply manufacturers or exporters.",
                "Kami memasok produsen atau eksportir.",
            ),
        },

        {
            key: "domestic",
            icon: Store,
            title: t("Domestic Market", "Pasar Domestik"),
            description: t(
                "We mainly serve the domestic market.",
                "Kami melayani pasar domestik.",
            ),
        },

        {
            key: "planning",
            icon: TrendingUp,
            title: t("Planning to Export", "Berencana Ekspor"),
            description: t(
                "We plan to start exporting.",
                "Kami berencana mulai ekspor.",
            ),
        },
    ];

    const roles = data.trade_roles ?? [];

    const toggleRole = (role) => {
        if (roles.includes(role)) {
            setData(
                "trade_roles",
                roles.filter((item) => item !== role),
            );

            return;
        }

        setData("trade_roles", [...roles, role]);
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ------------------------------------------------ */}
            {/* Header                                            */}
            {/* ------------------------------------------------ */}

            <div>
                <h2 className="text-2xl font-black text-slate-900">
                    {t("Trade Role™", "Peran Perdagangan™")}
                </h2>

                <p className="mt-3 max-w-3xl leading-7 text-slate-600">
                    {t(
                        "Select all activities that best describe your company's role in the textile supply chain.",
                        "Pilih seluruh aktivitas yang paling menggambarkan peran perusahaan Anda dalam rantai pasok tekstil.",
                    )}
                </p>
            </div>

            {/* ------------------------------------------------ */}
            {/* Cards                                             */}
            {/* ------------------------------------------------ */}

            <div className="mt-8 grid gap-5 md:grid-cols-2">
                {options.map((option) => {
                    const selected = roles.includes(option.key);

                    const Icon = option.icon;

                    return (
                        <button
                            key={option.key}
                            type="button"
                            onClick={() => toggleRole(option.key)}
                            className={`
                                rounded-2xl
                                border
                                p-6
                                text-left
                                transition
                                duration-200

                                ${
                                    selected
                                        ? "border-emerald-500 bg-emerald-50 shadow-sm"
                                        : "border-slate-200 hover:border-emerald-300 hover:bg-slate-50"
                                }
                            `}
                        >
                            <div className="flex items-start gap-4">
                                <div
                                    className={`
                                        rounded-2xl
                                        p-3

                                        ${
                                            selected
                                                ? "bg-emerald-600 text-white"
                                                : "bg-slate-100 text-slate-600"
                                        }
                                    `}
                                >
                                    <Icon className="h-6 w-6" />
                                </div>

                                <div className="flex-1">
                                    <div className="flex items-center justify-between">
                                        <h3 className="font-bold text-slate-900">
                                            {option.title}
                                        </h3>

                                        <div
                                            className={`
                                                h-6
                                                w-6
                                                rounded-full
                                                border-2

                                                ${
                                                    selected
                                                        ? "border-emerald-600 bg-emerald-600"
                                                        : "border-slate-300"
                                                }
                                            `}
                                        >
                                            {selected && (
                                                <div className="flex h-full items-center justify-center text-xs font-black text-white">
                                                    ✓
                                                </div>
                                            )}
                                        </div>
                                    </div>

                                    <p className="mt-2 text-sm leading-6 text-slate-500">
                                        {option.description}
                                    </p>
                                </div>
                            </div>
                        </button>
                    );
                })}
            </div>

            {/* ------------------------------------------------ */}
            {/* Footer                                            */}
            {/* ------------------------------------------------ */}

            <div className="mt-8 rounded-2xl border border-indigo-100 bg-indigo-50 p-5">
                <div className="font-bold text-indigo-700">
                    {t("Why is this important?", "Mengapa ini penting?")}
                </div>

                <p className="mt-2 leading-7 text-slate-600">
                    {t(
                        "Your trade role helps DIGESTEX understand where your company fits within the global textile supply chain. This information will improve Buyer Trust™, Visibility™, Smart Business Matching™, and future Global RFQ recommendations.",
                        "Peran perdagangan membantu DIGESTEX memahami posisi perusahaan Anda dalam rantai pasok tekstil global. Informasi ini akan meningkatkan Buyer Trust™, Visibility™, Smart Business Matching™, serta rekomendasi Global RFQ di masa mendatang.",
                    )}
                </p>
            </div>
        </div>
    );
}
